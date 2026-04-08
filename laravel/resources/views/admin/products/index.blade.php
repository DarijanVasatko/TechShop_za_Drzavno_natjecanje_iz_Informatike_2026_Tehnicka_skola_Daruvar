@extends('layouts.admin')

@section('title', 'Proizvodi — TechShop Admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-box-seam me-2"></i> Proizvodi
            </h2>
            <p class="text-muted mb-0">
                Pronađeno proizvoda: {{ $products->count() }}
            </p>
        </div>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productCreateModal">
            <i class="bi bi-plus-lg me-1"></i> Novi proizvod
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Greška pri spremanju:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Pretraži</label>
                    <input type="text" name="q" class="form-control" placeholder="Naziv ili šifra proizvoda..."
                        value="{{ request('q') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Konfigurator</label>
                    <select name="konfigurator" class="form-select">
                        <option value="">Svi proizvodi</option>
                        <option value="1" @selected(request('konfigurator') === '1')>Samo konfigurator</option>
                        <option value="0" @selected(request('konfigurator') === '0')>Bez konfiguratora</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button class="btn btn-outline-secondary flex-fill" type="submit">
                        <i class="bi bi-search me-1"></i> Filtriraj
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light border flex-fill">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light sticky-top">
                    <tr>
                        <th style="width: 70px;">ID</th>
                        <th style="width: 80px;">Slika</th>
                        <th>Naziv i Brend</th>
                        <th>Šifra</th>
                        <th>Kategorija / Tip</th>
                        <th>Spec</th>
                        <th class="text-end">Cijena</th>
                        <th class="text-center">Zaliha</th>
                        <th class="text-end" style="width: 160px;">Akcije</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        @php $spec = $product->pcSpec; $tip = $product->tip; @endphp
                        <tr>
                            <td>#{{ $product->Proizvod_ID }}</td>
                            <td>
                                @if($product->Slika)
                                    <img src="{{ $product->slika_url }}" alt="{{ $product->Naziv }}"
                                        class="rounded" style="width: 56px; height: 56px; object-fit: cover;">
                                @else
                                    <div class="bg-light border rounded d-flex align-items-center justify-content-center"
                                        style="width: 56px; height: 56px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $product->Naziv }}</div>
                                <div class="small text-primary">{{ $product->brand->name ?? 'Nepoznat brend' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-secondary-subtle text-dark border">{{ $product->sifra }}</span>
                            </td>
                            <td>
                                <div>{{ $product->kategorija->ImeKategorija ?? '—' }}</div>
                                @if($tip)
                                    <span class="badge {{ $tip->konfigurator ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-light text-dark border' }}">
                                        @if($tip->konfigurator && $tip->ikona)
                                            <i class="bi {{ $tip->ikona }} me-1"></i>
                                        @endif
                                        {{ $tip->naziv_tip }}
                                    </span>
                                @endif
                            </td>
                            <td class="small text-muted">
                                @if($spec)
                                    @if($spec->socket_type) <span class="badge bg-secondary-subtle text-dark border me-1">{{ $spec->socket_type }}</span> @endif
                                    @if($spec->ram_type)    <span class="badge bg-secondary-subtle text-dark border me-1">{{ $spec->ram_type }}</span> @endif
                                    @if($spec->form_factor) <span class="badge bg-secondary-subtle text-dark border me-1">{{ $spec->form_factor }}</span> @endif
                                    @if($spec->wattage)     <span class="badge bg-secondary-subtle text-dark border me-1">{{ $spec->wattage }}W</span> @endif
                                    @if($spec->tdp)         <span class="badge bg-secondary-subtle text-dark border me-1">TDP {{ $spec->tdp }}W</span> @endif
                                @endif
                            </td>
                            <td class="text-end">{{ number_format($product->Cijena, 2, ',', '.') }} €</td>
                            <td class="text-center">
                                @php $stock = $product->StanjeNaSkladistu; @endphp
                                @if($stock <= 0)
                                    <span class="badge bg-danger">Nema</span>
                                @elseif($stock <= 5)
                                    <span class="badge bg-warning text-dark">Niska ({{ $stock }})</span>
                                @else
                                    <span class="badge bg-success">Na zalihi ({{ $stock }})</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary me-1 editProductBtn"
                                    data-bs-toggle="modal" data-bs-target="#editProductModal"
                                    data-id="{{ $product->Proizvod_ID }}"
                                    data-sifra="{{ $product->sifra }}"
                                    data-naziv="{{ $product->Naziv }}"
                                    data-opis="{{ $product->Opis }}"
                                    data-detaljniopis="{{ $product->DetaljniOpis }}"
                                    data-cijena="{{ $product->Cijena }}"
                                    data-zaliha="{{ $product->StanjeNaSkladistu }}"
                                    data-kategorija="{{ $product->getAttributeValue('kategorija') }}"
                                    data-tip="{{ $product->tip_proizvoda_id }}"
                                    data-brand="{{ $product->brand_id }}"
                                    data-slika="{{ $product->slika_url }}"
                                    data-socket="{{ $spec?->socket_type }}"
                                    data-ram="{{ $spec?->ram_type }}"
                                    data-form="{{ $spec?->form_factor }}"
                                    data-wattage="{{ $spec?->wattage }}"
                                    data-tdp="{{ $spec?->tdp }}">
                                    <i class="bi bi-pencil-square me-1"></i> Uredi
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger deleteProductBtn"
                                    data-bs-toggle="modal" data-bs-target="#deleteProductModal"
                                    data-id="{{ $product->Proizvod_ID }}" data-name="{{ $product->Naziv }}">
                                    <i class="bi bi-trash me-1"></i> Obriši
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Nema proizvoda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="productCreateModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content" style="max-height: 90vh;">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                      style="display:flex; flex-direction:column; overflow:hidden; max-height:inherit;">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold"><i class="bi bi-plus-lg me-2"></i> Dodaj novi proizvod</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3" style="overflow-y: auto;">
                        <div class="col-md-4">
                            <label class="form-label">Šifra <span class="text-danger">*</span></label>
                            <input type="text" name="sifra" class="form-control" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Naziv <span class="text-danger">*</span></label>
                            <input type="text" name="Naziv" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Kratki opis</label>
                            <textarea name="Opis" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Detaljan opis</label>
                            <textarea name="DetaljniOpis" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cijena (€) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="Cijena" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Zaliha <span class="text-danger">*</span></label>
                            <input type="number" name="StanjeNaSkladistu" class="form-control" value="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Brend</label>
                            <select name="brand_id" class="form-select">
                                <option value="">Odaberi brend...</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategorija <span class="text-danger">*</span></label>
                            <select name="kategorija" id="create_kategorija" class="form-select category-select" required>
                                <option value="">Odaberi kategoriju...</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id_kategorija }}">{{ $cat->ImeKategorija }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tip proizvoda</label>
                            <select name="tip_proizvoda_id" id="create_tip" class="form-select type-select">
                                <option value="">Prvo odaberi kategoriju</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id_tip }}"
                                        data-category="{{ $type->kategorija_id }}"
                                        data-konfigurator="{{ $type->konfigurator ? '1' : '0' }}"
                                        data-slug="{{ $type->slug }}">
                                        {{ $type->naziv_tip }}
                                        @if($type->konfigurator) (konfigurator) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 spec-section" id="create_spec_section" style="display:none;">
                            <hr class="my-1">
                            <p class="form-label fw-semibold mb-2 text-primary">
                                <i class="bi bi-sliders me-1"></i> Specifikacije komponente (konfigurator)
                            </p>
                            <div class="row g-3">
                                <div class="col-md-4 spec-field" data-spec="socket_type" style="display:none;">
                                    <label class="form-label">Socket tip</label>
                                    <select name="socket_type" class="form-select">
                                        <option value="">—</option>
                                        <option>LGA1700</option>
                                        <option>AM4</option>
                                        <option>AM5</option>
                                    </select>
                                </div>
                                <div class="col-md-4 spec-field" data-spec="ram_type" style="display:none;">
                                    <label class="form-label">Tip RAM-a</label>
                                    <select name="ram_type" class="form-select">
                                        <option value="">—</option>
                                        <option>DDR4</option>
                                        <option>DDR5</option>
                                    </select>
                                </div>
                                <div class="col-md-4 spec-field" data-spec="form_factor" style="display:none;">
                                    <label class="form-label">Form factor</label>
                                    <select name="form_factor" class="form-select">
                                        <option value="">—</option>
                                        <option>ATX</option>
                                        <option>mATX</option>
                                        <option>ITX</option>
                                    </select>
                                </div>
                                <div class="col-md-4 spec-field" data-spec="wattage" style="display:none;">
                                    <label class="form-label">Snaga (W)</label>
                                    <input type="number" name="wattage" class="form-control" min="1" placeholder="npr. 650">
                                </div>
                                <div class="col-md-4 spec-field" data-spec="tdp" style="display:none;">
                                    <label class="form-label">TDP (W)</label>
                                    <input type="number" name="tdp" class="form-control" min="1" placeholder="npr. 65">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Slika</label>
                            <input type="file" name="slika" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Zatvori</button>
                        <button type="submit" class="btn btn-primary">Spremi proizvod</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
            <div class="modal-content" style="max-height: 90vh;">
                <form id="editProductForm" method="POST" enctype="multipart/form-data"
                      style="display:flex; flex-direction:column; overflow:hidden; max-height:inherit;">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i> Uredi proizvod</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body row g-3" style="overflow-y: auto;">
                        <div class="col-md-4">
                            <label class="form-label">Šifra</label>
                            <input type="text" name="sifra" id="edit_sifra" class="form-control">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Naziv</label>
                            <input type="text" name="Naziv" id="edit_naziv" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Kratki opis</label>
                            <textarea name="Opis" id="edit_opis" rows="2" class="form-control"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Detaljan opis</label>
                            <textarea name="DetaljniOpis" id="edit_detaljniopis" rows="4" class="form-control"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cijena (€)</label>
                            <input type="number" step="0.01" name="Cijena" id="edit_cijena" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Zaliha</label>
                            <input type="number" name="StanjeNaSkladistu" id="edit_zaliha" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Brend</label>
                            <select name="brand_id" id="edit_brend" class="form-select">
                                <option value="">Bez brenda</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategorija</label>
                            <select name="kategorija" id="edit_kategorija" class="form-select category-select">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id_kategorija }}">{{ $cat->ImeKategorija }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tip proizvoda</label>
                            <select name="tip_proizvoda_id" id="edit_tip" class="form-select type-select">
                                <option value="">Bez tipa</option>
                                @foreach($types as $type)
                                    <option value="{{ $type->id_tip }}"
                                        data-category="{{ $type->kategorija_id }}"
                                        data-konfigurator="{{ $type->konfigurator ? '1' : '0' }}"
                                        data-slug="{{ $type->slug }}">
                                        {{ $type->naziv_tip }}
                                        @if($type->konfigurator) (konfigurator) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12 spec-section" id="edit_spec_section" style="display:none;">
                            <hr class="my-1">
                            <p class="form-label fw-semibold mb-2 text-primary">
                                <i class="bi bi-sliders me-1"></i> Specifikacije komponente (konfigurator)
                            </p>
                            <div class="row g-3">
                                <div class="col-md-4 spec-field" data-spec="socket_type" style="display:none;">
                                    <label class="form-label">Socket tip</label>
                                    <select name="socket_type" id="edit_socket" class="form-select">
                                        <option value="">—</option>
                                        <option>LGA1700</option>
                                        <option>AM4</option>
                                        <option>AM5</option>
                                    </select>
                                </div>
                                <div class="col-md-4 spec-field" data-spec="ram_type" style="display:none;">
                                    <label class="form-label">Tip RAM-a</label>
                                    <select name="ram_type" id="edit_ram" class="form-select">
                                        <option value="">—</option>
                                        <option>DDR4</option>
                                        <option>DDR5</option>
                                    </select>
                                </div>
                                <div class="col-md-4 spec-field" data-spec="form_factor" style="display:none;">
                                    <label class="form-label">Form factor</label>
                                    <select name="form_factor" id="edit_form" class="form-select">
                                        <option value="">—</option>
                                        <option>ATX</option>
                                        <option>mATX</option>
                                        <option>ITX</option>
                                    </select>
                                </div>
                                <div class="col-md-4 spec-field" data-spec="wattage" style="display:none;">
                                    <label class="form-label">Snaga (W)</label>
                                    <input type="number" name="wattage" id="edit_wattage" class="form-control" min="1">
                                </div>
                                <div class="col-md-4 spec-field" data-spec="tdp" style="display:none;">
                                    <label class="form-label">TDP (W)</label>
                                    <input type="number" name="tdp" id="edit_tdp" class="form-control" min="1">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 d-flex gap-3 align-items-center">
                            <div class="flex-grow-1">
                                <label class="form-label">Nova slika</label>
                                <input type="file" name="slika" class="form-control">
                            </div>
                            <img id="edit_preview" src="" class="border rounded" style="width: 80px; height: 80px; object-fit: cover;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Zatvori</button>
                        <button type="submit" class="btn btn-primary">Spremi promjene</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteProductForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title text-danger fw-bold"><i class="bi bi-exclamation-triangle me-2"></i> Potvrda brisanja</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Jesi siguran da želiš obrisati:</p>
                        <p class="fw-bold text-primary" id="deleteProductName"></p>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Odustani</button>
                        <button type="submit" class="btn btn-danger">Obriši</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    const SPEC_MAP = {
        'cpu':           ['socket_type', 'tdp'],
        'maticna-ploca': ['socket_type', 'ram_type', 'form_factor'],
        'ram':           ['ram_type'],
        'gpu':           ['tdp'],
        'storage':       [],
        'napajanje':     ['wattage'],
        'kuciste':       ['form_factor'],
    };

    function applySpecVisibility(prefix, isKonfigurator, slug) {
        const section = document.getElementById(prefix + '_spec_section');
        if (!section) return;

        const fields = section.querySelectorAll('.spec-field');
        const relevant = (isKonfigurator && slug) ? (SPEC_MAP[slug] ?? []) : [];

        section.style.display = (isKonfigurator && relevant.length > 0) ? '' : 'none';

        fields.forEach(field => {
            field.style.display = relevant.includes(field.dataset.spec) ? '' : 'none';
        });
    }

    function getSelectedTypeInfo(selectEl) {
        const opt = selectEl.selectedOptions[0];
        if (!opt || !opt.value) return { konfigurator: false, slug: '' };
        return {
            konfigurator: opt.dataset.konfigurator === '1',
            slug: opt.dataset.slug || '',
        };
    }

    function setupCategoryFilter(catSelectId, typeSelectId, specPrefix) {
        const catSelect = document.getElementById(catSelectId);
        const typeSelect = document.getElementById(typeSelectId);
        if (!catSelect || !typeSelect) return;

        const allOptions = Array.from(typeSelect.querySelectorAll('option[data-category]'));

        catSelect.addEventListener('change', function() {
            const selectedCat = this.value;
            typeSelect.value = "";

            allOptions.forEach(opt => {
                opt.style.display = (opt.dataset.category === selectedCat || selectedCat === "") ? 'block' : 'none';
            });

            applySpecVisibility(specPrefix, false, '');
        });

        typeSelect.addEventListener('change', function() {
            const info = getSelectedTypeInfo(this);
            applySpecVisibility(specPrefix, info.konfigurator, info.slug);
        });
    }

    setupCategoryFilter('create_kategorija', 'create_tip', 'create');
    setupCategoryFilter('edit_kategorija', 'edit_tip', 'edit');

    document.querySelectorAll('.editProductBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            document.getElementById('editProductForm').action = `/admin/products/${id}`;

            document.getElementById('edit_sifra').value = this.dataset.sifra;
            document.getElementById('edit_naziv').value = this.dataset.naziv;
            document.getElementById('edit_opis').value = this.dataset.opis || '';
            document.getElementById('edit_detaljniopis').value = this.dataset.detaljniopis || '';
            document.getElementById('edit_cijena').value = this.dataset.cijena;
            document.getElementById('edit_zaliha').value = this.dataset.zaliha;
            document.getElementById('edit_kategorija').value = this.dataset.kategorija;
            document.getElementById('edit_brend').value = this.dataset.brand || '';
            document.getElementById('edit_preview').src =
                this.dataset.slika || "{{ asset('img/no-image.svg') }}";

            document.getElementById('edit_kategorija').dispatchEvent(new Event('change'));
            setTimeout(() => {
                document.getElementById('edit_tip').value = this.dataset.tip || '';
                const tipInfo = getSelectedTypeInfo(document.getElementById('edit_tip'));
                applySpecVisibility('edit', tipInfo.konfigurator, tipInfo.slug);

                const socketEl  = document.getElementById('edit_socket');
                const ramEl     = document.getElementById('edit_ram');
                const formEl    = document.getElementById('edit_form');
                const wattageEl = document.getElementById('edit_wattage');
                const tdpEl     = document.getElementById('edit_tdp');

                if (socketEl)  socketEl.value  = this.dataset.socket  || '';
                if (ramEl)     ramEl.value     = this.dataset.ram     || '';
                if (formEl)    formEl.value    = this.dataset.form    || '';
                if (wattageEl) wattageEl.value = this.dataset.wattage || '';
                if (tdpEl)     tdpEl.value     = this.dataset.tdp     || '';
            }, 50);
        });
    });

    document.querySelectorAll('.deleteProductBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.getElementById('deleteProductForm').action = `/admin/products/${this.dataset.id}`;
            document.getElementById('deleteProductName').textContent = this.dataset.name;
        });
    });
})();
</script>
@endpush
