@extends('layouts.app')

@section('title', 'Moja košarica — TechShop')

@section('content')
<section class="cart-section py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold text-center mb-5 text-primary">
            <i class="bi bi-cart4 me-2"></i> Moja košarica
        </h2>

        @if(empty($cart))
            <div class="text-center py-5">
                <i class="bi bi-bag-x text-secondary" style="font-size: 4rem;"></i>
                <p class="text-muted mt-3 fs-5">Vaša košarica je trenutno prazna.</p>
                <a href="{{ route('proizvodi.index') }}" class="btn btn-primary mt-3 rounded-pill px-4 py-2 fw-semibold">
                    <i class="bi bi-shop me-2"></i> Nastavi kupovinu
                </a>
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    @if (session('success'))
                        <div class="alert alert-success text-center rounded-pill py-2 mb-4 shadow-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('warning'))
                        <div class="alert alert-warning text-center rounded-pill py-2 mb-4 shadow-sm">
                            <i class="bi bi-exclamation-triangle me-1"></i> {{ session('warning') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger text-center rounded-pill py-2 mb-4 shadow-sm">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive shadow-lg rounded-4 bg-white p-4">
                        <table class="table align-middle mb-0">
                            <thead class="bg-primary text-white rounded-top">
                                <tr>
                                    <th>Proizvod</th>
                                    <th class="text-center">Količina</th>
                                    <th class="text-center">Cijena</th>
                                    <th class="text-center">Ukupno</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = 0;
                                    $taxRate = config('shop.pdv_stopa');
                                    $delivery = 5.00;
                                @endphp

                                @foreach($cart as $id => $item)
                                    @php
                                        if (isset($item['product'])) {
                                            $product  = $item['product'];
                                            $name     = $product->Naziv;
                                            $price    = $product->Cijena;
                                            $imageUrl = $product->slika_url;
                                            $quantity = $item['quantity'];
                                            $stock    = $product->StanjeNaSkladistu;
                                        } else {
                                            $name     = $item['name'];
                                            $price    = $item['price'];
                                            $imageUrl = $item['image'] ?? asset('img/no-image.svg');
                                            $quantity = $item['quantity'];
                                            $stock    = $item['stock'] ?? 99;
                                        }

                                        $subtotalWithTax    = $price * $quantity;
                                        $subtotalWithoutTax = $subtotalWithTax / (1 + $taxRate);
                                        $total += $subtotalWithTax;
                                    @endphp

                                    <tr class="cart-item-row">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="cart-img-wrapper me-3">
                                                    <img src="{{ $imageUrl }}" alt="{{ $name }}" class="cart-img">
                                                </div>
                                                <div>
                                                    <h6 class="fw-semibold mb-1"><a href="{{ route('proizvod.show', $id) }}" class="text-dark text-decoration-none hover-link">{{ $name }}</a></h6>
                                                    <small class="text-muted">Šifra: {{ $id }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex align-items-center justify-content-center gap-2">
                                                <form action="{{ route('cart.update', $id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="quantity" value="{{ max(1, $quantity - 1) }}">
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm rounded-circle" @if($quantity <= 1) disabled @endif style="width:32px;height:32px;padding:0;">
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                </form>

                                                <span class="fw-semibold mx-1" style="min-width:24px;">{{ $quantity }}</span>

                                                <form action="{{ route('cart.update', $id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="quantity" value="{{ $quantity + 1 }}">
                                                    <button type="submit" class="btn btn-outline-secondary btn-sm rounded-circle" @if($quantity >= $stock) disabled @endif style="width:32px;height:32px;padding:0;">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                        <td class="text-center text-muted">
                                            <small class="text-secondary">
                                                Bez PDV-a: {{ number_format($price / (1 + $taxRate), 2) }} €
                                            </small>
                                            <div>{{ number_format($price, 2) }} €</div>
                                        </td>

                                        <td class="text-center">
                                            <small class="text-secondary">
                                                Bez PDV-a: {{ number_format($subtotalWithoutTax, 2) }} €
                                            </small>
                                            <div class="fw-bold text-primary">
                                                {{ number_format($subtotalWithTax, 2) }} €
                                            </div>
                                        </td>

                                        <td class="text-center">
                                            <form action="{{ route('cart.remove', ['id' => $id]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm rounded-pill">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 mt-5 p-4 bg-white">
                        @php
                            $taxAmount    = $total - ($total / (1 + $taxRate));
                            $freeShipping = $total >= 50;
                            $grandTotal   = $freeShipping ? $total : $total + $delivery;
                        @endphp

                        <div class="row">
                            <div class="col-md-6 text-md-start text-center mb-3">
                                <a href="{{ route('proizvodi.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-semibold">
                                    <i class="bi bi-arrow-left me-2"></i> Nastavi kupovinu
                                </a>
                            </div>

                            <div class="col-md-6 text-md-end text-center">
                                <p>Ukupno bez PDV-a: <strong>{{ number_format($total / (1 + $taxRate), 2) }} €</strong></p>
                                <p>PDV ({{ number_format($taxRate * 100, 0) }}%): <strong>{{ number_format($taxAmount, 2) }} €</strong></p>
                                <p>Dostava:
                                    @if($freeShipping)
                                        <strong class="text-success">Besplatna <i class="bi bi-check-circle-fill"></i></strong>
                                    @else
                                        <strong>{{ number_format($delivery, 2) }} €</strong>
                                    @endif
                                </p>
                                <h4 class="fw-bold">
                                    Ukupno za platiti:
                                    <span class="text-primary">{{ number_format($grandTotal, 2) }} €</span>
                                </h4>

                                <a href="{{ route('checkout.index') }}"
                                   class="btn btn-primary rounded-pill px-5 py-2 fw-semibold shadow-sm mt-3">
                                    <i class="bi bi-credit-card me-2"></i> Završi kupnju
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endif
    </div>
</section>

<style>
.cart-section { min-height: 70vh; }

.cart-img-wrapper {
    width: 70px;
    height: 70px;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.cart-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .3s ease;
}

.cart-img-wrapper:hover .cart-img {
    transform: scale(1.08);
}

.cart-item-row:hover {
    background-color: rgba(13,110,253,0.05);
}

.table td, .table th {
    vertical-align: middle;
    padding: 1rem;
}

.hover-link:hover { color: #0d6efd !important; text-decoration: underline !important; }
</style>
@endsection
