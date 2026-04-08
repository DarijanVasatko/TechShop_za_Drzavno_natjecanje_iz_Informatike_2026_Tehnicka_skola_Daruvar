<footer class="text-white pt-5 pb-4 mt-5" style="background: var(--ts-gradient-dark);">
    <div class="container">
        <div class="row g-4">

            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold mb-3 d-flex align-items-center">
                    <img src="{{ asset('logo_na_naslovnoj.svg') }}" alt="TechShop" width="28" height="28" class="me-2"> TechShop
                </h5>
                <p class="text-white-50 mb-3">
                    Vaša pouzdana destinacija za najnoviju tehnologiju. Kvalitetni proizvodi, povoljne cijene i brza
                    dostava.
                </p>
                <div class="d-flex gap-2">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                        <i class="bi bi-youtube"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle">
                        <i class="bi bi-linkedin"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3 text-uppercase">Navigacija</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ route('index.index') }}">Početna</a></li>
                    <li><a href="{{ route('proizvodi.index') }}">Proizvodi</a></li>
                    <li><a href="{{ route('pc-builder.index') }}">Konfiguriraj PC</a></li>
                    <li><a href="{{ route('cart.index') }}">Košarica</a></li>
                </ul>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="fw-bold mb-3 text-uppercase">Informacije</h6>
                <ul class="list-unstyled footer-links">
                    <li><a href="{{ route('o-nama') }}">O nama</a></li>
                    <li><a href="#" title="Dostupno uskoro">Uvjeti korištenja</a></li>
                    <li><a href="#" title="Dostupno uskoro">Politika privatnosti</a></li>
                    <li><a href="#" title="Dostupno uskoro">Dostava i povrat</a></li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-6">
                <h6 class="fw-bold mb-3 text-uppercase">Kontakt</h6>
                <ul class="list-unstyled text-white-50">
                    <li class="mb-2">
                        <a href="https://maps.google.com/?q=Tehni%C4%8Dka+%C5%A1kola+Daruvar" target="_blank" rel="noopener" class="text-white-50 text-decoration-none footer-contact-link">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>
                            Tehnička škola Daruvar
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="tel:+38512345678" class="text-white-50 text-decoration-none footer-contact-link">
                            <i class="bi bi-telephone me-2 text-primary"></i>
                            +385 1 234 5678
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="mailto:techshop.project.zavrsni.dv@gmail.com" class="text-white-50 text-decoration-none footer-contact-link">
                            <i class="bi bi-envelope me-2 text-primary"></i>
                            techshop.project.zavrsni.dv@gmail.com
                        </a>
                    </li>
                    <li>
                        <i class="bi bi-clock me-2 text-primary"></i>
                        Pon - Pet: 08:00 - 20:00
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-top border-secondary pt-4 mt-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    <span class="text-white-50 small">Prihvaćamo:</span>
                    <i class="bi bi-credit-card ms-2 fs-5"></i>
                    <i class="bi bi-paypal ms-2 fs-5"></i>
                    <i class="bi bi-cash-stack ms-2 fs-5"></i>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0 text-white-50 small">
                        &copy; {{ date('Y') }} TechShop. Sva prava pridržana.
                        <b>OVO JE RAD ZA NATJECANJE!</b>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<style>
    .footer-links li {
        margin-bottom: 0.5rem;
    }

    .footer-links a {
        color: rgba(255, 255, 255, 0.5);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .footer-links a:hover {
        color: #fff;
        padding-left: 5px;
    }

    .footer-contact-link:hover {
        color: #fff !important;
    }
</style>