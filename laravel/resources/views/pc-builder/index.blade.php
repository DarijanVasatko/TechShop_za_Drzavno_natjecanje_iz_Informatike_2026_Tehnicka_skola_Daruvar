@extends('layouts.app')

@section('title', 'Konfiguriraj svoj PC - TechShop')

@section('content')
<div class="container-fluid py-4">
    <div class="text-center mb-4">
        <h1 class="display-5 fw-bold">
            <i class="bi bi-pc-display-horizontal text-primary me-2"></i>
            Konfiguriraj svoj PC
        </h1>
        <p class="text-muted">Odaberi komponente korak po korak i sastavi svoje idealno računalo</p>
        <button class="btn btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#aiRecommendationModal">
            <i class="bi bi-stars me-2"></i>AI Preporuka konfiguracije
        </button>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap" id="progress-steps">
                @foreach($componentTypes as $index => $type)
                <div class="step-item text-center px-2 {{ $index === 0 ? 'active' : '' }}"
                     data-step="{{ $type->slug }}"
                     data-type-id="{{ $type->id_tip }}"
                     data-required="{{ $type->obavezan ? 'true' : 'false' }}">
                    <div class="step-icon mx-auto mb-1 d-flex align-items-center justify-content-center rounded-circle
                                {{ $index === 0 ? 'bg-primary text-white' : 'bg-light text-muted' }}"
                         style="width: 45px; height: 45px; cursor: pointer;">
                        <i class="bi {{ $type->ikona }}" style="font-size: 1.2rem;"></i>
                    </div>
                    <small class="d-none d-md-block {{ $index === 0 ? 'fw-semibold text-primary' : 'text-muted' }}">
                        {{ $type->naziv_tip }}
                    </small>
                    <span class="step-check d-none text-success"><i class="bi bi-check-circle-fill"></i></span>
                </div>
                @if(!$loop->last)
                <div class="step-connector flex-grow-1 d-none d-lg-block" style="height: 2px; background: #dee2e6; margin-top: -20px;"></div>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0" id="step-title">
                            <i class="bi bi-cpu text-primary me-2"></i>
                            Odaberi Procesor
                        </h5>
                        <span class="badge bg-primary" id="step-counter">Korak 1 od {{ count($componentTypes) }}</span>
                    </div>
                </div>
                <div class="card-body">
                    <div id="products-container">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Učitavanje...</span>
                            </div>
                            <p class="text-muted mt-2">Učitavanje komponenti...</p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                        <button class="btn btn-outline-secondary" id="btn-prev" disabled>
                            <i class="bi bi-arrow-left me-1"></i> Natrag
                        </button>
                        <button class="btn btn-primary" id="btn-next">
                            Dalje <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sticky-configuration">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white py-3">
                        <h5 class="mb-0">
                            <i class="bi bi-list-check me-2"></i>
                            Tvoja konfiguracija
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush" id="configuration-list">
                            @foreach($componentTypes as $type)
                            <li class="list-group-item config-item"
                                data-type-id="{{ $type->id_tip }}"
                                data-type-slug="{{ $type->slug }}">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="bi {{ $type->ikona }} text-muted me-2"></i>
                                        <span class="component-name text-muted">{{ $type->naziv_tip }}</span>
                                        <small class="d-block text-muted component-product" style="margin-left: 1.5rem;">
                                            Nije odabrano
                                        </small>
                                    </div>
                                    <div class="text-end">
                                        <span class="component-price fw-semibold">-</span>
                                        <button class="btn btn-sm btn-link text-danger p-0 ms-2 remove-component d-none"
                                                data-type-id="{{ $type->id_tip }}" title="Ukloni">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="qty-controls d-none mt-1" style="margin-left: 1.5rem;">
                                    <div class="d-inline-flex align-items-center border rounded" style="font-size: 0.8rem;">
                                        <button class="btn btn-sm btn-link text-dark px-2 py-0 qty-minus" data-type-id="{{ $type->id_tip }}">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <span class="px-2 qty-value fw-semibold">1</span>
                                        <button class="btn btn-sm btn-link text-dark px-2 py-0 qty-plus" data-type-id="{{ $type->id_tip }}">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="alert alert-warning py-2 px-3 mb-2 d-none" id="power-warning">
                            <small>
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                <span id="power-warning-text"></span>
                            </small>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="fw-semibold">UKUPNO:</span>
                            <span class="h4 mb-0 text-primary fw-bold" id="total-price">0.00 €</span>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="chk-assembly" value="1">
                            <label class="form-check-label small" for="chk-assembly">
                                <i class="bi bi-tools me-1"></i> Želim sastavljeno računalo <span class="text-success fw-semibold">(besplatno)</span>
                            </label>
                        </div>

                        <button class="btn btn-success w-100 mb-2" id="btn-add-to-cart" disabled>
                            <i class="bi bi-cart-plus me-2"></i>
                            Dodaj sve u košaricu
                        </button>

                        @auth
                        <button class="btn btn-outline-primary w-100" id="btn-save-config">
                            <i class="bi bi-save me-2"></i>
                            Spremi konfiguraciju
                        </button>
                        @else
                        <p class="text-muted small text-center mb-0 mt-2">
                            <a href="{{ route('login') }}">Prijavi se</a> za spremanje
                        </p>
                        @endauth
                    </div>
                </div>

                <div class="card shadow-sm mt-3">
                    <div class="card-body py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="bi bi-lightning-charge me-1"></i> Procjena:</small>
                            <span class="fw-semibold" id="total-tdp">0W</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="bi bi-plug me-1"></i> Preporučeno:</small>
                            <span class="fw-semibold text-primary" id="recommended-wattage">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="saveConfigModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Spremi konfiguraciju</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="config-name" class="form-label">Naziv konfiguracije <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="config-name" placeholder="npr. Gaming PC 2024" required>
                    <small class="text-danger d-none" id="config-name-error">Naziv konfiguracije je obavezan.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Odustani</button>
                <button type="button" class="btn btn-primary" id="confirm-save-config">Spremi</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="finishConfigModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Završi konfiguraciju</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="bi bi-check-circle text-success display-4 mb-3"></i>
                <p>Sve komponente su odabrane i spremne za košaricu.</p>
                <div class="form-check text-start mx-auto" style="max-width: 320px;">
                    <input class="form-check-input" type="checkbox" id="chk-assembly-modal" value="1">
                    <label class="form-check-label" for="chk-assembly-modal">
                        <i class="bi bi-tools me-1"></i> Želim sastavljeno računalo <span class="text-success fw-semibold">(besplatno)</span>
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Odustani</button>
                <button type="button" class="btn btn-success" id="confirm-finish-config">Dodaj u košaricu</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="aiRecommendationModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-stars me-2"></i>AI Preporuka konfiguracije
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="ai-form-section">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Budžet (EUR)</label>
                        <input type="range" class="form-range" id="ai-budget" min="300" max="3000" step="50" value="1000">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">300 €</span>
                            <span class="fw-bold text-primary fs-5" id="ai-budget-display">1000 €</span>
                            <span class="text-muted small">3000 €</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Namjena</label>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="ai-purpose" id="purpose-gaming" value="gaming" checked>
                                <label class="btn btn-outline-primary w-100 py-3 ai-purpose-label" for="purpose-gaming">
                                    <i class="bi bi-controller d-block mb-1" style="font-size: 1.5rem;"></i>
                                    Gaming
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="ai-purpose" id="purpose-office" value="office">
                                <label class="btn btn-outline-primary w-100 py-3 ai-purpose-label" for="purpose-office">
                                    <i class="bi bi-briefcase d-block mb-1" style="font-size: 1.5rem;"></i>
                                    Uredski rad
                                </label>
                            </div>
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="ai-purpose" id="purpose-content" value="content-creation">
                                <label class="btn btn-outline-primary w-100 py-3 ai-purpose-label" for="purpose-content">
                                    <i class="bi bi-camera-reels d-block mb-1" style="font-size: 1.5rem;"></i>
                                    Kreiranje sadržaja
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Metoda preporuke</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="ai-use-claude" checked>
                            <label class="form-check-label" for="ai-use-claude">
                                Koristi <strong>Claude AI</strong> za pametnije preporuke
                            </label>
                        </div>
                        <div class="form-text">Isključi za brzu preporuku algoritmom optimizacije (bez AI-a).</div>
                    </div>

                    <div class="mb-3" id="ai-free-text-section">
                        <label class="form-label fw-semibold">
                            Dodatne želje <span class="text-muted fw-normal">(opcionalno)</span>
                        </label>
                        <textarea class="form-control" id="ai-free-text" rows="2"
                                  placeholder="npr. &quot;Igram Cyberpunk i radim u Blenderu, trebam barem 32GB RAM-a&quot;"></textarea>
                        <div class="form-text">Slobodno opiši što ti treba — AI će prilagoditi preporuku.</div>
                    </div>

                    <button class="btn btn-primary w-100 btn-lg" id="ai-generate-btn">
                        <i class="bi bi-stars me-2"></i>Generiraj preporuku
                    </button>
                </div>

                <div id="ai-loading-section" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Učitavanje...</span>
                    </div>
                    <h5 class="text-primary">AI slaže tvoju konfiguraciju...</h5>
                    <p class="text-muted">Analiziramo komponente i provjeravamo kompatibilnost</p>
                    <div class="progress mx-auto" style="max-width: 300px; height: 6px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" style="width: 100%"></div>
                    </div>
                </div>

                <div id="ai-result-section" class="d-none">
                    <div class="alert alert-success d-flex align-items-center mb-3" id="ai-method-badge">
                        <i class="bi bi-stars me-2"></i>
                        <span id="ai-result-method"></span>
                    </div>

                    <div id="ai-explanation" class="alert alert-light border d-none mb-3">
                        <i class="bi bi-chat-left-text me-2 text-primary"></i>
                        <span id="ai-explanation-text"></span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Komponenta</th>
                                    <th>Proizvod</th>
                                    <th class="text-end">Cijena</th>
                                </tr>
                            </thead>
                            <tbody id="ai-result-table"></tbody>
                            <tfoot class="table-primary">
                                <tr>
                                    <th colspan="2">Ukupno</th>
                                    <th class="text-end" id="ai-result-total"></th>
                                </tr>
                                <tr class="table-light">
                                    <td colspan="2" class="text-muted">Preostali budžet</td>
                                    <td class="text-end text-success fw-semibold" id="ai-result-remaining"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="ai-result-footer" style="display: none;">
                <button type="button" class="btn btn-outline-secondary" id="ai-retry-btn">
                    <i class="bi bi-arrow-clockwise me-1"></i>Nova preporuka
                </button>
                <button type="button" class="btn btn-success btn-lg" id="ai-apply-btn">
                    <i class="bi bi-check-circle me-2"></i>Prihvati i učitaj u konfigurator
                </button>
            </div>
        </div>
    </div>
</div>

<style>

.navbar.sticky-top {
    z-index: 1050 !important;
}

.sticky-configuration {
    position: sticky;
    top: 90px;
    z-index: 1000;
    transition: top 0.3s ease;
}

@media (max-width: 991.98px) {
    .sticky-configuration {
        position: relative;
        top: 0;
    }
}


.step-item { transition: all 0.3s ease; position: relative; }
.step-item.active .step-icon { box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.25); border: 2px solid #0d6efd; }
.step-item.completed .step-icon { background-color: #198754 !important; color: white !important; }
.step-check { position: absolute; top: -5px; right: 5px; }


.product-card { transition: all 0.2s ease; cursor: pointer; border: 2px solid transparent; }
.product-card:hover { border-color: #0d6efd; transform: translateY(-2px); }
.product-card.selected { border-color: #198754; background-color: #f0fff4; }
.product-card.incompatible { opacity: 0.5; pointer-events: none; grayscale: 1; }

.config-item { cursor: pointer; }
.config-item.selected { background-color: #f8fbff; }
.config-item.selected .component-name { color: #0d6efd !important; font-weight: 600; }

.btn-check:checked + .ai-purpose-label {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: #fff !important;
}
</style>

<script src="{{ asset('js/pc-builder.js') }}"></script>
<script src="{{ asset('js/ai-recommendation.js') }}"></script>
@endsection