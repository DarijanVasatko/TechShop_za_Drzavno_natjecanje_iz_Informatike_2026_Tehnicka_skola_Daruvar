
<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Admin — TechShop')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

<script async src="https://www.googletagmanager.com/gtag/js?id=G-EVQERXLM84"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-EVQERXLM84');
</script>
</head>

<body class="bg-light">

    <nav class="navbar navbar-dark shadow-sm" style="background: var(--ts-gradient-dark);">
        <div class="container d-flex justify-content-between">
            
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('admin.dashboard') }}">
                <img src="{{ asset('logo_na_naslovnoj.svg') }}" alt="TechShop" width="28" height="28" class="me-2"> TechShop Admin
            </a>

            <ul class="navbar-nav flex-row">

                <li class="nav-item me-3">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">
                        <i class="bi bi-grid-fill me-1"></i> Dashboard
                    </a>
                </li>

                <li class="nav-item me-3">
                    <a href="{{ route('admin.products.index') }}" class="nav-link text-white">
                        <i class="bi bi-box-seam me-1"></i> Proizvodi
                    </a>
                </li>

                <li class="nav-item me-3">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link text-white">
                        <i class="bi bi-receipt me-1"></i> Narudžbe
                    </a>
                </li>

                <li class="nav-item me-3">
                    <a href="{{ route('admin.users.index') }}" class="nav-link text-white">
                        <i class="bi bi-people me-1"></i> Korisnici
                    </a>
                </li>

                <li class="nav-item me-3">
                    <a href="{{ route('admin.products.index', ['konfigurator' => 1]) }}" class="nav-link text-white">
                        <i class="bi bi-cpu me-1"></i> Konfigurator
                    </a>
                </li>

                <li class="nav-item me-3">
                    <a href="{{ route('admin.promo-kodovi.index') }}" class="nav-link text-white">
                        <i class="bi bi-ticket-perforated me-1"></i> Promo-kodovi
                    </a>
                </li>

                <li class="nav-item me-3">
                    <a href="{{ route('admin.recenzije.index') }}" class="nav-link text-white">
                        <i class="bi bi-chat-left-text me-1"></i> Recenzije
                    </a>
                </li>

                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-sm btn-danger">
                            <i class="bi bi-box-arrow-right me-1"></i> Odjava
                        </button>
                    </form>
                </li>
            </ul>

        </div>
    </nav>

    <main class="container py-4">
        @yield('content')
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>
