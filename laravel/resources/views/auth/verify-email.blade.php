<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5 text-center">
                    <h3 class="text-primary fw-bold mb-3">
                        <i class="bi bi-envelope-check me-2"></i> Potvrdite svoju email adresu
                    </h3>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success mt-3">
                            Nova poveznica za verifikaciju poslana je na vašu email adresu.
                        </div>
                    @endif

                    <p class="text-muted my-4">
                        Prije nego što nastavite, provjerite svoj email sandučić za poveznicu za potvrdu.
                        Ako niste primili email, možete ponovno poslati poveznicu.
                    </p>

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary rounded-pill w-100 py-2 fw-semibold">
                            Pošalji poveznicu ponovo
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary rounded-pill w-100 py-2 fw-semibold">
                            Odjava
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
