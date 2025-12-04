/**
 * public/js/quote-calculator.js
 * Handles live quote calculation for the onboarding form.
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log('Initializing Quote Calculator...');

    const form = document.getElementById('project-data-form');
    if (!form) {
        console.warn('Quote Calculator: Form #project-data-form not found.');
        return;
    }

    // Elements for Live Summary (Sidebar/Header)
    const liveTotalDisplay = document.getElementById('live-total');
    const livePackageDisplay = document.getElementById('live-package');
    const liveAddonsDisplay = document.getElementById('live-addons');
    const liveQuoteSummary = document.getElementById('live-quote-summary');

    // Elements for Detailed Quote (Step 5 & 6)
    const quoteDiv = document.getElementById('quotation-details');
    const finalQuoteDiv = document.getElementById('final-quotation-table');

    // Pricing Data (Should be injected via PHP in the view)
    const pricing = window.pricingData?.pricing || {};
    const addons = window.pricingData?.addons || {};
    const baseIncludedPages = window.pricingData?.base_included_pages || 5;

    // Cache & Debounce
    const quoteCache = {};
    let debounceTimer;

    function debounce(func, wait) {
        return function (...args) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(this, args), wait);
        };
    }

    // --- Live Quote Calculation (Client-Side Estimate) ---

    function updateLiveQuote() {
        // 1. Get Selected Addons
        const selectedAddons = [];
        const featureInputs = form.querySelectorAll('input[name="features[]"]:checked');
        featureInputs.forEach(input => {
            selectedAddons.push(input.value);
        });

        // 2. Determine Package
        let packageName = 'Basic';
        // Simple logic: if any selected addon is not free in Basic, upgrade to Standard/Premium
        // This logic mimics the PHP logic roughly, but can be adjusted.
        // For now, we'll rely on the server for the "True" package determination, 
        // but do a best-guess here for the UI.

        // Check for Standard-only features
        selectedAddons.forEach(addonKey => {
            if (addons[addonKey]) {
                if (addons[addonKey]['Standard'] === 0.00 && addons[addonKey]['Basic'] > 0) {
                    if (packageName === 'Basic') packageName = 'Standard';
                }
                if (addons[addonKey]['Premium'] === 0.00) {
                    packageName = 'Premium';
                }
            }
        });

        // 3. Calculate Costs
        if (!pricing[packageName]) return; // Safety check

        const packageInfo = pricing[packageName];
        let total = packageInfo.base;
        let addonsCount = 0;

        // Pages
        const pageInputs = form.querySelectorAll('input[name="pages[]"]:checked');
        const pageCount = pageInputs.length;
        const extraPages = Math.max(0, pageCount - baseIncludedPages);
        total += extraPages * packageInfo.extra_page;

        // Addons Cost
        selectedAddons.forEach(addonKey => {
            if (addons[addonKey]) {
                total += addons[addonKey][packageName];
                addonsCount++;
            }
        });

        // 4. Update UI
        if (liveTotalDisplay) liveTotalDisplay.textContent = 'R' + total.toLocaleString('en-ZA', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        if (livePackageDisplay) livePackageDisplay.textContent = 'Package: ' + packageName;
        if (liveAddonsDisplay) liveAddonsDisplay.textContent = 'Add-ons: ' + addonsCount;
    }

    // --- Server-Side Accurate Quote ---

    function triggerQuoteCalculation() {
        if (!quoteDiv) return;

        // Generate Cache Key
        const pageInputs = Array.from(form.querySelectorAll('input[name="pages[]"]:checked')).map(i => i.value).sort();
        const featureInputs = Array.from(form.querySelectorAll('input[name="features[]"]:checked')).map(i => i.value).sort();
        const cacheKey = JSON.stringify({ pages: pageInputs, features: featureInputs });

        if (quoteCache[cacheKey]) {
            console.log('Using cached quote data.');
            renderQuotation(quoteCache[cacheKey]);
            return;
        }

        // If not in cache, call debounced calculation
        quoteDiv.innerHTML = '<div class="flex justify-center p-4"><div class="spinner spinner-primary"></div><span class="ml-2">Calculating quote...</span></div>';
        debouncedCalculate(cacheKey);
    }

    const debouncedCalculate = debounce((cacheKey) => {
        calculateQuotation(cacheKey);
    }, 500);

    function calculateQuotation(cacheKey) {
        const calculationData = new FormData();
        const pageInputs = form.querySelectorAll('input[name="pages[]"]:checked');
        pageInputs.forEach(input => calculationData.append('pages[]', input.value));
        const featureInputs = form.querySelectorAll('input[name="features[]"]:checked');
        featureInputs.forEach(input => calculationData.append('features[]', input.value));

        // Get Base Path from meta tag or default
        const basePath = document.querySelector('meta[name="base-path"]')?.getAttribute('content') || '';

        fetch(`${basePath}/calculate-quote`, {
            method: 'POST',
            body: calculationData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cache the result
                    if (cacheKey) {
                        quoteCache[cacheKey] = data.quote;
                    }
                    renderQuotation(data.quote);
                } else {
                    if (quoteDiv) quoteDiv.innerHTML = '<p class="text-center text-error">Failed to calculate quote.</p>';
                }
            })
            .catch(error => {
                console.error('Quote calculation error:', error);
                if (quoteDiv) quoteDiv.innerHTML = '<p class="text-center text-error">Error connecting to server.</p>';
            });
    }

    function renderQuotation(data) {
        const quoteHTML = `
            <div class="bg-surface-50 p-4 rounded-lg border border-border">
                <h4 class="text-xl font-semibold mb-2">Selected Package: <span class="text-primary">${data.package_name}</span></h4>
                <table class="w-full text-left table-auto">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="py-2">Item</th>
                            <th class="py-2 text-right">Cost (Once-Off)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td class="py-1">Base Package Cost (${data.package_name})</td><td class="py-1 text-right">R${data.base_cost}</td></tr>
                        ${Object.entries(data.addons).map(([name, cost]) => `
                            <tr><td class="py-1 pl-4 text-sm text-secondary">Add-on: ${name.replace(/_/g, ' ')}</td><td class="py-1 text-right text-sm text-secondary">R${cost}</td></tr>
                        `).join('')}
                        <tr><td class="py-1 pl-4 text-sm text-secondary">Extra Pages (${data.extra_pages} @ R${(data.extra_pages_total / Math.max(1, data.extra_pages)).toFixed(2)})</td><td class="py-1 text-right text-sm text-secondary">R${data.extra_pages_total}</td></tr>
                        <tr class="font-bold border-t border-border mt-2">
                            <td class="py-2 pt-3">TOTAL PROJECT COST (Once-Off)</td>
                            <td class="py-2 pt-3 text-right text-primary text-lg">R${data.total_once_off}</td>
                        </tr>
                        <tr><td class="py-1">Hosting Fee (per month)</td><td class="py-1 text-right">R${data.hosting_monthly}</td></tr>
                    </tbody>
                </table>
            </div>
        `;

        if (quoteDiv) quoteDiv.innerHTML = quoteHTML;
        if (finalQuoteDiv) finalQuoteDiv.innerHTML = quoteHTML;
    }

    // Inject Price Badges
    function injectPriceBadges() {
        const featureInputs = form.querySelectorAll('input[name="features[]"]');
        featureInputs.forEach(input => {
            const addonKey = input.value;
            if (addons[addonKey]) {
                const cost = addons[addonKey]['Basic'];
                if (cost > 0) {
                    // Check if badge already exists
                    if (input.parentElement.querySelector('.price-badge')) return;

                    const badge = document.createElement('span');
                    badge.className = 'price-badge text-xs bg-surface-200 text-secondary px-2 py-0.5 rounded ml-2';
                    badge.textContent = '+R' + cost.toLocaleString();
                    input.parentElement.appendChild(badge);
                }
            }
        });
    }

    // --- Event Listeners ---

    // Attach listeners for live update
    const liveUpdateInputs = form.querySelectorAll('input[name="features[]"], input[name="pages[]"]');
    liveUpdateInputs.forEach(input => {
        input.addEventListener('change', () => {
            updateLiveQuote();
            // Also trigger server calculation if we are on the quote step (Step 5)
            // We can check if the quote div is visible or if we are on step 5
            // But since this is decoupled, we might just rely on the step change event or user interaction
        });
    });

    // Listen for step changes (dispatched by onboarding.js if possible, or we poll/observe)
    // Since onboarding.js doesn't dispatch a custom event, we can hook into the 'click' of next/prev buttons
    // OR, we can use a MutationObserver on the step content visibility.

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.target.classList.contains('active') && mutation.target.id === 'step-5') {
                triggerQuoteCalculation();
            }
        });
    });

    const stepContents = document.querySelectorAll('.step-content');
    stepContents.forEach(step => {
        observer.observe(step, { attributes: true, attributeFilter: ['class'] });
    });

    // Initialize
    injectPriceBadges();
    updateLiveQuote();
});
