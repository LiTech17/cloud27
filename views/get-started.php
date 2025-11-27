<?php 
// views/get-started.php

/**
 * @var array $data Contains data passed from the controller
 */
$statusMessage = $data['statusMessage'] ?? ''; 
$statusType = $data['statusType'] ?? 'alert-info';

// Define the maximum step for the progress indicator
$maxSteps = 6; 
?>

<section class="section">
    <div class="container-md mx-auto">
        <div class="text-center mb-8">
            <h2 class="heading-2 mb-3">Project Onboarding & Quotation</h2>
            <p class="text-lg text-secondary max-w-2xl mx-auto">
                Please provide detailed information across the six steps to generate your project quote.
            </p>
        </div>
        
        <div class="max-w-4xl mx-auto">
            
            <?php if (!empty($statusMessage)): ?>
                <div class="alert alert-<?= $statusType === 'alert-success' ? 'success' : 'error' ?> mb-6 animate-slide-down">
                    <p class="alert-message"><?= htmlspecialchars($statusMessage) ?></p>
                </div>
            <?php endif; ?>

            <div id="form-status-message" style="display: none;" class="mb-6 animate-slide-down"></div>
            
            <div class="progress-indicator mb-6 p-4 border rounded-lg bg-gray-50 flex justify-between items-center" id="progress-indicator">
                <span class="text-sm font-semibold">Step <span id="current-step">1</span> of <?= $maxSteps ?></span>
                <span id="step-title" class="text-base font-medium">BUSINESS PROFILE</span>
            </div>

            <div class="card shadow-lg p-6">
                <form id="onboardingForm" action="<?= BASE_PATH ?>/onboarding-submit" method="POST" enctype="multipart/form-data"> 
                    
                    <input type="hidden" name="current_step" id="current-step-input" value="1">

                    <div class="step-content" id="step-1">
                        <h3 class="heading-3 mb-4">1. Business Profile</h3>
                        
                        <div class="form-group">
                            <label for="company_name" class="form-label">Company Name / Trading Name</label>
                            <input type="text" id="company_name" name="company_name" class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="products_services" class="form-label">Products/Services Offered</label>
                            <textarea id="products_services" name="products_services" rows="3" class="form-textarea" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="mission_statement" class="form-label">Mission Statement</label>
                            <textarea id="mission_statement" name="mission_statement" rows="2" class="form-textarea"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="industry" class="form-label">Industry</label>
                            <input type="text" id="industry" name="industry" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="usp" class="form-label">Unique Selling Point (USP)</label>
                            <textarea id="usp" name="usp" rows="2" class="form-textarea"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="competitors" class="form-label">Main Competitors (comma-separated)</label>
                            <input type="text" id="competitors" name="competitors" class="form-input">
                        </div>
                    </div>

                    <div class="step-content" id="step-2" style="display: none;">
                        <h3 class="heading-3 mb-4">2. Contact Details</h3>
                        
                        <div class="form-group">
                            <label for="rep_name" class="form-label">Representative Name</label>
                            <input type="text" id="rep_name" name="rep_name" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="rep_role" class="form-label">Representative Role/Position</label>
                            <input type="text" id="rep_role" name="rep_role" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="rep_email" class="form-label">Representative Email</label>
                            <input type="email" id="rep_email" name="rep_email" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="rep_phone" class="form-label">Representative Phone</label>
                            <input type="tel" id="rep_phone" name="rep_phone" class="form-input">
                        </div>

                        <div class="form-group">
                            <label for="rep_whatsapp" class="form-label">Representative Whatsapp Number</label>
                            <input type="tel" id="rep_whatsapp" name="rep_whatsapp" class="form-input">
                        </div>

                        <div class="form-group">
                            <label for="comm_method" class="form-label">Preferred Communication Method</label>
                            <select id="comm_method" name="comm_method" class="form-input">
                                <option value="email">Email</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="phone">Phone Call</option>
                            </select>
                        </div>
                    </div>

                    <div class="step-content" id="step-3" style="display: none;">
                        <h3 class="heading-3 mb-4">3. Branding Materials</h3>

                        <div class="form-group">
                            <label for="logo_upload" class="form-label">Logo Upload (PNG/SVG)</label>
                            <input type="file" id="logo_upload" name="logo_upload" class="form-input file-input" accept=".png,.svg">
                        </div>

                        <div class="form-group">
                            <label for="brand_colors" class="form-label">Brand Colors (e.g., #FFFFFF, #000000)</label>
                            <input type="text" id="brand_colors" name="brand_colors" class="form-input" placeholder="Primary Color HEX, Secondary Color HEX, etc.">
                        </div>

                        <div class="form-group">
                            <label for="fonts" class="form-label">Fonts</label>
                            <input type="text" id="fonts" name="fonts" class="form-input" placeholder="e.g., Roboto, Open Sans">
                        </div>

                        <div class="form-group">
                            <label for="other_assets" class="form-label">Business Profile Upload, Other (optional)</label>
                            <input type="file" id="other_assets" name="other_assets[]" multiple class="form-input file-input">
                        </div>
                    </div>

                    <div class="step-content" id="step-4" style="display: none;">
                        <h3 class="heading-3 mb-4">4. Project Data</h3>
                        
                        <div class="form-group">
                            <label for="project_title" class="form-label">Project Title</label>
                            <input type="text" id="project_title" name="project_title" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Website Objectives (Select all that apply)</label>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="objectives[]" value="lead_generation"> Lead Generation</label>
                                <label><input type="checkbox" name="objectives[]" value="ecommerce_sales"> E-commerce Sales</label>
                                <label><input type="checkbox" name="objectives[]" value="brand_awareness"> Brand Awareness</label>
                                <label><input type="checkbox" name="objectives[]" value="online_booking"> Online Booking</label>
                                <label><input type="checkbox" name="objectives[]" value="portfolio_showcase"> Portfolio Showcase</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Required Pages (Select all that apply)</label>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="pages[]" value="home" checked> Home Page</label>
                                <label><input type="checkbox" name="pages[]" value="about" checked> About Us</label>
                                <label><input type="checkbox" name="pages[]" value="services"> Services/Products</label>
                                <label><input type="checkbox" name="pages[]" value="contact" checked> Contact Us</label>
                                <label><input type="checkbox" name="pages[]" value="blog"> Blog/News</label>
                                <label><input type="checkbox" name="pages[]" value="privacy_policy"> Privacy Policy</label>
                                <label><input type="checkbox" name="pages[]" value="gallery"> Gallery</label>
                                <label><input type="checkbox" name="pages[]" value="faq"> FAQ</label>
                                <label><input type="checkbox" name="pages[]" value="custom_page_1"> Custom Page 1</label>
                                <label><input type="checkbox" name="pages[]" value="custom_page_2"> Custom Page 2</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Features & Functionality (Select all that apply)</label>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="features[]" value="social_media"> Social Media Integration</label>
                                <label><input type="checkbox" name="features[]" value="whatsapp_button"> WhatsApp Button</label>
                                <label><input type="checkbox" name="features[]" value="custom_contact_form"> Custom Contact Form</label>
                                <label><input type="checkbox" name="features[]" value="complex_form"> Complex Form (Quotation/Survey)</label>
                                <label><input type="checkbox" name="features[]" value="live_chat"> Live Chat Integration</label>
                                <label><input type="checkbox" name="features[]" value="ecommerce"> E-commerce Functionality</label>
                                <label><input type="checkbox" name="features[]" value="booking_system"> Booking System</label>
                                <label><input type="checkbox" name="features[]" value="multi_language"> Multi-Language Support</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="tech_req" class="form-label">Technical Requirements (CMS, third-party integrations)</label>
                            <textarea id="tech_req" name="tech_req" rows="3" class="form-textarea"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="additional_notes" class="form-label">Additional Notes</label>
                            <textarea id="additional_notes" name="additional_notes" rows="2" class="form-textarea"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="flex items-center">
                                <input type="checkbox" id="confirmation" name="confirmation" value="1" class="mr-2" required>
                                I confirm all information is correct and complete.
                            </label>
                        </div>
                    </div>

                    <div class="step-content" id="step-5" style="display: none;">
                        <h3 class="heading-3 mb-4">5. Quotation & Hosting</h3>
                        <p class="text-secondary mb-4">Based on your input, the preliminary quote is calculated below. This section is automatically generated.</p>
                        
                        <div id="quotation-details" class="border p-4 rounded-lg bg-white shadow">
                            <p class="text-center text-lg font-semibold text-gray-500">
                                Calculating quotation based on Step 4 data...
                            </p>
                            </div>
                    </div>

                    <div class="step-content" id="step-6" style="display: none;">
                        <h3 class="heading-3 mb-4">6. Final Submission</h3>
                        
                        <h4 class="text-xl font-semibold mb-3">Review Onboarding Summary</h4>
                        <div id="summary-review" class="border p-4 rounded-lg bg-white mb-6 max-h-96 overflow-y-auto text-sm">
                            <p class="text-secondary">Summary will appear here after completing Step 4.</p>
                        </div>

                        <h4 class="text-xl font-semibold mb-3">Quotation Table</h4>
                        <div id="final-quotation-table" class="border p-4 rounded-lg bg-white mb-6">
                            <p class="text-secondary">Final quotation table will appear here.</p>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-full" id="submit-btn" disabled>
                            <span id="btn-text">Submit & Finalize Project</span>
                            <span id="btn-spinner" style="display: none;" class="spinner spinner-sm"></span>
                        </button>
                    </div>


                    <div class="form-group flex justify-between mt-6">
                        <button type="button" id="prev-btn" class="btn btn-secondary" style="display: none;">
                            &leftarrow; Previous
                        </button>
                        <button type="button" id="next-btn" class="btn btn-primary ml-auto">
                            Next Step &rightarrow;
                        </button>
                        </div>

                </form>
            </div>
        </div>
    </div>
</section>

---

## 💻 JavaScript for Multi-Step and AJAX

This script modifies your existing AJAX logic to handle the multi-step navigation, form validation, and the quote calculation trigger.

```javascript
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded. Initializing Onboarding Form Script.'); // Log initialization
    
    const form = document.getElementById('onboardingForm');
    const statusDiv = document.getElementById('form-status-message');
    const stepContents = document.querySelectorAll('.step-content');
    const currentStepDisplay = document.getElementById('current-step');
    const stepTitleDisplay = document.getElementById('step-title');
    const currentStepInput = document.getElementById('current-step-input');
    const prevBtn = document.getElementById('prev-btn');
    const nextBtn = document.getElementById('next-btn');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');
    
    let currentStep = 1;
    const stepTitles = [
        "BUSINESS PROFILE", "CONTACT DETAILS", "BRANDING MATERIALS", 
        "PROJECT DATA", "QUOTATION & HOSTING", "FINAL SUBMISSION"
    ];

    // --- Helper Functions ---
    
    // Function to check validity of required fields in the current step
    function validateStep(stepIndex) {
        console.log(`\n-- Validating Step ${stepIndex} --`); // Log validation start
        const currentContent = document.getElementById(`step-${stepIndex}`);
        const requiredInputs = currentContent.querySelectorAll('[required]');
        
        let isValid = true;
        requiredInputs.forEach(input => {
            if (!input.checkValidity()) {
                isValid = false;
                console.warn(`Validation failed for input: ${input.name} (Value: ${input.value})`); // Log failed input
                // Optional: Trigger browser's native validation message
                input.reportValidity();
            }
        });
        
        // Special validation for step 4 confirmation checkbox
        if (stepIndex === 4) {
            const confirmationCheckbox = document.getElementById('confirmation');
            if (!confirmationCheckbox.checked) {
                isValid = false;
                console.warn('Validation failed: Step 4 Confirmation checkbox is not checked.');
                showStatus('Please confirm all information is correct before proceeding.', 'error');
            }
        }

        console.log(`Validation result for Step ${stepIndex}: ${isValid ? 'PASSED' : 'FAILED'}`);
        return isValid;
    }

    // Function to show/hide steps and update UI
    function showStep(step) {
        // Clamp step between 1 and maxSteps
        step = Math.max(1, Math.min(step, stepContents.length));
        currentStep = step;
        currentStepInput.value = currentStep;
        
        console.log(`\n** Changing to Step ${currentStep} (${stepTitles[currentStep - 1]}) **`); // Log step change

        stepContents.forEach((content, index) => {
            content.style.display = (index + 1) === currentStep ? 'block' : 'none';
        });

        // Update progress indicator
        currentStepDisplay.textContent = currentStep;
        stepTitleDisplay.textContent = stepTitles[currentStep - 1];

        // Update navigation buttons
        prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';
        nextBtn.style.display = currentStep < 6 ? 'inline-block' : 'none';
        submitBtn.style.display = currentStep === 6 ? 'block' : 'none';
        
        // If on step 5 (Quotation), trigger calculation
        if (currentStep === 5) {
            console.log('Triggering quotation calculation...');
            calculateQuotation();
        }
        
        // If on step 6 (Final Submission), trigger summary/final table display
        if (currentStep === 6) {
            console.log('Displaying final submission summary and enabling submit button.');
            displaySummary();
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }

        // Hide server-side status message block when navigating
        const serverStatus = document.querySelector('.alert.animate-slide-down');
        if (serverStatus) serverStatus.style.display = 'none';

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // --- Quotation and Summary Functions ---
    
    // This function simulates the backend calculation call for Step 5/6
    function calculateQuotation() {
        const quoteDiv = document.getElementById('quotation-details');
        quoteDiv.innerHTML = '<p class="text-center text-lg font-semibold text-gray-500"><span class="spinner"></span> Calculating quote...</p>';

        const formData = new FormData(form);
        console.log('Preparing to send data for quote calculation (Simulated).');
        
        // **ACTION:** Make an AJAX call to a new endpoint (e.g., /api/calculate-quote)
        // For now, we simulate success after a delay.
        setTimeout(() => {
            // **Replace this block with actual fetch call and JSON parsing**
            const mockQuoteData = {
                package: "Standard",
                base_cost: "R3,490",
                hosting_pm: "R69",
                addons: [
                    { name: "Live Chat", cost: "R350" },
                    { name: "Complex Form", cost: "R600" },
                ],
                extra_pages: { count: 3, cost_per: "R120", total: "R360" },
                total_once_off: "R4,780" 
            };
            
            console.log('Simulated quote calculation successful. Rendering quote data:', mockQuoteData);

            // Render the quote table
            renderQuotation(mockQuoteData);

        }, 1500); // Simulate network delay
    }

    function renderQuotation(data) {
        const quoteDiv = document.getElementById('quotation-details');
        const finalQuoteDiv = document.getElementById('final-quotation-table');
        
        // ... (HTML rendering logic remains the same) ...
        const quoteHTML = `
            <h4 class="text-xl font-semibold mb-2">Selected Package: <span class="text-primary">${data.package}</span></h4>
            <table class="w-full text-left table-auto">
                <thead>
                    <tr class="border-b">
                        <th class="py-2">Item</th>
                        <th class="py-2 text-right">Cost (Once-Off)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="py-1">Base Package Cost (${data.package})</td><td class="py-1 text-right">${data.base_cost}</td></tr>
                    ${data.addons.map(a => `
                        <tr><td class="py-1 pl-4 text-sm text-secondary">Add-on: ${a.name}</td><td class="py-1 text-right text-sm text-secondary">${a.cost}</td></tr>
                    `).join('')}
                    <tr><td class="py-1 pl-4 text-sm text-secondary">Extra Pages (${data.extra_pages.count} @ ${data.extra_pages.cost_per})</td><td class="py-1 text-right text-sm text-secondary">${data.extra_pages.total}</td></tr>
                    <tr class="font-bold border-t">
                        <td class="py-2">TOTAL PROJECT COST (Once-Off)</td>
                        <td class="py-2 text-right text-primary text-lg">${data.total_once_off}</td>
                    </tr>
                    <tr><td class="py-1">Hosting Fee (per month)</td><td class="py-1 text-right">${data.hosting_pm}</td></tr>
                </tbody>
            </table>
        `;
        quoteDiv.innerHTML = quoteHTML;
        finalQuoteDiv.innerHTML = quoteHTML;
        console.log('Quotation tables rendered successfully.');
    }
    
    function displaySummary() {
        const summaryDiv = document.getElementById('summary-review');
        const formData = new FormData(form);
        let html = '';
        
        console.log('Generating summary review for Step 6.');

        // Simple summary generation
        for (const [key, value] of formData.entries()) {
            if (key !== 'current_step' && key !== 'confirmation' && key.indexOf('[]') === -1 && key.indexOf('upload') === -1) {
                html += `<p><strong>${key.replace(/_/g, ' ').toUpperCase()}:</strong> ${value}</p>`;
            } else if (key.indexOf('upload') !== -1 && value.name) {
                 html += `<p><strong>${key.replace(/_/g, ' ').toUpperCase()}:</strong> ${value.name} (${(value.size / 1024).toFixed(2)} KB)</p>`;
            }
        }
        
        summaryDiv.innerHTML = html || '<p class="text-secondary">No data entered yet.</p>';
        console.log('Summary content updated.');
    }

    // Function to show status messages (from contact.php)
    function showStatus(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
        const icon = type === 'success' 
            ? `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`
            : `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>`;

        statusDiv.className = `alert ${alertClass} animate-slide-down`;
        statusDiv.innerHTML = `
            ${icon}
            <div class="alert-content">
                <p class="alert-message">${message}</p>
            </div>
        `;
        statusDiv.style.display = 'flex';
        statusDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        console.log(`Status Message Displayed: Type: ${type}, Message: ${message}`); // Log status message
    }

    // --- Event Listeners ---
    
    // Previous Button handler
    prevBtn.addEventListener('click', function() {
        console.log('Prev button clicked. Current step:', currentStep);
        showStatus('', 'info'); // Clear any previous status message
        showStep(currentStep - 1);
    });

    // Next Button handler
    nextBtn.addEventListener('click', function() {
        console.log('Next button clicked. Validating step:', currentStep);
        if (validateStep(currentStep)) {
            showStatus('', 'info'); // Clear any previous status message
            showStep(currentStep + 1);
        }
    });

    // Form Submission Handler (Only triggered on Step 6)
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        console.log('Form submission initiated (Final Step).'); // Log final submission
        
        if (currentStep !== 6) {
            showStatus('Please complete all steps before final submission.', 'error');
            console.error('Submission prevented: Not on Step 6.');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        btnSpinner.style.display = 'inline-block';
        statusDiv.style.display = 'none';

        const formData = new FormData(form);

        console.log(`Sending AJAX request to: ${form.action}`); // Log AJAX destination

        // Fetch API for final submission (reusing your contact form's logic)
        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            console.log('Received response from server. Status:', response.status); // Log response status
            const contentType = response.headers.get("content-type");
            
            if (contentType && contentType.indexOf("application/json") !== -1) {
                console.log('Content-Type is application/json. Parsing...');
                return response.json();
            } else {
                console.error('Content-Type Error: Server did not respond with application/json. Content-Type:', contentType); // Critical error log
                throw new Error("Server response was not JSON. Check backend routing.");
            }
        })
        .then(data => {
            console.log('Parsed JSON data:', data); // Log parsed data
            if (data.success) {
                showStatus(data.message || 'Submission successful! We will be in touch shortly.', 'success');
            } else {
                console.error('Backend validation failed. Error data:', data); // Log backend error details
                // Show a general error, or if errors array exists, show specific messages
                const errorMessage = data.errors ? 'Submission failed due to validation errors. Check the fields above.' : (data.error || 'Submission failed. Please correct the errors and try again.');
                showStatus(errorMessage, 'error');
            }
        })
        .catch(error => {
            console.error('Submission Catch Block Error:', error.message); // Log full error object
            showStatus('A critical error occurred. Please try again or contact us directly.', 'error');
        })
        .finally(() => {
            // Restore button state
            submitBtn.disabled = false;
            btnText.style.display = 'inline';
            btnSpinner.style.display = 'none';
            console.log('Submission process finished. Button state restored.');
        });
    });

    // Initial load
    showStep(1);
});
</script>