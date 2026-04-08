<!doctype html>
<html lang="hr">

<head>
    <meta charset="utf-8">
    <title>Račun za narudžbu #{{ $order->Narudzba_ID }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6; font-family: 'DejaVu Sans', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
        <tr>
            <td align="center" style="padding:32px 12px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:640px; border-collapse:collapse; background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 10px 25px rgba(0,0,0,0.1);">

                    <tr>
                        <td style="background: linear-gradient(135deg, #6366f1, #8b5cf6); padding:24px; color:#ffffff;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="font-size:24px; font-weight:800; letter-spacing:-0.5px;">TechShop</td>
                                    
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 12px 0; font-size:18px; font-weight:700; color:#111827;">
                                Hvala na kupnji, {{ $order->user->ime }}!
                            </p>
                            <p style="margin:0 0 24px 0; font-size:14px; color:#4b5563; line-height:1.6;">
                                U nastavku je sažetak vaše narudžbe. Ovaj dokument služi kao potvrda i službeni račun za
                                vašu evidenciju.
                            </p>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="margin-bottom:24px;">
                                <tr>
                                    <td style="width:50%; vertical-align:top; padding-right:10px;">
                                        <div
                                            style="background-color:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px;">
                                            <div
                                                style="font-size:11px; text-transform:uppercase; color:#94a3b8; font-weight:700; margin-bottom:8px;">
                                                Podaci kupca</div>
                                            <div style="font-size:13px; color:#1e293b; line-height:1.5;">
                                                <strong>{{ $order->user->ime }} {{ $order->user->prezime }}</strong><br>
                                                {{ $order->Adresa_dostave ?? $order->user->adresa }}<br>
                                                {{ $order->user->postanski_broj ?? '' }}
                                                {{ $order->user->grad ?? '' }}<br>
                                                @if(!empty($order->user->telefon))
                                                    Tel: {{ $order->user->telefon }}
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td style="width:50%; vertical-align:top; padding-left:10px;">
                                        <div
                                            style="background-color:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px;">
                                            <div
                                                style="font-size:11px; text-transform:uppercase; color:#94a3b8; font-weight:700; margin-bottom:8px;">
                                                Podaci o računu</div>
                                            <div style="font-size:13px; color:#1e293b; line-height:1.5;">
                                                <strong>TechShop d.o.o.</strong><br>
                                                Ivana Gundulića 14, Daruvar<br>
                                                OIB: 12345678901<br>
                                                <span style="color:#64748b;">Datum:</span>
                                                {{ $order->created_at->format('d.m.Y') }}<br>
                                                <span style="color:#64748b;">Plaćanje:</span>
                                                {{ $order->nacinPlacanja->naziv ?? 'Kartica' }}
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            @php $subtotal = 0; @endphp

                            <div style="border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;">
                                <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                                    <thead>
                                        <tr style="background-color:#f8fafc;">
                                            <th align="left"
                                                style="padding:12px 16px; font-size:11px; color:#64748b; text-transform:uppercase; border-bottom:1px solid #e2e8f0;">
                                                Proizvod</th>
                                            <th align="center"
                                                style="padding:12px 8px; font-size:11px; color:#64748b; text-transform:uppercase; border-bottom:1px solid #e2e8f0;">
                                                Kol.</th>
                                            <th align="right"
                                                style="padding:12px 16px; font-size:11px; color:#64748b; text-transform:uppercase; border-bottom:1px solid #e2e8f0;">
                                                Iznos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->detalji as $stavka)
                                            @php
                                                $lineTotal = $stavka->kolicina * $stavka->cijena;
                                                $subtotal += $lineTotal;
                                            @endphp
                                            <tr>
                                                <td style="padding:16px; border-bottom:1px solid #f1f5f9;">
                                                    <div style="font-size:14px; font-weight:600; color:#1e293b;">
                                                        {{ $stavka->proizvod->Naziv }}</div>
                                                    <div style="font-size:11px; color:#94a3b8;">Šifra:
                                                        {{ $stavka->proizvod->sifra ?? '-' }}</div>
                                                </td>
                                                <td align="center"
                                                    style="padding:16px; font-size:14px; color:#1e293b; border-bottom:1px solid #f1f5f9;">
                                                    {{ number_format($stavka->kolicina, 0) }}
                                                </td>
                                                <td align="right"
                                                    style="padding:16px; font-size:14px; font-weight:600; color:#1e293b; border-bottom:1px solid #f1f5f9;">
                                                    {{ number_format($lineTotal, 2) }} €
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="margin-top:20px;">
                                <tr>
                                    <td style="width:60%;"></td>
                                    <td style="width:40%;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="padding:4px 0; font-size:13px; color:#64748b;">Međuiznos:
                                                </td>
                                                <td align="right" style="padding:4px 0; font-size:13px; color:#1e293b;">
                                                    {{ number_format($subtotal, 2) }} €</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:4px 0; font-size:13px; color:#64748b;">Dostava:</td>
                                                <td align="right" style="padding:4px 0; font-size:13px; color:#1e293b;">
                                                    @if($delivery == 0)
                                                        Besplatna
                                                    @else
                                                        {{ number_format($delivery, 2) }} €
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td
                                                    style="padding:12px 0 0 0; font-size:15px; font-weight:700; color:#1e293b; border-top:2px solid #e2e8f0;">
                                                    Ukupno:</td>
                                                <td align="right"
                                                    style="padding:12px 0 0 0; font-size:20px; font-weight:800; color:#6366f1; border-top:2px solid #e2e8f0;">
                                                    {{ number_format($order->Ukupni_iznos, 2) }} €
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p
                                style="margin:32px 0 0 0; font-size:12px; color:#94a3b8; line-height:1.6; text-align:center;">
                                Status narudžbe: <strong>{{ $order->Status }}</strong><br>
                                Račun je kompjuterski izdan i vrijedi bez pečata i potpisa.<br>
                                &copy; {{ date('Y') }} TechShop d.o.o. Sva prava pridržana.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>