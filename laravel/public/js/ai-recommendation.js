(function() {
    'use strict';

    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const baseUrl = '/pc-builder';

    const budgetSlider = document.getElementById('ai-budget');
    const budgetDisplay = document.getElementById('ai-budget-display');
    const generateBtn = document.getElementById('ai-generate-btn');
    const applyBtn = document.getElementById('ai-apply-btn');
    const retryBtn = document.getElementById('ai-retry-btn');

    const formSection = document.getElementById('ai-form-section');
    const loadingSection = document.getElementById('ai-loading-section');
    const resultSection = document.getElementById('ai-result-section');
    const resultFooter = document.getElementById('ai-result-footer');

    const useClaudeToggle = document.getElementById('ai-use-claude');
    const freeTextSection = document.getElementById('ai-free-text-section');

    let lastRecommendation = null;

    document.addEventListener('DOMContentLoaded', init);

    function init() {
        if (!budgetSlider) return;

        budgetSlider.addEventListener('input', () => {
            budgetDisplay.textContent = budgetSlider.value + ' €';
        });

        useClaudeToggle.addEventListener('change', () => {
            freeTextSection.style.display = useClaudeToggle.checked ? '' : 'none';
        });

        generateBtn.addEventListener('click', generateRecommendation);
        applyBtn.addEventListener('click', applyRecommendation);
        retryBtn.addEventListener('click', showForm);

        document.getElementById('aiRecommendationModal').addEventListener('hidden.bs.modal', showForm);
    }

    function showForm() {
        formSection.classList.remove('d-none');
        loadingSection.classList.add('d-none');
        resultSection.classList.add('d-none');
        resultFooter.style.display = 'none';
    }

    function showLoading() {
        formSection.classList.add('d-none');
        loadingSection.classList.remove('d-none');
        resultSection.classList.add('d-none');
        resultFooter.style.display = 'none';
    }

    function showResult() {
        formSection.classList.add('d-none');
        loadingSection.classList.add('d-none');
        resultSection.classList.remove('d-none');
        resultFooter.style.display = 'flex';
    }

    async function generateRecommendation() {
        const budget = parseFloat(budgetSlider.value);
        const purpose = document.querySelector('input[name="ai-purpose"]:checked').value;
        const freeText = document.getElementById('ai-free-text').value.trim();
        const useAi = useClaudeToggle.checked;

        showLoading();

        try {
            const response = await fetch(`${baseUrl}/ai-recommendation`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({
                    budget: budget,
                    purpose: purpose,
                    free_text: freeText,
                    use_ai: useAi
                })
            });

            if (response.status === 429) {
                showForm();
                showToast('Previše zahtjeva. Pokušajte ponovno za nekoliko minuta.', 'error');
                return;
            }

            const data = await response.json();

            if (data.success && data.recommendation) {
                lastRecommendation = data.recommendation;
                renderResult(data.recommendation);
                showResult();
            } else {
                showForm();
                showToast(data.message || 'Greška pri generiranju preporuke.', 'error');
            }
        } catch (error) {
            console.error('AI recommendation error:', error);
            showForm();
            showToast('Greška pri komunikaciji sa serverom.', 'error');
        }
    }

    function renderResult(recommendation) {
        const methodEl = document.getElementById('ai-result-method');
        const tableEl = document.getElementById('ai-result-table');
        const totalEl = document.getElementById('ai-result-total');
        const remainingEl = document.getElementById('ai-result-remaining');
        const explanationEl = document.getElementById('ai-explanation');
        const explanationText = document.getElementById('ai-explanation-text');

        const methodBadge = document.getElementById('ai-method-badge');
        if (recommendation.method === 'ai') {
            methodEl.innerHTML = '<strong>Claude AI</strong> — preporuka generirana umjetnom inteligencijom';
            methodBadge.className = 'alert alert-success d-flex align-items-center mb-3';
        } else {
            methodEl.innerHTML = '<strong>Smart algoritam</strong> — preporuka generirana algoritmom optimizacije';
            methodBadge.className = 'alert alert-info d-flex align-items-center mb-3';
        }

        if (recommendation.ai_explanation) {
            explanationEl.classList.remove('d-none');
            explanationText.textContent = recommendation.ai_explanation;
        } else {
            explanationEl.classList.add('d-none');
        }

        let html = '';
        recommendation.components.forEach(comp => {
            html += `
                <tr>
                    <td>
                        <span class="badge bg-primary me-1">${comp.naziv_tip}</span>
                    </td>
                    <td>
                        <strong>${comp.naziv}</strong>
                        ${comp.kratki_opis ? `<br><small class="text-muted">${comp.kratki_opis}</small>` : ''}
                    </td>
                    <td class="text-end fw-semibold">${parseFloat(comp.cijena).toFixed(2)} €</td>
                </tr>
            `;
        });
        tableEl.innerHTML = html;

        totalEl.textContent = parseFloat(recommendation.total_price).toFixed(2) + ' €';
        remainingEl.textContent = '+' + parseFloat(recommendation.remaining_budget).toFixed(2) + ' €';
    }

    async function applyRecommendation() {
        if (!lastRecommendation || !lastRecommendation.components.length) return;

        applyBtn.disabled = true;
        applyBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Učitavanje...';

        try {
            const components = lastRecommendation.components.map(c => ({
                proizvod_id: c.proizvod_id,
                tip_proizvoda_id: c.tip_proizvoda_id,
            }));

            const response = await fetch(`${baseUrl}/apply-recommendation`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({ components: components })
            });

            const data = await response.json();

            if (data.success) {
                if (typeof window.refreshConfiguration === 'function') {
                    await window.refreshConfiguration();
                    bootstrap.Modal.getInstance(document.getElementById('aiRecommendationModal')).hide();
                    showToast('AI preporuka je učitana u konfigurator!', 'success');
                } else {
                    location.reload();
                }
            } else {
                showToast(data.message || 'Greška pri primjeni preporuke.', 'error');
            }
        } catch (error) {
            console.error('Apply recommendation error:', error);
            showToast('Greška pri primjeni preporuke.', 'error');
        } finally {
            applyBtn.disabled = false;
            applyBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Prihvati i učitaj u konfigurator';
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
            warning: { color: 'bg-danger', icon: 'bi-exclamation-triangle' },
            info: { color: 'bg-primary', icon: 'bi-info-circle' }
        };

        toastEl.classList.remove('bg-success', 'bg-danger', 'bg-primary');
        toastEl.classList.add(map[type].color);
        icon.className = `bi ${map[type].icon} me-2`;
        body.textContent = message;

        new bootstrap.Toast(toastEl, { delay: 3500 }).show();
    }
})();
