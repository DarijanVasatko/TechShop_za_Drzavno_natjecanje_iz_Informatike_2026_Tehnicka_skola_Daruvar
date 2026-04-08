@extends('layouts.app')

@section('title', $proizvod->Naziv . ' — TechShop')

@section('content')
<section class="py-4 bg-white">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('proizvodi.index') }}" class="text-decoration-none">Trgovina</a></li>
                <li class="breadcrumb-item active">{{ $proizvod->tipProizvoda->Naziv ?? 'Artikl' }}</li>
            </ol>
        </nav>

        <div class="row g-4 mb-5">
            <div class="col-md-6 col-lg-5">
                <div class="product-gallery p-4 border rounded-3 bg-white d-flex align-items-center justify-content-center shadow-sm" style="min-height: 450px;">
                    <img src="{{ $proizvod->slika_url }}" 
                         alt="{{ $proizvod->Naziv }}" 
                         class="img-fluid main-img" 
                         style="max-height: 400px; transition: transform 0.3s ease;">
                </div>
            </div>

            <div class="col-md-6 col-lg-7">
                <div class="ps-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        <span class="badge bg-primary-subtle text-primary">Novo u ponudi</span>
                        @if($proizvod->brand)
                            <span class="badge bg-dark bg-opacity-75 fw-semibold px-3 py-1">
                                <i class="bi bi-award me-1"></i>{{ $proizvod->brand->name }}
                            </span>
                        @endif
                    </div>
                    <h1 class="fw-bold text-dark mb-1">{{ $proizvod->Naziv }}</h1>
                    <p class="text-muted small mb-3">Šifra proizvoda: #{{ $proizvod->Proizvod_ID }}</p>

                    <div class="short-description mb-4 p-3 bg-light rounded-2 border-start border-primary border-4">
                        <h6 class="fw-bold text-uppercase small text-secondary mb-1">Kratki pregled</h6>
                        <p class="mb-0 text-dark">{{ $proizvod->Opis }}</p>
                    </div>

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="viewers-tag px-3 py-1 rounded-pill small border shadow-sm">
                            <span class="pulse-dot"></span>
                            <span id="viewers-count">{{ rand(5, 23) }} </span>  &nbsp;ljudi gleda
                        </div>
                        @if($proizvod->StanjeNaSkladistu > 0)
                            <span class="text-success fw-bold small"><i class="bi bi-check2-circle me-1"></i> Dostupno ({{ $proizvod->StanjeNaSkladistu }} kom)</span>
                        @else
                            <span class="text-danger fw-bold small"><i class="bi bi-x-circle me-1"></i> Nema na zalihi</span>
                        @endif
                    </div>

                    <div class="purchase-box p-4 border rounded-3 bg-white shadow-sm mb-4">
                        <div class="row align-items-center">
                            <div class="col-sm-6">
                                <h2 class="fw-bold text-primary mb-0">{{ number_format($proizvod->Cijena, 2) }} €</h2>
                                <p class="text-muted small mb-0">Uključujući PDV (25%)</p>
                            </div>
                            <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                                <form action="{{ route('cart.add', ['id' => $proizvod->Proizvod_ID]) }}" method="POST" class="js-add-to-cart d-flex justify-content-sm-end gap-2">
                                    @csrf
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $proizvod->StanjeNaSkladistu }}" class="form-control text-center shadow-none" style="width: 70px;">
                                    <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm" {{ $proizvod->StanjeNaSkladistu <= 0 ? 'disabled' : '' }}>
                                        <i class="bi bi-cart-plus me-2"></i> DODAJ
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row pt-4">
            <div class="col-12">
                <div class="detail-container border-top">
                    <ul class="nav nav-pills mb-4 mt-4" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active fw-bold text-uppercase px-4 shadow-sm me-2" data-bs-toggle="pill" data-bs-target="#detailed-text">
                                <i class="bi bi-file-text me-2"></i>Detaljni Opis
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link fw-bold text-uppercase px-4 shadow-sm" data-bs-toggle="pill" data-bs-target="#specs">
                                <i class="bi bi-gear me-2"></i>Specifikacije
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content p-4 bg-light border rounded-3 shadow-sm" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="detailed-text" role="tabpanel">
                            <div class="detailed-content-wrapper lh-lg">
                                @if($proizvod->DetaljniOpis)
                                    <h4 class="fw-bold mb-3 border-bottom pb-2 text-dark">Potpune informacije o proizvodu</h4>
                                    <div class="text-secondary fs-5">
                                        {!! nl2br(e($proizvod->DetaljniOpis)) !!}
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="bi bi-info-circle fs-1 text-muted"></i>
                                        <p class="mt-2 text-muted">Detaljne informacije će uskoro biti dostupne.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="specs" role="tabpanel">
                             <table class="table table-hover mb-0 bg-white rounded overflow-hidden shadow-sm">
                                <tbody>
                                    <tr><th class="w-25 bg-light">Brand</th><td>{{ $proizvod->brand->name ?? '—' }}</td></tr>
                                    <tr><th class="bg-light">Model</th><td>{{ $proizvod->Naziv }}</td></tr>
                                    <tr><th class="bg-light">Kategorija</th><td>{{ $proizvod->tipProizvoda->Naziv ?? 'Hardware' }}</td></tr>
                                    <tr><th class="bg-light">ID</th><td>#{{ $proizvod->Proizvod_ID }}</td></tr>
                                </tbody>
                             </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-5">
            <div class="col-12">
                <div class="border-top pt-4">
                    <h4 class="fw-bold mb-4">
                        <i class="bi bi-chat-left-text me-2"></i>Recenzije kupaca
                        @if($recenzije->count())
                            <span class="badge bg-primary ms-2">{{ $recenzije->count() }}</span>
                        @endif
                    </h4>

                    @if($recenzije->count())
                        @php $prosjek = round($recenzije->avg('ocjena'), 1); @endphp
                        <div class="d-flex align-items-center gap-3 mb-4 p-3 bg-light rounded-3">
                            <div class="text-center">
                                <span class="display-5 fw-bold text-primary">{{ $prosjek }}</span>
                                <span class="text-muted fs-5">/ 5</span>
                            </div>
                            <div>
                                <div class="stars fs-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= round($prosjek) ? '-fill text-warning' : ' text-muted' }}"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">Temeljem {{ $recenzije->count() }} {{ $recenzije->count() == 1 ? 'recenzije' : 'recenzija' }}</small>
                            </div>
                        </div>
                    @endif

                    @if($mozeRecenzirati)
                        <div class="card shadow-sm mb-4">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="bi bi-pencil-square me-1"></i> Ostavite svoju recenziju</h6>

                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if(session('error'))
                                    <div class="alert alert-danger">{{ session('error') }}</div>
                                @endif

                                <form action="{{ route('recenzija.store', $proizvod->Proizvod_ID) }}" method="POST">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Ocjena</label>
                                        <div class="star-rating fs-3" id="star-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star star-select" data-value="{{ $i }}" role="button"></i>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="ocjena" id="ocjena-input" value="{{ old('ocjena') }}" required>
                                        @error('ocjena')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="komentar" class="form-label fw-semibold">Komentar <span class="text-muted fw-normal">(opcionalno)</span></label>
                                        <textarea name="komentar" id="komentar" rows="3" maxlength="1000"
                                                  class="form-control @error('komentar') is-invalid @enderror"
                                                  placeholder="Podijelite svoje iskustvo s ovim proizvodom...">{{ old('komentar') }}</textarea>
                                        @error('komentar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary fw-bold">
                                        <i class="bi bi-send me-1"></i> Pošalji recenziju
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif($postojecaRecenzija)
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle me-1"></i>
                            Već ste ostavili recenziju za ovaj proizvod.
                            @if(!$postojecaRecenzija->odobrena)
                                Vaša recenzija čeka odobrenje administratora.
                            @endif
                        </div>
                    @endif

                    @forelse($recenzije as $recenzija)
                        <div class="card mb-3 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <span class="fw-bold">{{ $recenzija->user->full_name }}</span>
                                        <div class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $recenzija->ocjena ? '-fill text-warning' : ' text-muted' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $recenzija->created_at->format('d.m.Y') }}</small>
                                </div>
                                @if($recenzija->komentar)
                                    <p class="mb-0 text-secondary">{{ $recenzija->komentar }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-chat-left fs-1"></i>
                            <p class="mt-2">Još nema recenzija za ovaj proizvod.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</section>

<style>
   
    :root {
        --ts-primary: #0062cc;
        --ts-bg: #f8f9fa;
    }

    body { background-color: #fff; }

    .breadcrumb { font-size: 0.85rem; }
    
    .product-gallery { border: 1px solid #eee !important; transition: all 0.3s; }
    .product-gallery:hover { border-color: var(--ts-primary) !important; }
    .main-img:hover { transform: scale(1.05); }

    .short-description { background-color: #f1f8ff !important; border-left: 4px solid var(--ts-primary) !important; }

    .viewers-tag { background: #fff; font-weight: 600; color: #555; display: inline-flex; align-items: center; }
    .pulse-dot {
        height: 8px; width: 8px; background-color: #ff4444; border-radius: 50%;
        display: inline-block; margin-right: 8px;
        box-shadow: 0 0 0 rgba(255, 68, 68, 0.4);
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 68, 68, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(255, 68, 68, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(255, 68, 68, 0); }
    }

    .nav-pills .nav-link { color: #555; background: #eee; border-radius: 6px; }
    .nav-pills .nav-link.active { background-color: var(--ts-primary); box-shadow: 0 4px 12px rgba(0, 98, 204, 0.3); }
    
    .detailed-content-wrapper { color: #444; }

    .purchase-box { border: 1px solid #e0e0e0 !important; }
    .btn-primary { background-color: var(--ts-primary); border: none; padding: 10px 25px; }
</style>

<script>
    
    (function() {
        const viewersEl = document.getElementById('viewers-count');
        if (!viewersEl) return;
        let current = parseInt(viewersEl.textContent);
        setInterval(() => {
            const change = Math.floor(Math.random() * 3) - 1;
            current = Math.max(3, Math.min(30, current + change));
            viewersEl.textContent = current;
        }, 4000);
    })();

    (function() {
        const stars = document.querySelectorAll('#star-rating .star-select');
        const input = document.getElementById('ocjena-input');
        if (!stars.length || !input) return;

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const val = star.dataset.value;
                input.value = val;
                stars.forEach(s => {
                    s.classList.toggle('bi-star-fill', s.dataset.value <= val);
                    s.classList.toggle('text-warning', s.dataset.value <= val);
                    s.classList.toggle('bi-star', s.dataset.value > val);
                    s.classList.toggle('text-muted', s.dataset.value > val);
                });
            });
            star.addEventListener('mouseenter', () => {
                const val = star.dataset.value;
                stars.forEach(s => {
                    s.classList.toggle('bi-star-fill', s.dataset.value <= val);
                    s.classList.toggle('bi-star', s.dataset.value > val);
                });
            });
        });
        document.getElementById('star-rating').addEventListener('mouseleave', () => {
            const val = input.value || 0;
            stars.forEach(s => {
                s.classList.toggle('bi-star-fill', s.dataset.value <= val);
                s.classList.toggle('text-warning', s.dataset.value <= val);
                s.classList.toggle('bi-star', s.dataset.value > val);
                s.classList.toggle('text-muted', s.dataset.value > val);
            });
        });
    })();
</script>
@endsection