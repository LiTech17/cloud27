// public/js/onboarding.js - Fixed for CORS and network issues

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('project-data-form');
    const stepContents = document.querySelectorAll('.step-content');
    const stepItems = document.querySelectorAll('.step-item');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const ajaxMessage = document.getElementById('ajax-message');
    const loadingOverlay = document.getElementById('loading-overlay');
    const autosaveIndicator = document.getElementById('autosave-indicator');
    const autosaveText = document.getElementById('autosave-text');
    const currentStepInput = document.getElementById('current_step');

    const totalSteps = stepContents.length;
    let autoSaveTimer;
    let debounceTimer;
    const AUTOSAVE_DELAY = 1000;

    // --- URL and Data Keys ---
    const basePath = document.querySelector('meta[name="base-path"]')?.getAttribute('content') || '';
    const projectId = form.dataset.projectId || form.action.match(/\/(\d+)$/)?.[1];
    if (!projectId) {
        console.error('CRITICAL ERROR: Project ID not found. Onboarding cannot function.');
        return;
    }
    const STORAGE_KEY = `project_draft_${projectId}`;
    const STORAGE_STEP_KEY = `project_draft_step_${projectId}`;
    const FORM_SUBMISSION_URL = form.action;

    // Load saved step from localStorage or URL hash
    let currentStep = 0;
    const savedStep = localStorage.getItem(STORAGE_STEP_KEY);
    const hashStep = window.location.hash.match(/step-(\d+)/);

    if (hashStep) {
        const loadedStep = parseInt(hashStep[1]);
        if (loadedStep >= 0 && loadedStep < totalSteps) {
            currentStep = loadedStep;
        }
    } else if (savedStep !== null) {
        const parsedStep = parseInt(savedStep);
        if (parsedStep >= 0 && parsedStep < totalSteps) {
            currentStep = parsedStep;
        }
    }

    console.log('🚀 === FORM LIFECYCLE DEBUG INFO ===');
    console.log('Project ID:', projectId);
    console.log('Initial Step:', currentStep);
    console.log('Total steps:', totalSteps);
    console.log('Form Submission URL:', FORM_SUBMISSION_URL);

    // ================================================================
    // CORE DATA HANDLING FUNCTIONS
    // ================================================================

    function getFormDataAsObject() {
        const payload = {};
        const elements = form.elements;
        const handledNames = new Set();

        for (let i = 0; i < elements.length; i++) {
            const element = elements[i];
            const name = element.name;

            if (!name || name === 'csrf_token' || name === 'current_step' || name === 'project_id' || element.type === 'file' || element.type === 'submit' || element.type === 'button' || handledNames.has(name)) {
                continue;
            }

            if (element.type === 'checkbox') {
                const sameNameCheckboxes = form.querySelectorAll(`input[name="${name}"][type="checkbox"]`);

                if (sameNameCheckboxes.length > 1) {
                    const checkedValues = Array.from(sameNameCheckboxes)
                        .filter(cb => cb.checked)
                        .map(cb => cb.value);

                    payload[name] = checkedValues;
                    handledNames.add(name);
                } else {
                    payload[name] = element.checked ? (element.value || '1') : null;
                }
            } else if (element.type === 'radio') {
                const checkedRadio = form.querySelector(`input[name="${name}"]:checked`);
                if (checkedRadio) {
                    payload[name] = checkedRadio.value;
                } else {
                    payload[name] = null;
                }
                handledNames.add(name);
            } else {
                payload[name] = element.value;
            }
        }

        payload['current_step'] = currentStep;
        return payload;
    }

    /**
     * Prepare URL-encoded data for server submission (without files for draft saves)
     * This avoids CORS preflight requests for simple data
     */
    function getUrlEncodedData() {
        const data = new URLSearchParams();
        const formData = new FormData(form);

        // Add all form fields except files
        for (let [key, value] of formData.entries()) {
            if (key !== 'brand_guidelines' && key !== 'business_profile') {
                if (Array.isArray(value)) {
                    value.forEach(v => data.append(key, v));
                } else {
                    data.append(key, value);
                }
            }
        }

        // Add draft flag
        if (currentStep < totalSteps - 1) {
            data.append('is_draft', '1');
        } else {
            data.append('is_draft', '0');
        }

        return data;
    }

    /**
     * Prepare FormData for final submission (includes files)
     */
    function getFormDataForFinalSubmission() {
        const formData = new FormData(form);
        formData.set('is_draft', '0');
        return formData;
    }

    // ================================================================
    // SERVER-SIDE DRAFT SAVE FUNCTIONS - FIXED FOR CORS
    // ================================================================

    async function saveDraftToServer() {
        try {
            // Use URL-encoded data for draft saves to avoid CORS preflight
            const data = getUrlEncodedData();

            console.log('💾 Saving draft to server via POST:', FORM_SUBMISSION_URL);
            console.log('📦 Data being sent:', Object.fromEntries(data.entries()));

            const response = await fetch(FORM_SUBMISSION_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: data,
                credentials: 'same-origin' // Include cookies/session
            });

            console.log('📨 Server response:', {
                status: response.status,
                statusText: response.statusText,
                ok: response.ok,
                type: response.type
            });

            if (response.ok) {
                console.log('💾 Draft saved to server successfully');
                return true;
            } else {
                // Try to get error details from response
                let errorDetail = response.statusText;
                try {
                    const errorText = await response.text();
                    if (errorText) {
                        errorDetail = errorText.substring(0, 200); // Limit length
                    }
                } catch (e) {
                    // Ignore if we can't read response body
                }

                console.error('❌ Server draft save failed:', response.status, errorDetail);

                // Specific error handling
                if (response.status === 413) {
                    showMessage('File size too large. Please reduce file sizes or remove files before saving draft.', 'error');
                } else if (response.status === 419) {
                    showMessage('Session expired. Please refresh the page and try again.', 'error');
                } else if (response.status === 422) {
                    showMessage('Validation error. Please check your inputs.', 'error');
                } else if (response.status === 403) {
                    showMessage('Access denied. Please ensure you are logged in.', 'error');
                }

                return false;
            }
        } catch (error) {
            console.error('❌ Network error during draft save:', error);

            // Specific network error handling
            if (error.name === 'TypeError' && error.message.includes('Failed to fetch')) {
                showMessage('Network error. Please check your connection.', 'error');
            } else if (error.name === 'AbortError') {
                console.log('Request was aborted');
            } else {
                showMessage('Connection failed. Data saved locally only.', 'warning');
            }

            return false;
        }
    }

    // ================================================================
    // LOCALSTORAGE FUNCTIONS
    // ================================================================

    function loadDraftFromStorage() {
        try {
            const savedData = localStorage.getItem(STORAGE_KEY);
            if (savedData) {
                const draftData = JSON.parse(savedData);
                console.log('💾 Loaded draft from localStorage:', draftData);

                Object.keys(draftData).forEach(key => {
                    const value = draftData[key];
                    const element = form.elements[key];
                    if (!element) return;

                    if (element.length && (element.item(0).type === 'radio' || element.item(0).type === 'checkbox')) {
                        if (Array.isArray(value)) {
                            Array.from(element).forEach(cb => {
                                cb.checked = value.includes(cb.value);
                            });
                        } else {
                            const radio = form.querySelector(`input[name="${key}"][value="${value}"]`);
                            if (radio) radio.checked = true;
                        }
                    } else if (element.type === 'checkbox') {
                        element.checked = value === '1' || value === true || element.value === value;
                    } else if (element.type !== 'file' && element.name !== 'current_step' && value !== null) {
                        element.value = value;
                    }
                });

                showAutosave('Draft restored from previous session', true);
                updateProgress();
                updateReviewSection();
                return true;
            }
        } catch (error) {
            console.error('❌ Error loading draft from localStorage:', error);
        }
        return false;
    }

    function saveDraftToStorage(showNotification = true) {
        try {
            const draftData = getFormDataAsObject();
            const cleanDraft = {};
            for (const key in draftData) {
                const value = draftData[key];
                if (value !== null && !(Array.isArray(value) && value.length === 0)) {
                    cleanDraft[key] = value;
                }
            }

            if (Object.keys(cleanDraft).length === 1 && cleanDraft.current_step !== undefined) {
                return false;
            }

            localStorage.setItem(STORAGE_KEY, JSON.stringify(cleanDraft));
            localStorage.setItem(STORAGE_STEP_KEY, currentStep.toString());

            const timestamp = new Date().toLocaleTimeString();
            if (showNotification) {
                showAutosave(`Draft saved locally at ${timestamp}`, true);
            }

            return true;
        } catch (error) {
            console.error('❌ Error saving draft to localStorage:', error);
            if (showNotification) {
                showAutosave('Failed to save draft locally', false);
            }
            return false;
        }
    }

    function clearDraftFromStorage() {
        try {
            localStorage.removeItem(STORAGE_KEY);
            localStorage.removeItem(STORAGE_STEP_KEY);
            console.log('🗑️ Draft cleared from localStorage');
        } catch (error) {
            console.error('❌ Error clearing draft from localStorage:', error);
        }
    }

    function hasDraftInStorage() {
        return localStorage.getItem(STORAGE_KEY) !== null;
    }

    // ================================================================
    // HELPER FUNCTIONS
    // ================================================================

    function showMessage(message, type = 'success') {
        if (!ajaxMessage) return;

        ajaxMessage.textContent = message;
        ajaxMessage.className = `alert alert-${type} mb-4 animate-slide-down`;
        ajaxMessage.classList.remove('hidden');
        setTimeout(() => {
            if (ajaxMessage) {
                ajaxMessage.classList.add('hidden');
            }
        }, 5000);
    }

    function showAutosave(message = 'Saved ✓', isSuccess = true) {
        if (!autosaveIndicator || !autosaveText) return;

        autosaveText.textContent = message;
        autosaveIndicator.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg z-50 transition-all duration-300 ${isSuccess ? 'bg-success text-white' : 'bg-error text-white'}`;
        autosaveIndicator.classList.remove('hidden');
        clearTimeout(autoSaveTimer);
        autoSaveTimer = setTimeout(() => {
            if (autosaveIndicator) {
                autosaveIndicator.classList.add('hidden');
            }
        }, 3000);
    }

    function showLoading() {
        console.log('⏳ Showing loading overlay');
        if (loadingOverlay) {
            loadingOverlay.classList.remove('hidden');
        }
    }

    function hideLoading() {
        console.log('✅ Hiding loading overlay');
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
    }

    function updateReviewSection() {
        const budgetSelect = document.getElementById('budget_range');
        const reviewCompany = document.getElementById('review-company');
        const reviewContact = document.getElementById('review-contact');
        const reviewEmail = document.getElementById('review-email');
        const reviewBudget = document.getElementById('review-budget');

        if (reviewCompany) reviewCompany.textContent = document.getElementById('company_name')?.value || 'Not provided';
        if (reviewContact) reviewContact.textContent = document.getElementById('contact_name')?.value || 'Not provided';
        if (reviewEmail) reviewEmail.textContent = document.getElementById('contact_email')?.value || 'Not provided';

        const budgetText = budgetSelect?.options[budgetSelect?.selectedIndex]?.text || 'Not specified';
        if (reviewBudget) reviewBudget.textContent = budgetText.trim();
    }

    function updateUI(stepIndex) {
        currentStep = stepIndex;

        stepContents.forEach((content, index) => {
            content.classList.remove('active');
            if (index === stepIndex) {
                content.classList.add('active');
            }
        });

        stepItems.forEach((item, index) => {
            item.classList.remove('active', 'inactive', 'completed');
            const icon = item.querySelector('.step-icon');
            if (icon) {
                icon.className = 'fa-solid step-icon';

                if (index < stepIndex) {
                    item.classList.add('completed');
                    icon.classList.add('fa-circle-check');
                } else if (index === stepIndex) {
                    item.classList.add('active');
                    icon.classList.add('fa-circle-dot');
                } else {
                    item.classList.add('inactive');
                    icon.classList.add('fa-circle');
                }
            }
        });

        if (prevBtn) prevBtn.style.display = stepIndex > 0 ? 'flex' : 'none';
        if (nextBtn) nextBtn.style.display = stepIndex < totalSteps - 1 ? 'flex' : 'none';
        if (submitBtn) submitBtn.style.display = stepIndex === totalSteps - 1 ? 'flex' : 'none';

        window.location.hash = `step-${stepIndex}`;
        const stepContentArea = document.querySelector('.step-content-area');
        if (stepContentArea) {
            stepContentArea.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        if (currentStepInput) currentStepInput.value = stepIndex;
        updateProgress();
        if (stepIndex === totalSteps - 1) {
            updateReviewSection();
        }
    }

    function validateCurrentStep() {
        let isValid = true;
        const currentContent = document.getElementById(`step-${currentStep}`);
        if (!currentContent) return true;

        currentContent.querySelectorAll('.text-xs.text-error').forEach(el => el.textContent = '');
        currentContent.querySelectorAll('.form-input-error').forEach(el => el.classList.remove('form-input-error'));

        const requiredInputs = currentContent.querySelectorAll('input[required], select[required], textarea[required]');

        const validatedNames = new Set();
        requiredInputs.forEach(input => {
            const name = input.name;
            const isGroupElement = (input.type === 'checkbox' || input.type === 'radio') && form.querySelectorAll(`[name="${name}"]`).length > 1;

            if (input.closest('[data-required-group]') || (isGroupElement && validatedNames.has(name))) {
                return;
            }

            const isEmail = input.type === 'email';
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            let failed = false;

            if (isGroupElement) {
                if (!form.querySelector(`input[name="${name}"]:checked`)) {
                    failed = true;
                }
                validatedNames.add(name);
            } else if (!input.value.trim() || (isEmail && !input.value.match(emailRegex))) {
                failed = true;
            }

            if (failed) {
                isValid = false;
                input.classList.add('form-input-error');

                const errorEl = input.parentElement.querySelector('.text-xs.text-error') || input.closest('.form-group')?.querySelector('.text-xs.text-error');

                if (errorEl) {
                    errorEl.textContent = isEmail ? 'Please enter a valid email address.' : 'This field is required.';
                }
            }
        });

        const requiredGroups = currentContent.querySelectorAll('[data-required-group]');
        requiredGroups.forEach(group => {
            const checkboxes = group.querySelectorAll('input[type="checkbox"], input[type="radio"]');
            const hasChecked = Array.from(checkboxes).some(cb => cb.checked);

            if (!hasChecked) {
                isValid = false;
                group.classList.add('form-input-error');
                const errorEl = group.querySelector('.text-xs.text-error') || group.closest('.form-group')?.querySelector('.text-xs.text-error');
                if (errorEl) {
                    errorEl.textContent = 'Please select at least one option.';
                }
            }
        });

        return isValid;
    }

    function updateProgress() {
        const allRequiredElements = Array.from(form.querySelectorAll('input[required], select[required], textarea[required], [data-required-group]'));

        const countedElements = new Set();
        let totalTrackedElements = 0;
        let completedFields = 0;

        allRequiredElements.forEach(element => {
            const name = element.name || element.dataset.field;
            const isGroup = element.hasAttribute('data-required-group');
            const isRadioOrCheckbox = element.type === 'radio' || element.type === 'checkbox';

            if (!isGroup && element.closest('[data-required-group]')) {
                return;
            }

            if (isRadioOrCheckbox) {
                const groupName = element.name;
                const elementsInGroup = form.querySelectorAll(`[name="${groupName}"]:not([data-required-group] *)`);
                if (elementsInGroup.length > 1) {
                    if (countedElements.has(groupName)) return;
                    countedElements.add(groupName);
                }
            } else if (!isGroup) {
                if (countedElements.has(name)) return;
                countedElements.add(name);
            }

            totalTrackedElements++;

            if (isGroup) {
                const checkboxes = element.querySelectorAll('input[type="checkbox"], input[type="radio"]');
                if (Array.from(checkboxes).some(cb => cb.checked)) {
                    completedFields++;
                }
            } else if (element.type === 'file') {
                if (element.files.length > 0 || element.dataset.hasExistingValue === 'true') {
                    completedFields++;
                }
            } else if (isRadioOrCheckbox) {
                if (form.querySelector(`[name="${name}"]:checked`)) {
                    completedFields++;
                }
            } else {
                if (element.value.trim()) {
                    completedFields++;
                }
            }
        });

        if (totalTrackedElements === 0) return;

        const progress = Math.min(100, Math.round((completedFields / totalTrackedElements) * 100));
        if (progressBar) progressBar.style.width = `${progress}%`;
        if (progressText) progressText.textContent = `${progress}% Complete`;
    }

    // ================================================================
    // EVENT LISTENERS
    // ================================================================

    form.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        updateProgress();
        debounceTimer = setTimeout(() => {
            saveDraftToStorage(false);
            if (currentStep === totalSteps - 1) updateReviewSection();
        }, AUTOSAVE_DELAY);
    });

    form.addEventListener('change', () => {
        clearTimeout(debounceTimer);
        updateProgress();
        saveDraftToStorage(false);
        if (currentStep === totalSteps - 1) updateReviewSection();
    });

    if (nextBtn) {
        nextBtn.addEventListener('click', async () => {
            if (validateCurrentStep()) {
                showLoading();
                try {
                    const serverSaved = await saveDraftToServer();
                    saveDraftToStorage(false);

                    if (currentStep < totalSteps - 1) {
                        updateUI(currentStep + 1);
                    }

                    if (!serverSaved) {
                        showMessage('Progress saved locally. Some features may not work until connection is restored.', 'warning');
                    }
                } catch (error) {
                    console.error('Navigation error:', error);
                    showMessage('Failed to save progress to server. Data saved locally.', 'warning');
                    if (currentStep < totalSteps - 1) {
                        updateUI(currentStep + 1);
                    }
                } finally {
                    hideLoading();
                }
            } else {
                showMessage('Please complete the required fields on this step before continuing.', 'error');
            }
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', async () => {
            if (currentStep > 0) {
                showLoading();
                try {
                    await saveDraftToServer();
                    saveDraftToStorage(false);
                    updateUI(currentStep - 1);
                } catch (error) {
                    console.error('Previous navigation error:', error);
                    showMessage('Failed to save progress to server. Data saved locally.', 'warning');
                    updateUI(currentStep - 1);
                } finally {
                    hideLoading();
                }
            }
        });
    }

    stepItems.forEach((item, index) => {
        item.addEventListener('click', async () => {
            if (index < currentStep || validateCurrentStep()) {
                showLoading();
                try {
                    await saveDraftToServer();
                    saveDraftToStorage(false);
                    updateUI(index);
                } catch (error) {
                    console.error('Sidebar navigation error:', error);
                    showMessage('Failed to save progress to server. Data saved locally.', 'warning');
                    updateUI(index);
                } finally {
                    hideLoading();
                }
            } else {
                showMessage('Please complete the required fields on the current step before proceeding.', 'error');
            }
        });
    });

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!validateCurrentStep()) {
            showMessage('Please complete all required fields before submitting.', 'error');
            return;
        }

        saveDraftToStorage(false);

        console.log('📤 Sending final submission via AJAX to:', FORM_SUBMISSION_URL);
        showLoading();

        try {
            const formData = new FormData(form);
            // Ensure is_draft is 0 for final submit
            formData.set('is_draft', '0');

            const response = await fetch(FORM_SUBMISSION_URL, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                clearDraftFromStorage();
                showMessage(result.message || 'Project submitted successfully! Redirecting...', 'success');

                // Redirect after delay
                setTimeout(() => {
                    window.location.href = `${basePath}/`;
                }, 2000);
            } else {
                console.error('Submission failed:', result);
                showMessage(result.message || 'Submission failed. Please check your inputs.', 'error');
                hideLoading();
            }
        } catch (error) {
            console.error('Submission error:', error);
            showMessage('An error occurred. Please try again.', 'error');
            hideLoading();
        }
    });

    // ================================================================
    // INITIALIZATION
    // ================================================================

    if (hasDraftInStorage()) {
        try {
            const savedData = localStorage.getItem(STORAGE_KEY);
            const parsedData = JSON.parse(savedData);

            const hasSubstantialData = Object.keys(parsedData).some(key => key !== 'current_step');

            if (hasSubstantialData) {
                const userWantsToLoad = confirm('You have unsaved draft data. Would you like to restore it?');
                if (userWantsToLoad) {
                    loadDraftFromStorage();
                } else {
                    clearDraftFromStorage();
                    currentStep = 0;
                }
            } else {
                clearDraftFromStorage();
            }
        } catch (e) {
            console.error('Error checking draft data:', e);
            clearDraftFromStorage();
        }
    }

    updateUI(currentStep);
    updateProgress();
    updateReviewSection();

    window.addEventListener('beforeunload', (e) => {
        if (hasDraftInStorage()) {
            try {
                const savedData = localStorage.getItem(STORAGE_KEY);
                const parsedData = JSON.parse(savedData);
                const hasSubstantialData = Object.keys(parsedData).some(key => key !== 'current_step');

                if (hasSubstantialData) {
                    e.preventDefault();
                    e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                    return e.returnValue;
                }
            } catch (e) {
                console.error('Error checking draft on beforeunload:', e);
            }
        }
    });
});