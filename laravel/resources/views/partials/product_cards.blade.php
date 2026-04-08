@foreach($proizvodi as $proizvod)
    <div class="col-md-6 col-lg-4 mb-4">
       
        <div class="card product-card h-100 border-0 shadow-sm rounded-4 overflow-hidden" 
             style="cursor:pointer; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); background: #ffffff;"
             onclick="window.location='{{ route('proizvod.show', $proizvod->Proizvod_ID) }}'"
             onmouseover="this.style.transform='translateY(-8px)'; this.style.boxShadow='0 12px 24px rgba(0,0,0,0.1)';"
             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 .125rem .25rem rgba(0,0,0,.075)';"
        >
            
            <div class="card-body p-3 d-flex flex-column">
               
                <div class="product-image-wrapper mb-3 bg-light rounded-4 d-flex align-items-center justify-content-center" 
                     style="height: 180px; padding: 15px;">
                    @if($proizvod->slika)
                        <img src="{{ $proizvod->slika_url }}" 
                             class="img-fluid h-100" 
                             alt="{{ $proizvod->Naziv }}" 
                             style="object-fit: contain; transition: transform 0.3s ease;">
                    @else
                        <div class="text-center">
                            <i class="bi bi-image text-muted opacity-50" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                </div>

                <div class="mb-2">
                    <h6 class="card-title fw-bold mb-1 text-dark text-truncate-2" 
                        style="height: 2.8rem; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                        {{ $proizvod->Naziv }}
                    </h6>
                    <small class="text-muted d-block mb-2">
                        <i class="bi bi-tag me-1"></i>{{ $proizvod->kategorija->ImeKategorija ?? 'Hardver' }}
                    </small>
                </div>

                
                <div class="spec-badges d-flex flex-wrap gap-1 mb-3" style="min-height: 26px;">
                    @if($proizvod->pcSpec)
                        @if($proizvod->pcSpec->socket_type)
                            <span class="badge bg-secondary-subtle text-secondary border-0 px-2 py-1" style="font-size: 0.7rem;">
                                {{ $proizvod->pcSpec->socket_type }}
                            </span>
                        @endif
                        @if($proizvod->pcSpec->ram_type)
                            <span class="badge bg-info-subtle text-info border-0 px-2 py-1" style="font-size: 0.7rem;">
                                {{ $proizvod->pcSpec->ram_type }}
                            </span>
                        @endif
                        @if($proizvod->pcSpec->form_factor)
                            <span class="badge bg-warning-subtle text-dark border-0 px-2 py-1" style="font-size: 0.7rem;">
                                {{ $proizvod->pcSpec->form_factor }}
                            </span>
                        @endif
                        @if($proizvod->pcSpec->tdp)
                            <span class="badge bg-danger-subtle text-danger border-0 px-2 py-1" style="font-size: 0.7rem;">
                                {{ $proizvod->pcSpec->tdp }}W
                            </span>
                        @endif
                    @endif
                </div>

                <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                    <div>
                        <span class="h5 mb-0 text-primary fw-bold">{{ number_format($proizvod->Cijena, 2) }} €</span>
                    </div>
                    
                    <form action="{{ route('cart.add', ['id' => $proizvod->Proizvod_ID]) }}" 
                          method="POST" 
                          class="js-add-to-cart" 
                          data-product-name="{{ $proizvod->Naziv }}"
                          onclick="event.stopPropagation();"
                    >
                        @csrf
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm fw-bold js-add-to-cart-btn">
                            <i class="bi bi-cart-plus me-1"></i> Dodaj
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@if($proizvodi->isEmpty())
    <div class="col-12 py-5 text-center">
        <div class="bg-light d-inline-block rounded-circle p-4 mb-3">
            <i class="bi bi-search text-muted display-4"></i>
        </div>
        <h4 class="fw-bold">Nema pronađenih proizvoda</h4>
        <p class="text-muted">Pokušajte s drugim filtrima ili pretraživanjem.</p>
    </div>
@endif

<style>

.text-truncate-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.bg-secondary-subtle { background-color: #f0f1f2 !important; color: #6c757d !important; }
.bg-info-subtle { background-color: #e7f6f8 !important; color: #0dcaf0 !important; }
.bg-warning-subtle { background-color: #fff3cd !important; color: #664d03 !important; }
.bg-danger-subtle { background-color: #fdebed !important; color: #dc3545 !important; }


.product-card:hover img {
    transform: scale(1.05);
}
</style>