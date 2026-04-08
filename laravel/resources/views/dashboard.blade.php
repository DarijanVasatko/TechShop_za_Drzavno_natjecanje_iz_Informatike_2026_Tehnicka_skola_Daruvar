@extends('layouts.app')

@section('title', 'TechShop Dashboard')

@section('content')
    <section class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5 text-center">
                        <h2 class="fw-bold text-primary mb-3">
                            <i class="bi bi-hand-thumbs-up-fill me-2"></i> Dobrodošli, {{ Auth::user()->name }}!
                        </h2>

                        <p class="lead text-muted mb-4">
                            Drago nam je što ste odabrali nas u <span class="fw-semibold text-primary">TechShopu</span>.
                            Istražite najnovije proizvode, pratite svoje narudžbe i uživajte u kupnji najmodernije
                            tehnologije!
                        </p>

                        <div class="d-flex justify-content-center gap-3 flex-wrap mt-4">
                            <a href="{{ route('index.index') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">
                                <i class="bi bi-house me-2"></i> Na početnu
                            </a>
                            <a href="{{ route('proizvodi.index') }}"
                                class="btn btn-primary rounded-pill px-4 py-2 fw-semibold">
                                <i class="bi bi-shop me-2"></i> Pregledaj proizvode
                            </a>

                            <a href="{{ route('profile.edit') }}"
                                class="btn btn-outline-primary rounded-pill px-4 py-2 fw-semibold">
                                <i class="bi bi-person-gear me-2"></i> Moj profil
                            </a>

                            <a href="{{ route('cart.index') }}"
                                class="btn btn-outline-secondary rounded-pill px-4 py-2 fw-semibold">
                                <i class="bi bi-cart me-2"></i> Moja košarica
                            </a>
                        </div>

                        <hr class="my-5">

                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="fw-bold text-primary"><i class="bi bi-stars me-2"></i> Najnovije ponude
                                        </h5>
                                        <p class="text-muted mb-0">Iskoristite posebne popuste i akcije dostupne samo
                                            registriranim korisnicima!</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="fw-bold text-primary"><i class="bi bi-box-seam me-2"></i> Brza dostava
                                        </h5>
                                        <p class="text-muted mb-0">Svi proizvodi isporučuju se brzo i sigurno diljem
                                            Hrvatske.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="fw-bold text-primary"><i class="bi bi-shield-check me-2"></i> Sigurna
                                            kupnja</h5>
                                        <p class="text-muted mb-0">Vaši podaci i plaćanja zaštićeni su najnovijim
                                            sigurnosnim standardima.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger rounded-pill px-4 py-2 fw-semibold">
                                    <i class="bi bi-box-arrow-right me-2"></i> Odjava
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border-radius: 1rem;
        }

        .card:hover {
            transform: translateY(-3px);
            transition: 0.3s;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
    </style>
@endsection