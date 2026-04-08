<?php

namespace App\Mail;

use App\Models\Narudzba;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public Narudzba $order;

    public function __construct(Narudzba $order)
    {
        $this->order = $order->load(['detalji.proizvod', 'user', 'nacinPlacanja']);
    }

    public function build()
    {
        $logoPath = public_path('l_ts_polozeni_2_b.png');
        $logoData = base64_encode(file_get_contents($logoPath));
        $logoBase64 = 'data:image/png;base64,' . $logoData;

        $subtotal = $this->order->detalji->sum(fn($d) => $d->kolicina * $d->cijena);
        $delivery = $subtotal >= 50 ? 0 : 5.00;

        $viewData = [
            'order'    => $this->order,
            'logo'     => $logoBase64,
            'delivery' => $delivery,
        ];

        $pdf = Pdf::loadView('emails.order-receipt', $viewData);

        return $this
            ->subject('Vaš račun za narudžbu #' . $this->order->Narudzba_ID)
            ->view('emails.order-receipt')
            ->with($viewData)
            ->attachData($pdf->output(), 'Racun_TechShop_#' . $this->order->Narudzba_ID . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}