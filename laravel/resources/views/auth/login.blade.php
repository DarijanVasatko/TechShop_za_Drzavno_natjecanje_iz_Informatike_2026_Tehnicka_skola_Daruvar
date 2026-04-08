<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 text-primary fw-bold">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Prijava u TechShop
                    </h3>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email adresa</label>
                            <input id="email" class="form-control rounded-pill" type="email" name="email"
                                   value="{{ old('email') }}" required autofocus autocomplete="username">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Lozinka</label>
                            <input id="password" class="form-control rounded-pill" type="password"
                                   name="password" required autocomplete="current-password">
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                <label class="form-check-label" for="remember_me">Zapamti me</label>
                            </div>

                            @if (Route::has('password.request'))
                                <a class="text-decoration-none text-primary small" href="{{ route('password.request') }}">
                                    Zaboravljena lozinka?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
                            Prijavi se
                        </button>

                        <div class="text-center mt-3">
                            <span class="text-muted">Nemaš račun?</span>
                            <a href="{{ route('register') }}" class="text-decoration-none fw-semibold text-primary">
                                Registriraj se
                            </a>
                        </div>
                    </form>
                    <div class="position-relative my-4">
    <hr class="text-muted">
    <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">ILI</span>
</div>

<div class="mb-3">
    <a href="{{ url('auth/google') }}" class="btn btn-outline-dark w-100 rounded-pill py-2 fw-semibold d-flex align-items-center justify-content-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-google me-2" viewBox="0 0 16 16">
            <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0c2.182 0 4.036.815 5.447 2.138L11.12 4.413c-.627-.602-1.708-1.311-3.12-1.311-2.424 0-4.398 2.01-4.398 4.488s1.974 4.488 4.398 4.488c2.813 0 3.857-2.028 4.021-3.07H8v-2.49h7.545z"/>
        </svg>
        Prijavi se putem Googlea
    </a>
</div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
