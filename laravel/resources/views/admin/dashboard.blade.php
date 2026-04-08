@extends('layouts.admin')

@section('title', 'Admin Dashboard — TechShop')

@section('content')
<h2 class="fw-bold mb-3">Pregled administracije</h2>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Proizvodi</h6>
                <h3 class="fw-bold">{{ $productsCount }}</h3>
                <a href="{{ route('admin.products.index') }}" class="btn btn-primary btn-sm">
                    Upravljanje proizvodima
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Narudžbe</h6>
                <h3 class="fw-bold">{{ $ordersCount }}</h3>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-success btn-sm">
                    Upravljanje narudžbama
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Korisnici</h6>
                <h3 class="fw-bold">{{ $usersCount }}</h3>
                <a href="{{ route('admin.users.index') }}" class="btn btn-info text-white btn-sm">
                    Pregled korisnika
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted">Promo kodovi</h6>
                <h3 class="fw-bold">{{ $promocodesCount }}</h3>
                <a href="{{ route('admin.promo-kodovi.index') }}" class="btn btn-warning text-white btn-sm">
                    Upravljanje promo kodovima
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h5 class="fw-bold mb-0">Google Analytics (Looker Studio)</h5>

                    @if(!empty($lookerUrl))
                        <a class="btn btn-outline-secondary btn-sm" href="{{ $lookerUrl }}" target="_blank" rel="noopener">
                            Otvori u novom tabu
                        </a>
                    @endif
                </div>

                @if(empty($lookerEmbedUrl))
                    <div class="alert alert-warning mb-0">
                        Analytics nije konfiguriran. Dodaj Looker Studio embed URL u <code>.env</code> (LOoker Studio embed).
                    </div>
                @else
                    <div class="ratio ratio-16x9">
                        <iframe
                            src="{{ $lookerEmbedUrl }}"
                            style="border:0;"
                            allowfullscreen
                        ></iframe>
                    </div>
                    <small class="text-muted d-block mt-2">
                        * Looker Studio je preporučen način za ugradnju GA izvještaja preko iframe-a.
                    </small>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
