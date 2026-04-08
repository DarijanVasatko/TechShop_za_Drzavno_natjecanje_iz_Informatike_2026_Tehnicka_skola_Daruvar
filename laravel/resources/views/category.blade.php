@extends('layouts.app')

@php
    $categoryId = $categoryId ?? (int) request()->route('id');
@endphp

@section('title',
    $categoryId
        ? 'TechShop - ' . ($kategorije->firstWhere('id_kategorija', $categoryId)->ImeKategorija ?? '')
        : 'TechShop - Svi proizvodi'
)

@section('content')
<div class="container py-5" id="products-page"
     data-ajax-url="{{ route('proizvodi.search') }}"
     data-category-id="{{ $categoryId }}">

    <div class="row">
        <div class="col-lg-3 mb-4">
            
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3 text-center text-primary">Kategorije</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item border-0 p-0">
                            <a href="{{ route('proizvodi.index') }}"
                               class="d-block px-3 py-2 rounded text-decoration-none
                               {{ empty($categoryId) ? 'bg-primary text-white fw-bold' : 'text-dark' }}">
                                <i class="bi bi-grid-fill me-2"></i> Sve kategorije
                            </a>
                        </li>
                        @foreach($kategorije as $kat)
                            @php 
                                $active = (int)$categoryId === (int)$kat->id_kategorija;
                                $ime = strtolower($kat->ImeKategorija);
                                
                                $icon = 'bi-box-seam'; 
                                if (str_contains($ime, 'laptop')) $icon = 'bi-laptop';
                                elseif (str_contains($ime, 'mobitel')) $icon = 'bi-phone';
                                elseif (str_contains($ime, 'procesor') || str_contains($ime, 'cpu')) $icon = 'bi-cpu';
                                elseif (str_contains($ime, 'grafičk') || str_contains($ime, 'gpu')) $icon = 'bi-gpu-card';
                                elseif (str_contains($ime, 'memorij') || str_contains($ime, 'ram')) $icon = 'bi-memory';
                                elseif (str_contains($ime, 'monitor') || str_contains($ime, 'ekran')) $icon = 'bi-display';
                                elseif (str_contains($ime, 'miš') || str_contains($ime, 'tipkovnic')) $icon = 'bi-mouse3';
                                elseif (str_contains($ime, 'ssd') || str_contains($ime, 'hdd') || str_contains($ime, 'pohran')) $icon = 'bi-hdd-network';
                                elseif (str_contains($ime, 'matičn') || str_contains($ime, 'ploč')) $icon = 'bi-motherboard';
                                elseif (str_contains($ime, 'napajanj') || str_contains($ime, 'psu')) $icon = 'bi-lightning-charge';
                                elseif (str_contains($ime, 'kućišt') || str_contains($ime, 'case')) $icon = 'bi-pc-display';
                            @endphp
                            <li class="list-group-item border-0 p-0">
                                <a href="{{ route('proizvodi.kategorija', ['id' => $kat->id_kategorija]) }}"
                                   class="d-block px-3 py-2 rounded text-decoration-none
                                   {{ $active ? 'bg-primary text-white fw-bold' : 'text-dark' }}">
                                    <i class="bi {{ $icon }} me-2"></i> {{ $kat->ImeKategorija }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body">
                    <h5 class="fw-semibold mb-3 text-center text-primary">Prilagodi pretragu</h5>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small">Cijena do: <span id="price-label" class="text-primary">5000</span> €</label>
                        <input type="range" class="form-range" min="0" max="5000" step="50" id="filter-price" value="5000">
                        <div class="d-flex justify-content-between small text-muted">
                            <span>0€</span>
                            <span>5000€</span>
                        </div>
                    </div>

                    @if(!empty($types) && $types->count() > 1)
                    <div class="mb-4">
                        <label class="form-label fw-bold small">Tip proizvoda</label>
                        <select class="form-select form-select-sm" id="filter-type">
                            <option value="">Svi tipovi</option>
                            @foreach($types as $type)
                                <option value="{{ $type->id_tip }}">{{ $type->naziv_tip }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    @if(!empty($brands) && $brands->count())
                    <div class="mb-4">
                        <label class="form-label fw-bold small">Brend</label>
                        <select class="form-select form-select-sm" id="filter-brand">
                            <option value="">Svi brendovi</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div id="dynamic-specs-container">
                        @if(!empty($specFilters))
                            @foreach($specFilters as $key => $options)
                                @if(count($options) > 0)
                                    <div class="mb-3">
                                        <label class="form-label fw-bold small text-muted text-uppercase" style="font-size: 0.7rem;">
                                            {{ str_replace('_', ' ', $key) }}
                                        </label>
                                        <select class="form-select form-select-sm spec-filter" data-spec="{{ $key }}">
                                            <option value="">Svi tipovi</option>
                                            @foreach($options as $opt)
                                                <option value="{{ $opt }}">{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <button type="button" id="reset-btn-sidebar" class="btn btn-outline-secondary btn-sm w-100 rounded-pill mt-3">
                        <i class="bi bi-arrow-counterclockwise me-1"></i> Poništi sve
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="mb-4">
                <div class="input-group shadow-sm rounded-pill overflow-hidden bg-white border">
                    <span class="input-group-text border-0 bg-transparent ps-4">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" id="search-input" class="form-control border-0 py-3 ps-2" 
                           placeholder="Pretraži dostupne komponente..." autocomplete="off">
                </div>
            </div>

            <div id="products-container" class="position-relative">
                @include('partials.products-grid', ['proizvodi' => $proizvodi])
            </div>

            <div id="pagination-container" class="d-flex justify-content-center mt-4">
                @include('partials.products-pagination', ['proizvodi' => $proizvodi])
            </div>
        </div>
    </div>
</div>

<style>
    .product-card { transition: all 0.3s ease; border: none; }
    .product-card:hover { transform: translateY(-8px); box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important; }
    .list-group-item a { transition: all 0.2s; }
    .list-group-item a:hover:not(.bg-primary) { background-color: #f8f9fa; color: #0d6efd !important; padding-left: 2rem !important; }
    .form-range::-webkit-slider-thumb { background: #0d6efd; border: 2px solid #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
</style>

<script>
(function () {
    const pageEl = document.getElementById('products-page');
    if (!pageEl) return;

    const productsContainer = document.getElementById('products-container');
    const paginationContainer = document.getElementById('pagination-container');
    const searchInput = document.getElementById('search-input');
    const priceFilter = document.getElementById('filter-price');
    const priceLabel = document.getElementById('price-label');
    const typeFilter = document.getElementById('filter-type');
    const brandFilter = document.getElementById('filter-brand');

    let t;
    const debounce = (fn, delay = 400) => {
        return (...args) => {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), delay);
        };
    };

    function buildParams(page = 1) {
        const p = new URLSearchParams();
        const cid = pageEl.getAttribute('data-category-id');
        
        if (cid) p.set('categoryId', cid);
        if (searchInput.value) p.set('search', searchInput.value);
        if (priceFilter.value) p.set('max_price', priceFilter.value);
        if (typeFilter && typeFilter.value) p.set('type', typeFilter.value);
        if (brandFilter && brandFilter.value) p.set('brand', brandFilter.value);

        document.querySelectorAll('.spec-filter').forEach(select => {
            if (select.value) p.set(`specs[${select.dataset.spec}]`, select.value);
        });

        if (page !== 1) p.set('page', page);
        return p.toString();
    }

    function wirePagination() {
        paginationContainer?.querySelectorAll('a.page-link').forEach(a => {
            a.onclick = e => {
                e.preventDefault();
                const url = new URL(a.href);
                load(url.searchParams.get('page') || 1);
            };
        });
    }

    function load(page = 1) {
        const ajaxUrl = pageEl.getAttribute('data-ajax-url');
        productsContainer.style.opacity = '0.5';

        fetch(ajaxUrl + '?' + buildParams(page), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            productsContainer.innerHTML = data.html;
            paginationContainer.innerHTML = data.pagination;
            productsContainer.style.opacity = '1';
            wirePagination();

            if (brandFilter && data.brands) {
                const currentBrand = brandFilter.value;
                brandFilter.innerHTML = '<option value="">Svi brendovi</option>';
                data.brands.forEach(b => {
                    const opt = document.createElement('option');
                    opt.value = b.id;
                    opt.textContent = b.name;
                    if (String(b.id) === currentBrand) opt.selected = true;
                    brandFilter.appendChild(opt);
                });
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        })
        .catch(() => productsContainer.style.opacity = '1');
    }

    priceFilter?.addEventListener('input', (e) => priceLabel.innerText = e.target.value);
    priceFilter?.addEventListener('change', () => load(1));
    searchInput?.addEventListener('input', debounce(() => load(1)));
    typeFilter?.addEventListener('change', () => {
        if (brandFilter) brandFilter.value = '';
        load(1);
    });
    brandFilter?.addEventListener('change', () => load(1));
    document.querySelectorAll('.spec-filter').forEach(s => s.addEventListener('change', () => load(1)));
    document.getElementById('reset-btn-sidebar')?.addEventListener('click', () => {
        searchInput.value = '';
        priceFilter.value = 5000;
        priceLabel.innerText = 5000;
        if (typeFilter) typeFilter.value = '';
        if (brandFilter) brandFilter.value = '';
        document.querySelectorAll('.spec-filter').forEach(s => s.value = '');
        load(1);
    });

    wirePagination();
})();
</script>
@endsection