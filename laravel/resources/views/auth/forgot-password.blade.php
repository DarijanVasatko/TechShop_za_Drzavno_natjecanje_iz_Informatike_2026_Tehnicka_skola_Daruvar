<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 text-primary fw-bold">
                        <i class="bi bi-envelope-at me-2"></i> Zaboravljena lozinka
                    </h3>

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <p class="text-muted mb-4">
                        Unesite svoju email adresu i poslat ćemo vam poveznicu za ponovno postavljanje lozinke.
                    </p>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email adresa</label>
                            <input id="email" class="form-control rounded-pill" type="email" name="email"
                                   value="{{ old('email') }}" required autofocus>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
                            Pošalji poveznicu za reset lozinke
                        </button>

                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-decoration-none text-primary">
                                <i class="bi bi-arrow-left"></i> Povratak na prijavu
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
