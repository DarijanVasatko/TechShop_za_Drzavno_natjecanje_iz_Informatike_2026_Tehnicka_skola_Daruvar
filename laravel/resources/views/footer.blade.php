<footer class="bg-dark text-light mt-auto">
    <div class="container py-4">
        <div class="row">

            <div class="col-md-4 mb-3">
                <h5 class="fw-bold text-uppercase">TechShop</h5>
                <p class="small text-muted mb-0">
                    Završni rad — eCommerce platforma izrađena u Laravelu.
                </p>
            </div>

            <div class="col-md-4 mb-3">
                <h6 class="fw-semibold">Navigacija</h6>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="footer-link">Početna</a></li>
                    <li><a href="{{ route('products.index') }}" class="footer-link">Proizvodi</a></li>
                    <li><a href="{{ route('cart.index') }}" class="footer-link">Košarica</a></li>
                    <li><a href="{{ route('profile.edit') }}" class="footer-link">Profil</a></li>
                </ul>
            </div>

            <div class="col-md-4 mb-3">
                <h6 class="fw-semibold">Informacije</h6>
                <p class="small mb-1">Autor: Darijan Vašatko</p>
                <p class="small mb-0">© {{ date('Y') }} TechShop</p>
            </div>

        </div>

        <hr class="border-secondary">

        <div class="text-center small text-muted">
            Izrađeno za završni rad · Laravel {{ app()->version() }}
        </div>
    </div>
</footer>


<style>
    .footer-link {
        color: #adb5bd;
        text-decoration: none;
    }

    .footer-link:hover {
        color: #ffffff;
        text-decoration: underline;
    }
</style>