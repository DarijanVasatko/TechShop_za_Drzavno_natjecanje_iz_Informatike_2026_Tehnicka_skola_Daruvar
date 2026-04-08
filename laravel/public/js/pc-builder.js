(function() {
    'use strict';

    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const baseUrl = '/pc-builder';

    let steps = [];
    let currentStepIndex = 0;
    let configuration = {};
    let currentProducts = [];

    const progressSteps = document.querySelectorAll('.step-item');
    const stepTitle = document.getElementById('step-title');
    const stepCounter = document.getElementById('step-counter');
    const productsContainer = document.getElementById('products-container');
    const configurationList = document.getElementById('configuration-list');
    const totalPriceEl = document.getElementById('total-price');
    const totalTdpEl = document.getElementById('total-tdp');
    const recommendedWattageEl = document.getElementById('recommended-wattage');
    const powerWarning = document.getElementById('power-warning');
    const powerWarningText = document.getElementById('power-warning-text');
    const btnPrev = document.getElementById('btn-prev');
    const btnNext = document.getElementById('btn-next');
    const btnAddToCart = document.getElementById('btn-add-to-cart');
    const btnSaveConfig = document.getElementById('btn-save-config');
    const filterMinPrice = document.getElementById('filter-min-price');
    const filterMaxPrice = document.getElementById('filter-max-price');
    const applyFilters = document.getElementById('apply-filters');
    const chkAssembly = document.getElementById('chk-assembly');
    const chkAssemblyModal = document.getElementById('chk-assembly-modal');

    document.addEventListener('DOMContentLoaded', init);

    function init() {
        progressSteps.forEach((el, index) => {
            steps.push({
                slug: el.dataset.step,
                typeId: parseInt(el.dataset.typeId),
                required: el.dataset.required === 'true',
                element: el
            });
        });

        loadConfiguration().then(() => {
            loadStep(0);
        });

        setupEventListeners();
    }

    function setupEventListeners() {
        progressSteps.forEach((el, index) => {
            el.addEventListener('click', () => {
                loadStep(index);
            });
        });

        btnPrev.addEventListener('click', () => {
            if (currentStepIndex > 0) {
                loadStep(currentStepIndex - 1);
            }
        });

        btnNext.addEventListener('click', () => {
            if (currentStepIndex < steps.length - 1) {
                loadStep(currentStepIndex + 1);
            } else {
                const hasItems = Object.values(configuration.items || {}).some(item => item !== null);
                if (hasItems) {
                    const modal = new bootstrap.Modal(document.getElementById('finishConfigModal'));
                    modal.show();
                } else {
                    showToast('Konfiguracija je prazna. Odaberite barem jednu komponentu.', 'error');
                }
            }
        });

        if (chkAssembly && chkAssemblyModal) {
            chkAssembly.addEventListener('change', () => { chkAssemblyModal.checked = chkAssembly.checked; });
            chkAssemblyModal.addEventListener('change', () => { chkAssembly.checked = chkAssemblyModal.checked; });
        }

        document.getElementById('confirm-finish-config').addEventListener('click', () => {
            if (chkAssembly && chkAssemblyModal) {
                chkAssembly.checked = chkAssemblyModal.checked;
            }
            bootstrap.Modal.getInstance(document.getElementById('finishConfigModal')).hide();
            addAllToCart();
        });

        if (applyFilters) {
            applyFilters.addEventListener('click', () => {
                loadProducts(steps[currentStepIndex].typeId);
            });
        }

        if (filterMinPrice) {
            filterMinPrice.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') loadProducts(steps[currentStepIndex].typeId);
            });
        }

        if (filterMaxPrice) {
            filterMaxPrice.addEventListener('keypress', (e) => {
                if (e.key === 'Enter') loadProducts(steps[currentStepIndex].typeId);
            });
        }

        btnAddToCart.addEventListener('click', addAllToCart);

        if (btnSaveConfig) {
            btnSaveConfig.addEventListener('click', () => {
                const modal = new bootstrap.Modal(document.getElementById('saveConfigModal'));
                modal.show();
            });

            document.getElementById('confirm-save-config').addEventListener('click', saveConfiguration);

            document.getElementById('config-name').addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const errorEl = document.getElementById('config-name-error');
                if (errorEl) errorEl.classList.add('d-none');
            });
        }

        document.getElementById('configuration-list').addEventListener('click', (e) => {
            const removeBtn = e.target.closest('.remove-component');
            if (removeBtn) {
                e.stopPropagation();
                removeComponent(parseInt(removeBtn.dataset.typeId));
                return;
            }

            const qtyMinus = e.target.closest('.qty-minus');
            if (qtyMinus) {
                e.stopPropagation();
                changeQuantity(parseInt(qtyMinus.dataset.typeId), -1);
                return;
            }

            const qtyPlus = e.target.closest('.qty-plus');
            if (qtyPlus) {
                e.stopPropagation();
                changeQuantity(parseInt(qtyPlus.dataset.typeId), 1);
                return;
            }

            const configItem = e.target.closest('.config-item');
            if (configItem) {
                const slug = configItem.dataset.typeSlug;
                const index = steps.findIndex(s => s.slug === slug);
                if (index !== -1) loadStep(index);
            }
        });
    }

    function isStepAccessible(index) {
        for (let i = 0; i < index; i++) {
            if (steps[i].required && !configuration.items?.[steps[i].slug]) {
                return false;
            }
        }
        return true;
    }

    async function loadConfiguration() {
        try {
            const response = await fetch(`${baseUrl}/configuration`, {
                headers: { 'Accept': 'application/json' }
            });
            configuration = await response.json();
            updateConfigurationUI();
        } catch (error) {
            console.error('Error loading configuration:', error);
        }
    }

    window.refreshConfiguration = async function() {
        await loadConfiguration();
        await loadStep(0);
    };

    async function loadStep(index) {
        currentStepIndex = index;
        const step = steps[index];

        updateProgressUI();

        btnPrev.disabled = index === 0;
        btnNext.textContent = index === steps.length - 1 ? 'Završi' : 'Dalje';
        btnNext.innerHTML = index === steps.length - 1
            ? 'Završi <i class="bi bi-check-circle ms-1"></i>'
            : 'Dalje <i class="bi bi-arrow-right ms-1"></i>';

        await loadProducts(step.typeId);
    }

    async function loadProducts(typeId) {
        const step = steps.find(s => s.typeId === typeId);

        productsContainer.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Učitavanje...</span>
                </div>
                <p class="text-muted mt-2">Učitavanje komponenti...</p>
            </div>
        `;

        const typeEl = progressSteps[currentStepIndex];
        const typeName = typeEl.querySelector('small')?.textContent || step.slug;
        const typeIcon = typeEl.querySelector('.step-icon i').className;
        stepTitle.innerHTML = `<i class="${typeIcon} text-primary me-2"></i>Odaberi ${typeName}`;
        stepCounter.textContent = `Korak ${currentStepIndex + 1} od ${steps.length}`;

        try {
            let url = `${baseUrl}/compatible-products/${typeId}`;
            const params = new URLSearchParams();

            if (filterMinPrice && filterMinPrice.value) params.append('min_price', filterMinPrice.value);
            if (filterMaxPrice && filterMaxPrice.value) params.append('max_price', filterMaxPrice.value);

            if (params.toString()) url += '?' + params.toString();

            const response = await fetch(url, {
                headers: { 'Accept': 'application/json' }
            });

            const data = await response.json();
            currentProducts = data.products;

            renderProducts(data.products, typeId);
        } catch (error) {
            console.error('Error loading products:', error);
            productsContainer.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Greška pri učitavanju proizvoda. Pokušajte ponovno.
                </div>
            `;
        }
    }

    function renderProducts(products, typeId) {
        if (!products || products.length === 0) {
            productsContainer.innerHTML = `
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3">Nema dostupnih proizvoda</h5>
                    <p class="text-muted">Pokušajte promijeniti filtere ili odabrati druge komponente.</p>
                </div>
            `;
            return;
        }

        const step = steps.find(s => s.typeId === typeId);
        const selectedItem = configuration.items?.[step.slug];
        const selectedProductId = selectedItem?.proizvod?.Proizvod_ID;

        let html = '<div class="row">';

        products.forEach(product => {
            const isSelected = product.Proizvod_ID === selectedProductId;
            const spec = product.pc_spec || {};

            html += `
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card product-card h-100 ${isSelected ? 'selected' : ''}"
                         data-product-id="${product.Proizvod_ID}"
                         data-type-id="${typeId}">
                        <div class="card-body">
                            ${product.slika_url && !product.slika_url.includes('no-image') ? `
                                <img src="${product.slika_url}" class="img-fluid mb-2 rounded"
                                     alt="${product.Naziv}" style="max-height: 120px; object-fit: contain; width: 100%;">
                            ` : `
                                <div class="bg-light rounded d-flex align-items-center justify-content-center mb-2" style="height: 120px;">
                                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                </div>
                            `}

                            <h6 class="card-title mb-1">${product.Naziv}</h6>
                            <p class="card-text small text-muted mb-2">${product.KratkiOpis || ''}</p>

                            <div class="mb-2">
                                ${spec.socket_type ? `<span class="badge bg-secondary me-1">${spec.socket_type}</span>` : ''}
                                ${spec.ram_type ? `<span class="badge bg-info me-1">${spec.ram_type}</span>` : ''}
                                ${spec.form_factor ? `<span class="badge bg-warning text-dark me-1">${spec.form_factor}</span>` : ''}
                                ${spec.tdp ? `<span class="badge bg-danger">${spec.tdp}W TDP</span>` : ''}
                                ${spec.wattage ? `<span class="badge bg-success">${spec.wattage}W</span>` : ''}
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 text-primary fw-bold">${parseFloat(product.Cijena).toFixed(2)} €</span>
                                <button class="btn btn-sm ${isSelected ? 'btn-success' : 'btn-outline-primary'} select-product-btn">
                                    ${isSelected ? '<i class="bi bi-check-circle me-1"></i> Odabrano' : '<i class="bi bi-plus-circle me-1"></i> Odaberi'}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        html += '</div>';
        productsContainer.innerHTML = html;

        document.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', () => {
                const productId = parseInt(card.dataset.productId);
                const typeId = parseInt(card.dataset.typeId);
                selectProduct(productId, typeId);
            });
        });
    }

    async function selectProduct(productId, typeId) {
        try {
            const response = await fetch(`${baseUrl}/add-component`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({
                    proizvod_id: productId,
                    tip_proizvoda_id: typeId
                })
            });

            const data = await response.json();

            if (data.success) {
                configuration = data.configuration;
                updateConfigurationUI();
                renderProducts(currentProducts, typeId);

                if (data.removed_components && data.removed_components.length > 0) {
                    const names = data.removed_components.map(slug => {
                        const step = steps.find(s => s.slug === slug);
                        if (step) {
                            const label = step.element.querySelector('small');
                            return label ? label.textContent.trim() : slug;
                        }
                        return slug;
                    });
                    showToast(`Uklonjene nekompatibilne komponente: ${names.join(', ')}`, 'warning');
                } else {
                    showToast('Komponenta dodana!', 'success');
                }
            }
        } catch (error) {
            console.error('Error selecting product:', error);
            showToast('Greška pri odabiru komponente.', 'error');
        }
    }

    async function changeQuantity(typeId, delta) {
        const step = steps.find(s => s.typeId === typeId);
        if (!step) return;

        const item = configuration.items?.[step.slug];
        if (!item) return;

        const newQty = (item.kolicina || 1) + delta;
        if (newQty < 1 || newQty > 10) return;

        try {
            const response = await fetch(`${baseUrl}/update-quantity`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({
                    tip_proizvoda_id: typeId,
                    kolicina: newQty
                })
            });

            const data = await response.json();
            if (data.success) {
                configuration = data.configuration;
                updateConfigurationUI();
            }
        } catch (error) {
            console.error('Error updating quantity:', error);
        }
    }

    async function removeComponent(typeId) {
        try {
            const response = await fetch(`${baseUrl}/remove-component/${typeId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                }
            });

            const data = await response.json();

            if (data.success) {
                configuration = data.configuration;
                updateConfigurationUI();

                const step = steps[currentStepIndex];
                if (step.typeId === typeId) {
                    loadProducts(typeId);
                }

                showToast('Komponenta uklonjena.', 'info');
            }
        } catch (error) {
            console.error('Error removing component:', error);
            showToast('Greška pri uklanjanju komponente.', 'error');
        }
    }

    function updateConfigurationUI() {
        const items = configuration.items || {};

        document.querySelectorAll('.config-item').forEach(el => {
            const slug = el.dataset.typeSlug;
            const item = items[slug];
            const productNameEl = el.querySelector('.component-product');
            const priceEl = el.querySelector('.component-price');
            const removeBtn = el.querySelector('.remove-component');
            const qtyControls = el.querySelector('.qty-controls');
            const qtyValue = el.querySelector('.qty-value');

            if (item && item.proizvod) {
                const qty = item.kolicina || 1;
                const lineTotal = (parseFloat(item.cijena) * qty).toFixed(2);

                productNameEl.textContent = item.proizvod.Naziv;
                productNameEl.classList.remove('text-muted');
                productNameEl.classList.add('text-dark');
                priceEl.textContent = lineTotal + ' €';
                priceEl.classList.add('text-success');
                el.classList.add('selected');
                removeBtn.classList.remove('d-none');

                qtyControls.classList.remove('d-none');
                qtyValue.textContent = qty;
            } else {
                productNameEl.textContent = 'Nije odabrano';
                productNameEl.classList.add('text-muted');
                productNameEl.classList.remove('text-dark');
                priceEl.textContent = '-';
                priceEl.classList.remove('text-success');
                el.classList.remove('selected');
                removeBtn.classList.add('d-none');

                qtyControls.classList.add('d-none');
                qtyValue.textContent = '1';
            }
        });

        steps.forEach((step, index) => {
            const el = step.element;
            const icon = el.querySelector('.step-icon');
            const label = el.querySelector('small');

            if (items[step.slug]) {
                el.classList.add('completed');
                icon.classList.remove('bg-light', 'text-muted', 'bg-primary', 'text-white');
                icon.classList.add('bg-success', 'text-white');
                if (label) label.classList.add('text-success');
            } else {
                el.classList.remove('completed');
                if (index === currentStepIndex) {
                    icon.classList.remove('bg-light', 'text-muted', 'bg-success');
                    icon.classList.add('bg-primary', 'text-white');
                    if (label) {
                        label.classList.remove('text-success', 'text-muted');
                        label.classList.add('text-primary');
                    }
                } else {
                    icon.classList.remove('bg-primary', 'text-white', 'bg-success');
                    icon.classList.add('bg-light', 'text-muted');
                    if (label) {
                        label.classList.remove('text-success', 'text-primary');
                        label.classList.add('text-muted');
                    }
                }
            }
        });

        totalPriceEl.textContent = parseFloat(configuration.ukupna_cijena || 0).toFixed(2) + ' €';
        totalTdpEl.textContent = (configuration.total_tdp || 0) + 'W';
        recommendedWattageEl.textContent = (configuration.recommended_wattage || 0) + 'W';

        const psuItem = items['napajanje'];
        if (psuItem && psuItem.proizvod && psuItem.proizvod.pc_spec) {
            const psuWattage = psuItem.proizvod.pc_spec.wattage || 0;
            const recommendedWattage = configuration.recommended_wattage || 0;

            if (psuWattage < recommendedWattage) {
                powerWarning.classList.remove('d-none');
                powerWarningText.textContent = `Odabrano napajanje (${psuWattage}W) može biti nedovoljno. Preporučeno: ${recommendedWattage}W`;
            } else {
                powerWarning.classList.add('d-none');
            }
        } else {
            powerWarning.classList.add('d-none');
        }

        const hasItems = Object.values(items).some(item => item !== null);
        btnAddToCart.disabled = !hasItems;
    }

    function updateProgressUI() {
        steps.forEach((step, index) => {
            const el = step.element;
            const icon = el.querySelector('.step-icon');
            const label = el.querySelector('small');

            if (index === currentStepIndex) {
                el.classList.add('active');
                if (!el.classList.contains('completed')) {
                    icon.classList.remove('bg-light', 'text-muted');
                    icon.classList.add('bg-primary', 'text-white');
                }
                if (label) {
                    label.classList.remove('text-muted');
                    label.classList.add('fw-semibold', 'text-primary');
                }
            } else {
                el.classList.remove('active');
                if (!el.classList.contains('completed')) {
                    icon.classList.remove('bg-primary', 'text-white');
                    icon.classList.add('bg-light', 'text-muted');
                }
                if (label) {
                    label.classList.remove('fw-semibold', 'text-primary');
                    if (!el.classList.contains('completed')) {
                        label.classList.add('text-muted');
                    }
                }
            }
        });
    }

    async function addAllToCart() {
        btnAddToCart.disabled = true;
        btnAddToCart.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Dodavanje...';

        try {
            const wantAssembly = chkAssembly ? chkAssembly.checked : false;
            const body = new FormData();
            if (wantAssembly) body.append('sastavljanje', '1');

            const response = await fetch(`${baseUrl}/add-to-cart`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: body
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');

                const badge = document.getElementById('cart-count');
                if (badge) {
                    badge.textContent = data.cartCount;
                    badge.classList.remove('d-none');
                }
            } else {
                showToast(data.message || 'Greška pri dodavanju u košaricu.', 'error');
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            showToast('Greška pri dodavanju u košaricu.', 'error');
        } finally {
            btnAddToCart.disabled = false;
            btnAddToCart.innerHTML = '<i class="bi bi-cart-plus me-2"></i>Dodaj sve u košaricu';
        }
    }

    async function saveConfiguration() {
        const configInput = document.getElementById('config-name');
        const configName = configInput.value.trim();
        const errorEl = document.getElementById('config-name-error');

        if (!configName) {
            configInput.classList.add('is-invalid');
            if (errorEl) errorEl.classList.remove('d-none');
            configInput.focus();
            return;
        }

        configInput.classList.remove('is-invalid');
        if (errorEl) errorEl.classList.add('d-none');

        try {
            const response = await fetch(`${baseUrl}/save`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({ naziv: configName })
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                bootstrap.Modal.getInstance(document.getElementById('saveConfigModal')).hide();
            } else {
                showToast('Greška pri spremanju konfiguracije.', 'error');
            }
        } catch (error) {
            console.error('Error saving configuration:', error);
            showToast('Greška pri spremanju konfiguracije.', 'error');
        }
    }

    function showToast(message, type = 'info') {
        const toastEl = document.getElementById('globalToast');
        if (!toastEl) return;

        const body = toastEl.querySelector('.toast-body');
        const icon = toastEl.querySelector('.toast-header i');

        const map = {
            success: { color: 'bg-success', icon: 'bi-check-circle' },
            error: { color: 'bg-danger', icon: 'bi-exclamation-triangle' },
            warning: { color: 'bg-warning', icon: 'bi-exclamation-triangle' },
            info: { color: 'bg-primary', icon: 'bi-info-circle' }
        };

        toastEl.classList.remove('bg-success', 'bg-danger', 'bg-primary', 'bg-warning', 'text-dark', 'text-white');
        toastEl.classList.add(map[type].color);
        toastEl.classList.add(type === 'warning' ? 'text-dark' : 'text-white');
        icon.className = `bi ${map[type].icon} me-2`;
        body.textContent = message;

        const delay = type === 'warning' ? 5000 : 3500;
        const toast = new bootstrap.Toast(toastEl, { delay: delay });
        toast.show();
    }
})();
