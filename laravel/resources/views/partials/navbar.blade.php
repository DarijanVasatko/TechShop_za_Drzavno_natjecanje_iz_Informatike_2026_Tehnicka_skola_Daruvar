@php
$ikoneKategorija = [
    'Laptop' => 'bi-laptop',
    'Računalo' => 'bi-pc-display',
    'Komponente' => 'bi-cpu',
    'Pohrana' => 'bi-device-hdd',
    'Oprena za računala' => 'bi-keyboard',
    'Mobiteli' => 'bi-phone',
    'Tableti' => 'bi-tablet',
    'TV' => 'bi-tv',
    'Audio i Video' => 'bi-speaker',
    'Gaming' => 'bi-controller',
    'Mreža' => 'bi-router',
];
@endphp
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
    <div class="container-fluid py-2">

        <a class="navbar-brand fw-bold text-white d-flex align-items-center me-4"
           href="{{ route('index.index') }}">
            <img src="{{ asset('logo_na_naslovnoj.svg') }}" alt="TechShop" width="28" height="28" class="me-2"> TechShop
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav align-items-lg-center me-3">

                <li class="nav-item">
                    <a class="nav-link text-white" href="{{ route('index.index') }}">
                        <i class="bi bi-house-door me-1"></i> Početna
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-white fw-semibold" href="{{ route('pc-builder.index') }}">
                        <i class="bi bi-pc-display-horizontal me-1"></i> Konfiguriraj PC
                    </a>
                </li>

                <li class="nav-item dropdown position-static kategorije-dropdown">
                    <a href="{{ route('proizvodi.index') }}"
                       class="nav-link dropdown-toggle text-white fw-semibold"
                       id="kategorijeDropdown"
                       role="button"
                       aria-expanded="false">
                        <i class="bi bi-grid-3x3-gap me-1"></i> Kategorije
                    </a>

                    <div class="dropdown-menu w-100 border-0 shadow-lg mt-0 p-4 mega-dropdown"
                         aria-labelledby="kategorijeDropdown">
                        <div class="container">
                            <div class="row g-4">
                                @foreach($kategorije as $kat)
                                    <div class="col-6 col-md-4 col-lg-3">
                                        <a href="{{ url('/kategorija/'.$kat->id_kategorija) }}"
                                           class="text-decoration-none d-block p-3 rounded-3 hover-card">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="icon-wrapper me-2">
                                                    <i class="bi {{ $ikoneKategorija[$kat->ImeKategorija] ?? 'bi-grid' }}"></i>
                                                </div>
                                                <h6 class="mb-0 fw-semibold">
                                                    {{ $kat->ImeKategorija }}
                                                </h6>
                                            </div>
                                            <small class="text-muted">
                                                Istraži proizvode
                                            </small>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </li>
            </ul>

            <form class="d-flex flex-grow-1 mx-lg-4"
                  action="{{ route('proizvodi.index') }}"
                  method="GET"
                  style="max-width: 700px;">
                <div class="input-group w-100 position-relative">
                    <input type="text"
                           name="search"
                           class="form-control rounded-pill ps-4"
                           placeholder="Pretraži proizvode..."
                           value="{{ request('search') }}">
                    <button class="btn btn-light rounded-pill position-absolute end-0 me-2">
                        <i class="bi bi-search text-primary"></i>
                    </button>
                </div>
            </form>

            <ul class="navbar-nav align-items-center ms-auto gap-3">

                <li class="nav-item position-relative">
                    <a href="{{ route('cart.index') }}" class="nav-link text-white">
                        <i class="bi bi-cart3 fs-5"></i>
                        <span id="cart-count"
                              class="badge bg-danger position-absolute top-0 start-100 translate-middle
                              {{ $cartCount > 0 ? '' : 'd-none' }}">
                            {{ $cartCount }}
                        </span>
                    </a>
                </li>

                @guest
                    <li class="nav-item">
                        <a href="{{ route('login') }}"
                           class="btn btn-outline-light rounded-pill px-3">
                            Prijava
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}"
                           class="btn rounded-pill px-3 text-white fw-semibold"
                           style="background:linear-gradient(90deg,#ff7e5f,#fd3a69)">
                            Registracija
                        </a>
                    </li>
                @else
                    <li class="nav-item position-relative" id="profileDropdown">
    <button type="button"
            class="nav-link text-white fw-semibold btn btn-link p-0"
            id="profileToggle">
        <i class="bi bi-person-circle me-1"></i>
        Bok, {{ Auth::user()->ime }}
    </button>

    <ul class="dropdown-menu dropdown-menu-end shadow-sm mt-2"
        id="profileMenu"
        style="display:none;">
        <li>
            <a class="dropdown-item" href="{{ route('profile.edit') }}">
                <i class="bi bi-person me-2"></i>Profil
            </a>
        </li>
        <li>
            <a class="dropdown-item" href="{{ route('pc-builder.saved') }}">
                <i class="bi bi-pc-display-horizontal me-2"></i>Moje konfiguracije
            </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i>Odjava
                </button>
            </form>
        </li>
    </ul>
</li>


                @endguest

            </ul>
        </div>
    </div>
</nav>
