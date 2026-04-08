@extends('layouts.app')

@section('title', 'Detalji narudžbe — TechShop')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold text-center text-primary mb-5">
        <i class="bi bi-receipt me-2"></i> Detalji narudžbe #{{ $order->Narudzba_ID }}
    </h2>

    <div class="card shadow-sm rounded-4 border-0">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Status:</strong> <span class="badge bg-info">{{ $order->Status }}</span></p>
                    <p><strong>Adresa dostave:</strong> {{ $order->Adresa_dostave ?? '-' }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Način plaćanja:</strong> {{ optional($order->nacinPlacanja)->Opis ?? 'N/A' }}</p>
                    <p><strong>Datum:</strong> {{ \Carbon\Carbon::parse($order->Datum_narudzbe)->format('d.m.Y H:i') }}</p>
                    
                    
                    @if($order->promo_code)
                        <p><strong>Korišten promo kod:</strong> <span class="badge bg-success">{{ $order->promo_code }}</span></p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive shadow-sm rounded-4 bg-white p-3 mt-4">
        <table class="table align-middle">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Proizvod</th>
                    <th class="text-center">Količina</th>
                    <th class="text-end">Cijena</th>
                </tr>
            </thead>
            <tbody>
                @php $subtotal = 0; @endphp
                @foreach($order->detalji as $detail)
                    @php 
                        $itemTotal = ($detail->proizvod->Cijena ?? 0) * $detail->Kolicina;
                        $subtotal += $itemTotal;
                    @endphp
                    <tr>
                        <td>{{ $detail->proizvod->Naziv ?? 'Proizvod obrisan' }}</td>
                        <td class="text-center">{{ $detail->Kolicina }}</td>
                        <td class="text-end">{{ number_format($itemTotal, 2) }} €</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="text-end mt-4">
        
        @if($subtotal > $order->Ukupni_iznos)
            <h6 class="text-muted">Međuzbroj: {{ number_format($subtotal, 2) }} €</h6>
            <h6 class="text-danger">Popust: -{{ number_format($subtotal - $order->Ukupni_iznos, 2) }} €</h6>
        @endif
        
        <h4 class="fw-bold">Plaćeno: <span class="text-primary">{{ number_format($order->Ukupni_iznos, 2) }} €</span></h4>
    </div>

    <div class="text-center mt-5">
        <a href="{{ route('index.index') }}" class="btn btn-lg btn-primary rounded-pill px-5">
            <i class="bi bi-cart-fill me-2"></i> Nastavite s kupovinom
        </a>
    </div>
</div>
@endsection