<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <title>Admin Prijava — TechShop</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height:100vh;">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">
                    <h3 class="text-center mb-3">Admin Prijava</h3>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.post') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email"
                                class="form-control"
                                value="{{ old('email') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lozinka</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <button class="btn btn-dark w-100 rounded-pill">Prijava</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="/" class="text-muted small">← Povratak na početnu</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
