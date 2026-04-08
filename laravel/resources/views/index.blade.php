@extends('layouts.app')

@section('title', 'TechShop - Najbolja tehnologija u Hrvatskoj')

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

@section('content')

<div class="modal fade" id="zavrsniRadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white rounded-top-4 py-3">
                <h5 class="modal-title fw-bold" id="staticBackdropLabel">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> VAŽNA OBAVIJEST
                </h5>
            </div>
            <div class="modal-body p-5 text-center">
                <div class="mb-4">
                    <i class="bi bi-mortarboard text-primary" style="font-size: 4rem;"></i>
                </div>
                <h2 class="fw-bold mb-3">Ovo je stranica za natjecanje</h2>
                <p class="lead text-muted">
                    Dobrodošli! Napominjemo da je ova web trgovina dio projekta za <strong>natjecanja</strong>. 
                    Sve funkcionalnosti, artikli i simulacije plaćanja služe isključivo u svrhu demonstracije.
                </p>
                
                <hr class="my-4">

                <div class="form-check d-inline-block text-start p-3 bg-light rounded-3 border">
                    <input class="form-check-input border-primary ms-0 me-2" type="checkbox" id="potvrdaCheck" style="cursor: pointer; width: 1.2em; height: 1.2em;">
                    <label class="form-check-label fw-medium text-dark cursor-pointer" for="potvrdaCheck" style="cursor: pointer;">
                        Potvrđujem da razumijem svrhu ove stranice.
                    </label>
                </div>
            </div>
            <div class="modal-footer border-0 pb-4 justify-content-center">
                <button type="button" id="btnZatvori" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold shadow-sm" disabled data-bs-dismiss="modal">
                    UĐI NA STRANICU
                </button>
            </div>
        </div>
    </div>
</div>

<section class="hero-section text-white text-center py-5 position-relative overflow-hidden">
    <div class="hero-overlay"></div>
    <div class="container py-5 position-relative">
        <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill shadow-sm">
            <i class="bi bi-lightning-fill me-1"></i> Besplatna dostava iznad 50€
        </span>
        <h1 class="display-3 fw-bold mb-3">Dobrodošli u <span class="text-gradient">TechShop</span></h1>
        <p class="lead mb-4 mx-auto" style="max-width: 600px;">
            Najbolja tehnologija po povoljnim cijenama. Istraži, usporedi i pronađi savršen uređaj za sebe.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="{{ route('proizvodi.index') }}" class="btn btn-primary btn-lg rounded-pill px-4 shadow">
                <i class="bi bi-shop me-2"></i> Pregledaj proizvode
            </a>
            <a href="{{ route('pc-builder.index') }}" class="btn btn-outline-light btn-lg rounded-pill px-4 border-2">
                <i class="bi bi-pc-display me-2"></i> Sastavi PC
            </a>
        </div>
    </div>
</section>

<section class="py-5 bg-white shadow-sm border-bottom">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-6 col-md-3">
                <div class="feature-box p-3">
                    <div class="feature-icon bg-primary-subtle text-primary rounded-circle mx-auto mb-3">
                        <i class="bi bi-truck fs-4"></i>
                    </div>
                    <h6 class="fw-bold">Brza dostava</h6>
                    <small class="text-muted">Dostava u 24-48h</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature-box p-3">
                    <div class="feature-icon bg-success-subtle text-success rounded-circle mx-auto mb-3">
                        <i class="bi bi-shield-check fs-4"></i>
                    </div>
                    <h6 class="fw-bold">Garancija</h6>
                    <small class="text-muted">2 godine na sve</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature-box p-3">
                    <div class="feature-icon bg-warning-subtle text-warning rounded-circle mx-auto mb-3">
                        <i class="bi bi-credit-card fs-4"></i>
                    </div>
                    <h6 class="fw-bold">Sigurno plaćanje</h6>
                    <small class="text-muted">Kartica, PayPal</small>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="feature-box p-3">
                    <div class="feature-icon bg-info-subtle text-info rounded-circle mx-auto mb-3">
                        <i class="bi bi-headset fs-4"></i>
                    </div>
                    <h6 class="fw-bold">Podrška</h6>
                    <small class="text-muted">Tu smo 24/7</small>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Popularne kategorije</h2>
            <p class="text-muted">Pronađi što tražiš u našim kategorijama</p>
        </div>
        <div class="row g-4">
            @foreach($kategorije->take(6) as $kat)
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card h-100 border-0 shadow-sm category-card text-center rounded-4 p-2">
                        <div class="card-body py-4">
                            <div class="category-icon mx-auto mb-3 shadow-sm" style="background: linear-gradient(135deg, #0d6efd, #6610f2);">
                                <i class="bi {{ $ikoneKategorija[$kat->ImeKategorija] ?? 'bi-grid' }} text-white fs-3"></i>
                            </div>
                            <h6 class="fw-semibold mb-0">{{ $kat->ImeKategorija }}</h6>
                            <a href="{{ url('/kategorija/'.$kat->id_kategorija) }}" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="featured-products" class="py-5">
    <div class="container">
        <h2 class="text-center fw-bold mb-5">Izdvojeni proizvodi</h2>
        <div id="product-row" class="d-flex gap-4 overflow-hidden pb-3" style="white-space: nowrap; scroll-behavior: smooth;">
            @foreach ($proizvodi as $product)
                <div class="card product-card flex-shrink-0 border-0 shadow-sm" style="width: 250px; border-radius: 1.25rem; overflow: hidden; background: #fff;">
                    <a href="{{ route('proizvod.show', $product->Proizvod_ID) }}" class="text-decoration-none">
                        <div class="position-relative" style="height: 200px; overflow: hidden; background: #f8f9fa;">
                            <img src="{{ $product->slika_url }}" alt="{{ $product->Naziv }}" class="w-100 h-100 object-fit-contain p-3" style="transition: transform 0.4s ease;">
                        </div>
                    </a>
                    <div class="card-body text-center">
                        <h6 class="fw-bold mb-1 text-truncate text-dark">{{ $product->Naziv }}</h6>
                        <p class="text-muted small mb-2">{{ Str::limit($product->Opis, 40) }}</p>
                        <h5 class="text-primary fw-bold mb-3">{{ number_format($product->Cijena, 2) }} €</h5>
                        <form action="{{ route('cart.add', ['id' => $product->Proizvod_ID]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm">
                                <i class="bi bi-cart-plus me-1"></i> Dodaj
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('proizvodi.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-5 border-2">
                Pogledaj sve proizvode
            </a>
        </div>
    </div>
</section>

<section id="recently-viewed" class="py-5 bg-light d-none border-top">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Nedavno pregledano</h2>
            <button onclick="clearRecentlyViewed()" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-trash me-1"></i> Očisti
            </button>
        </div>
        <div id="recently-viewed-products" class="row g-4"></div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const checkbox = document.getElementById('potvrdaCheck');
    const btn = document.getElementById('btnZatvori');

    if (!localStorage.getItem('upozorenje_prikazano')) {
        var myModal = new bootstrap.Modal(document.getElementById('zavrsniRadModal'));
        myModal.show();

        btn.addEventListener('click', function() {
            localStorage.setItem('upozorenje_prikazano', '1');
        });
    }

    checkbox.addEventListener('change', function() {
        btn.disabled = !this.checked;
    });

    
    loadRecentlyViewed();
});

async function loadRecentlyViewed() {
    const viewed = JSON.parse(localStorage.getItem('recentlyViewed') || '[]');
    const section = document.getElementById('recently-viewed');
    const container = document.getElementById('recently-viewed-products');

    if (viewed.length === 0) {
        section.classList.add('d-none');
        return;
    }

    try {
        const response = await fetch('{{ route("proizvodi.byIds") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids: viewed })
        });

        const products = await response.json();
        if (products.length === 0) {
            section.classList.add('d-none');
            return;
        }

        container.innerHTML = products.map(product => `
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                    <a href="${product.url}" class="text-decoration-none">
                        <div class="bg-white" style="height: 150px;">
                            <img src="${product.slika}" class="w-100 h-100 object-fit-contain p-2">
                        </div>
                    </a>
                    <div class="card-body text-center p-2">
                        <h6 class="small fw-bold mb-1 text-dark text-truncate">${product.naziv}</h6>
                        <p class="text-primary fw-bold mb-0 small">${product.cijena} €</p>
                    </div>
                </div>
            </div>
        `).join('');

        section.classList.remove('d-none');
    } catch (e) { console.error(e); }
}

function clearRecentlyViewed() {
    localStorage.removeItem('recentlyViewed');
    document.getElementById('recently-viewed').classList.add('d-none');
}
</script>

<style>

.hero-section { background: #0f172a; min-height: 450px; display: flex; align-items: center; }
.hero-overlay { position: absolute; inset: 0; background: url("data:image/svg+xml,%3Csvg width='60' height='60' ... %3E"); opacity: 0.1; }
.text-gradient { background: linear-gradient(90deg, #60a5fa, #a855f7); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.feature-icon { width: 55px; height: 55px; display: flex; align-items: center; justify-content: center; transition: 0.3s; }
.category-card:hover { transform: translateY(-7px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; transition: 0.3s; }
.category-icon { width: 65px; height: 65px; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
.product-card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.12) !important; transition: 0.3s; }
#product-row::-webkit-scrollbar { display: none; }
</style>

@endsection