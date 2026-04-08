@extends('layouts.app')

@section('title', 'Završi kupnju — TechShop')

@section('content')
<div class="container py-5">
    <h2 class="fw-bold text-center text-primary mb-5">
        <i class="bi bi-credit-card me-2"></i> Završetak kupnje
    </h2>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST" class="shadow-lg rounded-4 bg-white p-4">
        @csrf

        <h5 class="fw-bold mb-3">Adresa dostave</h5>

        @if($addresses->isEmpty())
            <p class="text-muted">Nema spremljenih adresa. <a href="{{ route('profile.edit') }}">Dodaj adresu</a>.</p>
            <input type="text" name="adresa_dostave" class="form-control rounded-pill mb-4" placeholder="Unesite adresu ručno" required>
        @else
            <select name="adresa_dostave" class="form-select rounded-pill mb-4" required>
                @foreach($addresses as $address)
                    <option value="{{ $address->adresa }}, {{ $address->grad }}, {{ $address->drzava }}">
                        {{ $address->adresa }}, {{ $address->grad }} ({{ $address->drzava }})
                    </option>
                @endforeach
            </select>
        @endif

        <div class="mb-4">
            <h5 class="fw-bold mb-3">Način plaćanja</h5>
            <div class="row">
                @foreach($paymentMethods as $method)
                    <div class="col-md-4 mb-3">
                        <div class="form-check border rounded-4 p-3 shadow-sm h-100 d-flex align-items-center gap-2 hover-card">
                            <input class="form-check-input mt-0" type="radio" name="nacin_placanja_id"
                                   id="placanje_{{ $method->NacinPlacanja_ID }}" 
                                   value="{{ $method->NacinPlacanja_ID }}" required>
                            <label class="form-check-label fw-semibold flex-grow-1 d-flex align-items-center gap-2"
                                   for="placanje_{{ $method->NacinPlacanja_ID }}">
                                @switch($method->Opis)
                                    @case('Revolut Pay')
                                        <img src="{{ asset('uploads/icons/revoult.webp') }}" alt="Revolut" style="height:20px;">
                                        @break
                                    @case('KeksPay')
                                        <img src="{{ asset('uploads/icons/kekspay.webp') }}" alt="KeksPay" style="height:20px;">
                                        @break
                                    @case('Skrill')
                                        <img src="{{ asset('uploads/icons/skrill.webp') }}" alt="Skrill" style="height:20px;">
                                        @break
                                    @case('PayPal')
                                        <img src="{{ asset('uploads/icons/paypal.webp') }}" alt="PayPal" style="height:20px;">
                                        @break
                                    @case('Kartično plaćanje')
                                        <i class="bi bi-credit-card text-primary fs-5"></i>
                                        @break
                                    @case('Plaćanje pouzećem')
                                        <i class="bi bi-cash-stack text-success fs-5"></i>
                                        @break
                                    @case('Google Pay')
                                        <img src="{{ asset('uploads/icons/googlepay.webp') }}" alt="Google Pay" style="height:20px;">
                                        @break
                                    @case('Apple Pay')
                                        <img src="{{ asset('uploads/icons/applepay.webp') }}" alt="Apple Pay" style="height:20px;">
                                        @break
                                    @case('Bankovni prijenos')
                                        <i class="bi bi-bank fs-5 text-primary"></i>
                                        @break
                                    @default
                                        <i class="bi bi-wallet2 text-secondary fs-5"></i>
                                @endswitch
                                {{ $method->Opis }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <h5 class="fw-bold mb-3 mt-4">Pregled proizvoda</h5>
        <div class="table-responsive mb-4">
            <table class="table align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Proizvod</th>
                        <th class="text-center">Količina</th>
                        <th class="text-end">Cijena</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        @php
                            $product = $item->proizvod ?? (object) $item['product'];
                            $qty = $item->kolicina ?? $item['quantity'];
                            $price = $product->Cijena ?? $item['price'];
                        @endphp
                        <tr>
                            <td><a href="{{ route('proizvod.show', $product->Proizvod_ID ?? $item->proizvod_id) }}" class="text-dark text-decoration-none fw-semibold hover-link">{{ $product->Naziv }}</a></td>
                            <td class="text-center">{{ $qty }}</td>
                            <td class="text-end">{{ number_format($price * $qty, 2) }} €</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <hr class="my-4">

        <div class="row align-items-center">
            <div class="col-md-5 mb-4 mb-md-0">
                <label for="promo_code_input" class="fw-bold mb-2">Imate li promo kod?</label>
                <div class="input-group">
                    <input type="text" id="promo_code_input"
                           class="form-control rounded-start-pill border-primary"
                           placeholder="Unesite kod"
                           @if($savedPromoCode) value="{{ $savedPromoCode }}" readonly @endif>
                    @if($savedPromoCode)
                        <button class="btn btn-outline-danger rounded-end-pill px-4" type="button" id="remove_promo_btn">
                            Ukloni
                        </button>
                    @else
                        <button class="btn btn-outline-primary rounded-end-pill px-4" type="button" id="apply_promo_btn">
                            Primijeni
                        </button>
                    @endif
                </div>
                <small id="promo_message" class="d-block mt-2 @if($savedPromoCode) text-success fw-bold @endif">
                    @if($savedPromoCode) Kod je uspješno primijenjen! @endif
                </small>
            </div>

            <div class="col-md-7 text-md-end">
                <div id="discount_row" class="mb-2 @if(!$savedPromoCode) d-none @endif">
                    <span class="text-muted">Popust: </span>
                    <span id="display_discount" class="text-danger fw-bold">-{{ number_format($savedPromoDiscount, 2) }} €</span>
                </div>
                <p class="mb-2">Dostava:
                    @if($freeShipping)
                        <strong class="text-success">Besplatna <i class="bi bi-check-circle-fill"></i></strong>
                    @else
                        <strong>{{ number_format($delivery, 2) }} €</strong>
                    @endif
                </p>
                @if(session('pc_sastavljanje'))
                <p class="mb-2">
                    <i class="bi bi-tools me-1"></i> Sastavljanje računala:
                    <strong class="text-success">Besplatno <i class="bi bi-check-circle-fill"></i></strong>
                </p>
                @endif

                <h4 class="fw-bold mb-3">
                    Ukupno za platiti: <span id="display_total" class="text-primary">{{ number_format($grandTotal, 2) }} €</span>
                </h4>

                <input type="hidden" name="promo_code" id="hidden_promo_code" value="{{ $savedPromoCode ?? '' }}">
                
                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold me-2">
                    <i class="bi bi-arrow-left me-1"></i> Povratak na košaricu
                </a>
                <button type="submit" id="checkout-submit-btn" class="btn btn-success rounded-pill px-5 py-2 fw-semibold">
                    <i class="bi bi-check-circle me-1"></i> Potvrdi narudžbu
                </button>
            </div>
        </div>
    </form>
</div>

<style>
    .hover-card {
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .hover-card:hover {
        background-color: #f0f7ff;
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(13,110,253,0.1);
    }
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    .form-check-input:checked + .form-check-label {
        color: #0d6efd;
    }
    .hover-link:hover { color: #0d6efd !important; text-decoration: underline !important; }
</style>

<script>
@if($savedPromoCode)
document.getElementById('remove_promo_btn').addEventListener('click', function() {
    fetch("{{ route('promo.remove') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
});
@else
document.getElementById('apply_promo_btn').addEventListener('click', function() {
    const code = document.getElementById('promo_code_input').value;
    const messageBox = document.getElementById('promo_message');

    if (!code) {
        messageBox.innerText = "Molimo unesite kod.";
        messageBox.className = "text-danger";
        return;
    }

    this.disabled = true;
    this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>';

    fetch("{{ route('promo.apply') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            promo_code: code,
            total_amount: {{ $total }}
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            messageBox.innerText = data.message;
            messageBox.className = "text-danger";
            this.disabled = false;
            this.innerHTML = 'Primijeni';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        messageBox.innerText = "Pogreška pri komunikaciji sa serverom.";
        this.disabled = false;
        this.innerHTML = 'Primijeni';
    });
});
@endif

document.querySelector('form[action="{{ route('checkout.store') }}"]')?.addEventListener('submit', function() {
    const btn = document.getElementById('checkout-submit-btn');
    if (btn.disabled) { event.preventDefault(); return; }
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Obrada...';
});
</script>
@endsection