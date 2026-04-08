<div class="col-md-6 col-lg-4 mb-3">
    <div class="card product-card h-100" data-product-id="{{ $product->Proizvod_ID }}">
        <div class="card-body">
            @if($product->Slika)
            <img src="{{ $product->slika_url }}" class="img-fluid mb-2 rounded" alt="{{ $product->Naziv }}" style="max-height: 120px; object-fit: contain;">
            @else
            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-2" style="height: 120px;">
                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
            </div>
            @endif

            <h6 class="card-title mb-1">{{ $product->Naziv }}</h6>
            <p class="card-text small text-muted mb-2">{{ Str::limit($product->KratkiOpis, 50) }}</p>

            @if($product->pcSpec)
            <div class="mb-2">
                @if($product->pcSpec->socket_type)
                <span class="badge bg-secondary me-1">{{ $product->pcSpec->socket_type }}</span>
                @endif
                @if($product->pcSpec->ram_type)
                <span class="badge bg-info me-1">{{ $product->pcSpec->ram_type }}</span>
                @endif
                @if($product->pcSpec->form_factor)
                <span class="badge bg-warning text-dark me-1">{{ $product->pcSpec->form_factor }}</span>
                @endif
                @if($product->pcSpec->tdp)
                <span class="badge bg-danger">{{ $product->pcSpec->tdp }}W TDP</span>
                @endif
                @if($product->pcSpec->wattage)
                <span class="badge bg-success">{{ $product->pcSpec->wattage }}W</span>
                @endif
            </div>
            @endif

            <div class="d-flex justify-content-between align-items-center">
                <span class="h5 mb-0 text-primary fw-bold">{{ number_format($product->Cijena, 2) }} €</span>
                <button class="btn btn-sm btn-outline-primary select-product-btn">
                    <i class="bi bi-plus-circle me-1"></i> Odaberi
                </button>
            </div>
        </div>
    </div>
</div>
