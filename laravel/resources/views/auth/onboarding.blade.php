@extends('layouts.app_stepform')

@section('title', 'Dovršite profil — TechShop')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg rounded-4 mx-auto" style="max-width: 700px;">
        <div class="card-body p-5">
            <h3 class="fw-bold text-center text-primary mb-4">
                <i class="bi bi-person-lines-fill me-2"></i> Dovršite svoj profil
            </h3>
            <p class="text-muted text-center mb-4">Molimo vas da unesete osnovne podatke za dostavu i kontakt.</p>

            <form method="POST" action="{{ route('onboarding.store') }}">
                @csrf
                @if($errors->any())
                    <div class="alert alert-danger rounded-3 mb-3">
                        @foreach($errors->all() as $error)
                            <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ime <span class="text-danger">*</span></label>
                        <input type="text" name="ime" value="{{ old('ime', Auth::user()->ime) }}"
                               class="form-control rounded-pill @error('ime') is-invalid @enderror" required>
                        <small class="text-danger d-none js-error" data-field="ime">Ime je obavezno.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Prezime <span class="text-danger">*</span></label>
                        <input type="text" name="prezime" value="{{ old('prezime', Auth::user()->prezime) }}"
                               class="form-control rounded-pill @error('prezime') is-invalid @enderror" required>
                        <small class="text-danger d-none js-error" data-field="prezime">Prezime je obavezno.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Telefon</label>
                        <input type="tel" name="telefon" value="{{ old('telefon', Auth::user()->telefon) }}"
                               class="form-control rounded-pill @error('telefon') is-invalid @enderror"
                               pattern="[0-9+\s\-]{6,20}"
                               title="Samo brojevi, +, razmaci i crtice (6-20 znakova)">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Adresa <span class="text-danger">*</span></label>
                        <input type="text" name="adresa" value="{{ old('adresa') }}"
                               class="form-control rounded-pill @error('adresa') is-invalid @enderror" required>
                        <small class="text-danger d-none js-error" data-field="adresa">Adresa je obavezna.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Grad <span class="text-danger">*</span></label>
                        <input type="text" name="grad" id="grad" class="form-control rounded-pill @error('grad') is-invalid @enderror"
                               placeholder="Upiši grad..." autocomplete="off" required>
                        <small class="text-danger d-none js-error" data-field="grad">Grad je obavezan.</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Poštanski broj <span class="text-danger">*</span></label>
                        <input type="text" name="postanski_broj" id="postanski_broj"
                               class="form-control rounded-pill @error('postanski_broj') is-invalid @enderror" required>
                        <small class="text-danger d-none js-error" data-field="postanski_broj">Poštanski broj je obavezan.</small>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Država <span class="text-danger">*</span></label>
                        <input list="country-list" name="drzava" id="drzava"
                               class="form-control rounded-pill @error('drzava') is-invalid @enderror"
                               placeholder="Upiši ili odaberi..." required>
                        <small class="text-danger d-none js-error" data-field="drzava">Država je obavezna.</small>

                        <datalist id="country-list">
                            @foreach($countries as $country)
                                <option value="{{ $country->name }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-semibold">
                        <i class="bi bi-check-circle me-1"></i> Spremi i nastavi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form[action="{{ route('onboarding.store') }}"]');
    const requiredFields = ['ime', 'prezime', 'adresa', 'grad', 'postanski_broj', 'drzava'];

    form.addEventListener('submit', function(e) {
        let hasError = false;

        document.querySelectorAll('.js-error').forEach(el => el.classList.add('d-none'));
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        requiredFields.forEach(field => {
            const input = form.querySelector(`[name="${field}"]`);
            const error = form.querySelector(`.js-error[data-field="${field}"]`);
            if (input && !input.value.trim()) {
                input.classList.add('is-invalid');
                if (error) error.classList.remove('d-none');
                hasError = true;
            }
        });

        if (hasError) {
            e.preventDefault();
        }
    });

    const cityInput = document.getElementById('grad');
    const postalInput = document.getElementById('postanski_broj');

    const countryInput = document.getElementById('drzava');
    const getCountry = () => (countryInput?.value || 'HR').trim() || 'HR';

    if (!cityInput || !postalInput) {
        console.warn('Missing #grad or #postanski_broj input');
        return;
    }

    let t = null;

    async function lookupPostal(city, country) {
        const res = await fetch(`/post-codes/lookup?city=${encodeURIComponent(city)}&country=${encodeURIComponent(country)}`, {
            headers: { 'Accept': 'application/json' }
        });
        return await res.json();
    }

    cityInput.addEventListener('input', () => {
        clearTimeout(t);
        const city = cityInput.value.trim();
        const country = getCountry();

        if (city.length < 2) return;

        t = setTimeout(async () => {
            try {
                const data = await lookupPostal(city, country);
                if (data.postal_code) {
                    postalInput.value = data.postal_code;
                }
            } catch (e) {}
        }, 350);
    });

    cityInput.addEventListener('blur', async () => {
        const city = cityInput.value.trim();
        const country = getCountry();
        if (!city) return;

        try {
            const data = await lookupPostal(city, country);
            if (data.postal_code) {
                postalInput.value = data.postal_code;
            }
        } catch (e) {}
    });
});
</script>

@endpush
