<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 text-primary fw-bold">
                        <i class="bi bi-shield-lock me-2"></i> Potvrdite lozinku
                    </h3>

                    <p class="text-muted mb-4 text-center">
                        Ova je sekcija zaštićena. Molimo vas da potvrdite lozinku prije nastavka.
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="password" class="form-label">Lozinka</label>
                            <input id="password" class="form-control rounded-pill" type="password"
                                   name="password" required autocomplete="current-password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
                            Potvrdi lozinku
                        </button>

                    </form>

                    <form action="{{ route('logout') }}" method="POST" class="text-center mt-3">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none text-muted p-0">
                            <i class="bi bi-box-arrow-left"></i> Odjavi se
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
