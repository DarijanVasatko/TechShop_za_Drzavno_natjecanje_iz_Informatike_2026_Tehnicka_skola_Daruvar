@extends('layouts.app')

@section('title', 'TechShop – Plaćanje karticom (test)')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">

            <div class="mb-4 text-center">
                <h3 class="fw-bold">Plaćanje karticom</h3>
                <p class="text-muted mb-1">Simulirano WSPay test plaćanje</p>
                <small class="text-muted">
                    Narudžba <strong>#{{ $payment->narudzba->Narudzba_ID }}</strong> ·
                    Iznos: <strong>{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</strong>
                </small>
            </div>

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">

                    <form id="fakepayForm"
                          method="POST"
                          action="{{ route('payments.fakepay.process', $payment->id) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ime i prezime na kartici</label>
                            <input type="text"
                                   name="card_holder"
                                   class="form-control @error('card_holder') is-invalid @enderror"
                                   placeholder="npr. IVAN HORVAT"
                                   value="{{ old('card_holder', strtoupper(auth()->user()->ime . ' ' . auth()->user()->prezime)) }}">
                            @error('card_holder')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Broj kartice</label>
                            <input type="text"
                                   name="card_number"
                                   class="form-control @error('card_number') is-invalid @enderror"
                                   placeholder="1111 2222 3333 4444"
                                   value="{{ old('card_number') }}">
                            <div class="form-text">
                                Test broj: <code>4111 1111 1111 1111</code>
                            </div>
                            @error('card_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold">Datum isteka</label>
                                <div class="d-flex gap-2">
                                    <input type="text"
                                           name="expiry_month"
                                           class="form-control @error('expiry_month') is-invalid @enderror"
                                           placeholder="MM"
                                           value="{{ old('expiry_month') }}">
                                    <input type="text"
                                           name="expiry_year"
                                           class="form-control @error('expiry_year') is-invalid @enderror"
                                           placeholder="GG"
                                           value="{{ old('expiry_year') }}">
                                </div>
                                @error('expiry_month')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                @error('expiry_year')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-6">
                                <label class="form-label fw-semibold">CVV / CVC</label>
                                <input type="password"
                                       name="cvv"
                                       class="form-control @error('cvv') is-invalid @enderror"
                                       placeholder="***">
                                @error('cvv')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button id="payBtn"
                                type="submit"
                                class="btn btn-primary w-100 py-2 fw-semibold">
                            Plati {{ number_format($payment->amount, 2) }} {{ $payment->currency }}
                        </button>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                Ovo je test okruženje – sredstva se stvarno ne terete.
                            </small>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('fakepayForm');
    const btn  = document.getElementById('payBtn');

    let submitted = false;

    if (!form || !btn) return;

    form.addEventListener('submit', function () {
        if (submitted) return;

        submitted = true;

        btn.disabled = true;
        btn.style.pointerEvents = 'none';
        btn.innerText = 'Obrada...';
    });
});
</script>

@endsection
