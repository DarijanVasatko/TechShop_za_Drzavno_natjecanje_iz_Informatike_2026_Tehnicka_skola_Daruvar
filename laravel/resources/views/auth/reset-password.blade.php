<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 text-primary fw-bold">
                        <i class="bi bi-key me-2"></i> Postavi novu lozinku
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

                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="mb-3">
                            <label for="email" class="form-label">Email adresa</label>
                            <input id="email" class="form-control rounded-pill" type="email" name="email"
                                   value="{{ old('email', $request->email) }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Nova lozinka</label>
                            <input id="password" class="form-control rounded-pill" type="password" name="password"
                                   required autocomplete="new-password">
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Potvrdi lozinku</label>
                            <input id="password_confirmation" class="form-control rounded-pill" type="password"
                                   name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2 fw-semibold">
                            Spremi novu lozinku
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
