<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Kosarica;
use App\Models\UserAddress;
use App\Models\NacinPlacanja;
use App\Models\Narudzba;
use App\Models\DetaljiNarudzbe;
use App\Models\Payment;
use App\Models\PcConfiguration;
use App\Models\PromoKod;
use App\Mail\OrderReceiptMail;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $addresses = UserAddress::where('user_id', $user->id)->get();
        $paymentMethods = NacinPlacanja::whereNotIn('NacinPlacanja_ID', [1, 6])->get();

        $cartItems = Kosarica::where('korisnik_id', $user->id)
            ->with('proizvod')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Vaša košarica je prazna.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->proizvod->Cijena * $item->kolicina;
        });

        $savedPromoCode = session('promo_code');
        $savedPromoDiscount = 0;

        if ($savedPromoCode) {
            $promo = PromoKod::where('kod', $savedPromoCode)->where('aktivno', true)->first();
            if ($promo) {
                $savedPromoDiscount = $promo->tip === 'postotak'
                    ? round($total * ($promo->vrijednost / 100), 2)
                    : round($promo->vrijednost, 2);
                session(['promo_discount' => $savedPromoDiscount]);
            } else {
                session()->forget(['promo_code', 'promo_discount']);
                $savedPromoCode = null;
            }
        }

        $discountedTotal = max(0, $total - $savedPromoDiscount);
        $delivery     = 5.00;
        $freeShipping = $discountedTotal >= 50;
        $grandTotal   = $freeShipping ? $discountedTotal : $discountedTotal + $delivery;

        return view('checkout', compact('addresses', 'paymentMethods', 'cartItems', 'total', 'delivery', 'freeShipping', 'grandTotal', 'savedPromoCode', 'savedPromoDiscount'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        Log::info('CheckoutController@store called', ['user_id' => $user->id ?? null]);

        $validator = Validator::make($request->all(), [
            'adresa_dostave'    => 'required|string|max:255',
            'nacin_placanja_id' => 'required|exists:nacin_placanja,NacinPlacanja_ID',
            'promo_code'        => 'nullable|string|exists:promo_kodovi,kod',
        ]);

        if ($validator->fails()) {
            Log::warning('Checkout validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input'  => $request->all(),
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $paymentMethod = NacinPlacanja::findOrFail($validated['nacin_placanja_id']);
        $isCardPayment = ((int) $paymentMethod->NacinPlacanja_ID === config('shop.card_payment_id'));

        $cartItems = Kosarica::where('korisnik_id', $user->id)->with('proizvod')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Vaša košarica je prazna.');
        }

        $total = $cartItems->sum(function ($item) {
            return $item->proizvod->Cijena * $item->kolicina;
        });

        $promo    = null;
        $discount = 0;

        if (!empty($validated['promo_code'])) {
            $promo = PromoKod::where('kod', $validated['promo_code'])
                ->where('aktivno', true)
                ->first();

            if ($promo) {
                $now     = Carbon::now();
                $isValid = true;

                if (($promo->vrijedi_od && $now->lt($promo->vrijedi_od)) ||
                    ($promo->vrijedi_do && $now->gt($promo->vrijedi_do)) ||
                    ($promo->max_koristenja && $promo->koristenja >= $promo->max_koristenja) ||
                    ($total < $promo->minimalan_iznos)) {
                    $isValid = false;
                }

                if ($isValid) {
                    $discount = $promo->tip === 'postotak'
                        ? $total * ($promo->vrijednost / 100)
                        : $promo->vrijednost;
                } else {
                    $promo = null; 
                }
            }
        }

        DB::beginTransaction();

        try {
            $cartItems = Kosarica::where('korisnik_id', $user->id)
                ->with('proizvod')
                ->lockForUpdate()
                ->get();

            if ($cartItems->isEmpty()) {
                DB::rollBack();
                return redirect()->route('cart.index')->with('error', 'Vaša košarica je prazna.');
            }

            $lockedProizvodi = [];
            $total           = 0;

            foreach ($cartItems as $item) {
                $proizvod = \App\Models\Proizvod::lockForUpdate()->find($item->proizvod_id);

                if (!$proizvod) {
                    throw new \Exception('Jedan od proizvoda više nije dostupan.');
                }

                if ($item->kolicina < 1) {
                    throw new \Exception("Nevažeća količina za \"{$proizvod->Naziv}\".");
                }

                if ($proizvod->StanjeNaSkladistu < $item->kolicina) {
                    $stockMsg = $proizvod->StanjeNaSkladistu <= 0
                        ? "Nažalost, \"{$proizvod->Naziv}\" trenutno nije dostupan."
                        : "Nažalost, \"{$proizvod->Naziv}\" nema dovoljno zaliha. " .
                          "Dostupno: {$proizvod->StanjeNaSkladistu}, traženo: {$item->kolicina}.";
                    throw new \Exception($stockMsg);
                }

                $lockedProizvodi[$item->proizvod_id] = $proizvod;
                $total += $proizvod->Cijena * $item->kolicina;
            }

            if ($promo) {
                $discount = $promo->tip === 'postotak'
                    ? $total * ($promo->vrijednost / 100)
                    : $promo->vrijednost;

                $promo->increment('koristenja');
            }

            $discountedTotal = max(0, $total - $discount);
            $deliveryCost = $discountedTotal >= 50 ? 0 : 5.00;
            $finalTotal = $discountedTotal + $deliveryCost;

            Log::info('Attempting to create order', [
                'user'       => $user->id ?? null,
                'total'      => $total,
                'discount'   => $discount,
                'delivery'   => $deliveryCost,
                'finalTotal' => $finalTotal,
            ]);

            $napomena = session('pc_sastavljanje')
                ? 'Kupac želi sastavljeno računalo.'
                : null;

            $order = Narudzba::create([
                'Kupac_ID'         => $user->id,
                'NacinPlacanja_ID' => $paymentMethod->NacinPlacanja_ID,
                'Datum_narudzbe'   => now()->format('Y-m-d H:i:s'),
                'Ukupni_iznos'     => $finalTotal,
                'Adresa_dostave'   => $validated['adresa_dostave'],
                'Status'           => 'U obradi',
                'napomena'         => $napomena,
            ]);

            session()->forget(['pc_sastavljanje', 'promo_code', 'promo_discount']);

            Log::info('Order created', ['Narudzba_ID' => $order->Narudzba_ID ?? null]);

            foreach ($cartItems as $item) {
                $proizvod = $lockedProizvodi[$item->proizvod_id];

                DetaljiNarudzbe::create([
                    'Narudzba_ID'      => $order->Narudzba_ID,
                    'Proizvod_ID'      => $item->proizvod_id,
                    'Kolicina'         => $item->kolicina,
                    'cijena_po_komadu' => $proizvod->Cijena,
                ]);
            }

            Kosarica::where('korisnik_id', $user->id)->delete();

            if ($isCardPayment) {
                $payment = Payment::create([
                    'narudzba_id' => $order->Narudzba_ID,
                    'provider'    => 'fakepay',
                    'reference'   => 'TS-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6)),
                    'amount'      => $order->Ukupni_iznos,
                    'currency'    => 'EUR',
                    'status'      => 'pending',
                ]);

                DB::commit();

                Log::info('Checkout completed (card), redirecting to FakePay', [
                    'user'        => $user->id ?? null,
                    'Narudzba_ID' => $order->Narudzba_ID ?? null,
                    'Payment_ID'  => $payment->id ?? null,
                ]);

                return redirect()->route('payments.fakepay', $payment->id);
            } else {
                $order->Status = 'Čeka plaćanje pouzećem';
                $order->save();

                DB::commit();

                $email = optional($user)->email;
                if ($email) {
                    Mail::to($email)->send(new OrderReceiptMail($order));
                }

                $this->clearPcConfiguration($user->id);

                Log::info('Checkout completed (COD), redirecting to orders.show', [
                    'user'        => $user->id ?? null,
                    'Narudzba_ID' => $order->Narudzba_ID ?? null,
                ]);

                return redirect()
                    ->route('orders.show', $order->Narudzba_ID)
                    ->with('success', 'Narudžba je zaprimljena. Plaćanje pri pouzeću.');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout failed', ['exception' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Došlo je do pogreške: ' . $e->getMessage());
        }
    }

    protected function clearPcConfiguration(int $userId): void
    {
        PcConfiguration::where('user_id', $userId)
            ->whereNull('naziv')
            ->delete();

        session()->forget('pc_configuration_id');
    }
}
