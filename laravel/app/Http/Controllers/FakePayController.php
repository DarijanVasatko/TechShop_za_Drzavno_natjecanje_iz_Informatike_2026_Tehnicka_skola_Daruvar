<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\PcConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderReceiptMail;

class FakePayController extends Controller
{
    
    public function show(Payment $payment)
    {
        
        abort_unless($payment->narudzba->Kupac_ID === Auth::id(), 403);

        return view('payments.fakepay', compact('payment'));
    }

   
    public function process(Request $request, Payment $payment)
    {
        abort_unless($payment->narudzba->Kupac_ID === Auth::id(), 403);

    
        $data = $request->validate([
            'card_holder'   => 'required|string|max:255',
            'card_number'   => 'required|string|max:32',
            'expiry_month'  => 'required|string|max:2',
            'expiry_year'   => 'required|string|max:2',
            'cvv'           => 'required|string|max:4',
            'result'        => 'nullable|in:success,failed',
        ]);

      
        $card = preg_replace('/\s+/', '', $data['card_number']);

        if ($card === '4111111111111111') {
            $result = 'success';
        } else {
            $result = $data['result'] ?? 'success';
        }

        
        return redirect()->route('payments.fakepay.callback', [
            'payment' => $payment->id,
            'result'  => $result,
        ]);
    }

    
    public function callback(Request $request, Payment $payment)
    {
        abort_unless($payment->narudzba->Kupac_ID === Auth::id(), 403);

        $result = $request->input('result', 'success');
        $order  = $payment->narudzba;

        if ($result === 'success') {
            $payment->status = 'success';
            $payment->meta = [
                'fake_gateway_message' => 'Payment authorised (test).',
            ];
            $payment->save();


            $order->Status = 'Plaćeno';
            $order->save();

            $email = optional($order->user)->email;
            if ($email) {
                Mail::to($email)->send(new OrderReceiptMail($order));
            }

            $this->clearPcConfiguration(Auth::id());

            return redirect()
                ->route('orders.show', $order->Narudzba_ID)
                ->with('success', 'Plaćanje je uspješno provedeno (test). Račun je poslan na e-mail.');
        }


        $payment->status = 'failed';
        $payment->meta = [
            'fake_gateway_message' => 'Payment declined (test).',
        ];
        $payment->save();

        return redirect()
            ->route('orders.show', $order->Narudzba_ID)
            ->with('error', 'Plaćanje nije uspjelo (test). Pokušajte ponovno ili odaberite drugi način plaćanja.');
    }

   
    protected function clearPcConfiguration(int $userId): void
    {
        
        PcConfiguration::where('user_id', $userId)
            ->whereNull('naziv')
            ->delete();


        session()->forget('pc_configuration_id');
    }
}