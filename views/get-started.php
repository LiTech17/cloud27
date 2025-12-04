<?php 
// views/get-started.php

/**
 * @var array $data Contains data passed from the controller
 */
$statusMessage = $data['statusMessage'] ?? ''; 
$statusType = $data['statusType'] ?? 'alert-info';
$preselectedFeatures = $data['preselectedFeatures'] ?? [];
$pricingData = $data['pricingData'] ?? []; // Ensure pricing data is passed

// Define the maximum step for the progress indicator
$maxSteps = 6; 
?>

<!-- Pass PHP data to JS -->
<script>
    window.preselectedFeatures = <?= json_encode($preselectedFeatures); ?>;
    window.pricingData = <?= json_encode($pricingData); ?>;
</script>
<meta name="base-path" content="<?= BASE_PATH ?>">

<section class="section py-12 bg-surface-50">
    <div class="container-xl mx-auto px-4">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="heading-2 mb-3">Let's Build Your Vision</h2>
            <p class="text-lg text-secondary max-w-2xl mx-auto">
                Tell us about your business, and we'll handle the rest. We just need a few details to get started.
            </p>
        </div>

        <!-- Main Layout -->
        <div class="onboarding-wrapper">
            
            <!-- Sidebar Navigation -->
            <aside class="step-sidebar hidden lg:block">
                <div class="space-y-2">
                    <div class="step-item active" data-step="0">
                        <i class="fa-solid fa-circle-dot step-icon"></i>
                        <span class="font-medium">Business Profile</span>
                    </div>
                    <div class="step-item inactive" data-step="1">
                        <i class="fa-solid fa-circle step-icon"></i>
                        <span class="font-medium">Contact Details</span>
                    </div>
                    <div class="step-item inactive" data-step="2">
                        <i class="fa-solid fa-circle step-icon"></i>
                        <span class="font-medium">Branding</span>
                    </div>
                    <div class="step-item inactive" data-step="3">
                        <i class="fa-solid fa-circle step-icon"></i>
                        <span class="font-medium">Project Data</span>
                    </div>
                    <div class="step-item inactive" data-step="4">
                        <i class="fa-solid fa-circle step-icon"></i>
                        <span class="font-medium">Quotation</span>
                    </div>
                    <div class="step-item inactive" data-step="5">
                        <i class="fa-solid fa-circle step-icon"></i>
                        <span class="font-medium">Review & Submit</span>
                    </div>
                </div>

                <!-- Live Quote Summary (Visible on Desktop Sidebar) -->
                <div id="live-quote-summary" class="mt-8 p-4 bg-surface-100 rounded-lg border border-border">
                    <h4 class="text-sm font-semibold uppercase text-secondary mb-2">Estimated Cost</h4>
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm">Total:</span>
                        <span id="live-total" class="font-bold text-primary">R0.00</span>
                    </div>
                    <div class="text-xs text-secondary">
                        <div id="live-package">Package: Basic</div>
                        <div id="live-addons">Add-ons: 0</div>
                    </div>
                </div>
            </aside>

            <!-- Form Content -->
            <div class="form-content-area">
                
                <!-- Status Messages -->
                <div id="ajax-message" class="hidden"></div>
                <?php if (!empty($statusMessage)): ?>
                    <div class="alert alert-<?= $statusType === 'alert-success' ? 'success' : 'error' ?> mb-6 animate-slide-down">
                        <p class="alert-message"><?= htmlspecialchars($statusMessage) ?></p>
                    </div>
                <?php endif; ?>

                <!-- Mobile Progress Bar -->
                <div class="lg:hidden mb-6">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-semibold">Progress</span>
                        <span id="progress-text">0% Complete</span>
                    </div>
                    <div class="w-full bg-surface-200 rounded-full h-2.5">
                        <div id="progress-bar" class="bg-primary h-2.5 rounded-full" style="width: 0%"></div>
                    </div>
                </div>

                <div class="card p-8 md:p-12">
                    <form id="project-data-form" action="<?= BASE_PATH ?>/onboarding-submit" method="POST" enctype="multipart/form-data" data-project-id="<?= $data['projectId'] ?? 'new' ?>">
                        <input type="hidden" name="current_step" id="current_step" value="0">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

                        <!-- Step 1: Business Profile -->
                        <div class="step-content active" id="step-0">
                            <h3 class="heading-3 mb-6">1. Business Profile</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group col-span-2">
                                    <label for="company_name" class="form-label">Company Name / Trading Name <span class="text-error">*</span></label>
                                    <input type="text" id="company_name" name="company_name" class="form-input" required>
                                    <p class="text-xs text-error mt-1"></p>
                                </div>
                                
                                <div class="form-group col-span-2">
                                    <label for="products_services" class="form-label">Products/Services Offered <span class="text-error">*</span></label>
                                    <textarea id="products_services" name="products_services" rows="3" class="form-textarea" required></textarea>
                                    <p class="text-xs text-error mt-1"></p>
                                </div>

                                <div class="form-group col-span-2">
                                    <label for="mission_statement" class="form-label">Mission Statement</label>
                                    <textarea id="mission_statement" name="mission_statement" rows="2" class="form-textarea"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="industry" class="form-label">Industry <span class="text-error">*</span></label>
                                    <input type="text" id="industry" name="industry" class="form-input" required>
                                    <p class="text-xs text-error mt-1"></p>
                                </div>

                                <div class="form-group">
                                    <label for="competitors" class="form-label">Main Competitors</label>
                                    <input type="text" id="competitors" name="competitors" class="form-input" placeholder="Comma-separated">
                                </div>

                                <div class="form-group col-span-2">
                                    <label for="usp" class="form-label">Unique Selling Point (USP)</label>
                                    <textarea id="usp" name="usp" rows="2" class="form-textarea"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Contact Details -->
                        <div class="step-content" id="step-1">
                            <h3 class="heading-3 mb-6">2. Contact Details</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label for="rep_name" class="form-label">Representative Name <span class="text-error">*</span></label>
                                    <input type="text" id="rep_name" name="rep_name" class="form-input" required>
                                    <p class="text-xs text-error mt-1"></p>
                                </div>

                                <div class="form-group">
                                    <label for="rep_role" class="form-label">Role/Position <span class="text-error">*</span></label>
                                    <input type="text" id="rep_role" name="rep_role" class="form-input" required>
                                    <p class="text-xs text-error mt-1"></p>
                                </div>

                                <div class="form-group">
                                    <label for="rep_email" class="form-label">Email Address <span class="text-error">*</span></label>
                                    <input type="email" id="rep_email" name="rep_email" class="form-input" required>
                                    <p class="text-xs text-error mt-1"></p>
                                </div>

                                <div class="form-group">
                                    <label for="rep_phone" class="form-label">Phone Number</label>
                                    <input type="tel" id="rep_phone" name="rep_phone" class="form-input">
                                </div>

                                <div class="form-group">
                                    <label for="rep_whatsapp" class="form-label">WhatsApp Number</label>
                                    <input type="tel" id="rep_whatsapp" name="rep_whatsapp" class="form-input">
                                </div>

                                <div class="form-group">
                                    <label for="comm_method" class="form-label">Preferred Communication</label>
                                    <select id="comm_method" name="comm_method" class="form-input">
                                        <option value="email">Email</option>
                                        <option value="whatsapp">WhatsApp</option>
                                        <option value="phone">Phone Call</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Branding -->
                        <div class="step-content" id="step-2">
                            <h3 class="heading-3 mb-6">3. Branding Materials</h3>

                            <div class="alert alert-info mb-6">
                                <i class="bi bi-info-circle mr-2"></i>
                                <span class="text-sm">We'll request your high-res logo and assets later via the client dashboard.</span>
                            </div>

                            <div class="grid grid-cols-1 gap-6">
                                <div class="form-group">
                                    <label for="brand_colors" class="form-label">Brand Colors</label>
                                    <input type="text" id="brand_colors" name="brand_colors" class="form-input" placeholder="e.g., #0044cc, #ffffff">
                                    <p class="text-xs text-secondary mt-1">Hex codes or general description.</p>
                                </div>

                                <div class="form-group">
                                    <label for="fonts" class="form-label">Preferred Fonts</label>
                                    <input type="text" id="fonts" name="fonts" class="form-input" placeholder="e.g., Roboto, Open Sans">
                                </div>
                                
                                <div class="form-group">
                                    <label for="brand_guidelines" class="form-label">Upload Brand Guidelines (PDF/Doc, Optional)</label>
                                    <input type="file" id="brand_guidelines" name="brand_guidelines" class="form-input" accept=".pdf,.doc,.docx">
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Project Data -->
                        <div class="step-content" id="step-3">
                            <h3 class="heading-3 mb-6">4. Project Data</h3>
                            
                            <div class="space-y-6">
                                <div class="form-group">
                                    <label for="project_title" class="form-label">Project Title <span class="text-error">*</span></label>
                                    <input type="text" id="project_title" name="project_title" class="form-input" required>
                                    <p class="text-xs text-error mt-1"></p>
                                </div>

                                <div class="form-group" data-required-group>
                                    <label class="form-label mb-2 block">Website Objectives <span class="text-error">*</span></label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <label class="form-checkbox-label flex items-center p-3 cursor-pointer">
                                            <input type="checkbox" name="objectives[]" value="lead_generation" class="form-checkbox mr-3">
                                            <span>Lead Generation</span>
                                        </label>
                                        <label class="form-checkbox-label flex items-center p-3 cursor-pointer">
                                            <input type="checkbox" name="objectives[]" value="ecommerce_sales" class="form-checkbox mr-3">
                                            <span>E-commerce Sales</span>
                                        </label>
                                        <label class="form-checkbox-label flex items-center p-3 cursor-pointer">
                                            <input type="checkbox" name="objectives[]" value="brand_awareness" class="form-checkbox mr-3">
                                            <span>Brand Awareness</span>
                                        </label>
                                        <label class="form-checkbox-label flex items-center p-3 cursor-pointer">
                                            <input type="checkbox" name="objectives[]" value="online_booking" class="form-checkbox mr-3">
                                            <span>Online Booking</span>
                                        </label>
                                        <label class="form-checkbox-label flex items-center p-3 cursor-pointer">
                                            <input type="checkbox" name="objectives[]" value="portfolio_showcase" class="form-checkbox mr-3">
                                            <span>Portfolio Showcase</span>
                                        </label>
                                    </div>
                                    <p class="text-xs text-error mt-1"></p>
                                </div>

                                <div class="form-group">
                                    <label class="form-label mb-2 block">Required Pages</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                        <label class="flex items-center"><input type="checkbox" name="pages[]" value="home" checked class="mr-2"> Home Page</label>
                                        <label class="flex items-center"><input type="checkbox" name="pages[]" value="about" checked class="mr-2"> About Us</label>
                                        <label class="flex items-center"><input type="checkbox" name="pages[]" value="services" class="mr-2"> Services</label>
                                        <label class="flex items-center"><input type="checkbox" name="pages[]" value="contact" checked class="mr-2"> Contact Us</label>
                                        <label class="flex items-center"><input type="checkbox" name="pages[]" value="blog" class="mr-2"> Blog</label>
                                        <label class="flex items-center"><input type="checkbox" name="pages[]" value="gallery" class="mr-2"> Gallery</label>
                                        <label class="flex items-center"><input type="checkbox" name="pages[]" value="faq" class="mr-2"> FAQ</label>
                                        <label class="flex items-center"><input type="checkbox" name="pages[]" value="custom_1" class="mr-2"> Custom Page 1</label>
                                        <label class="flex items-center"><input type="checkbox" name="pages[]" value="custom_2" class="mr-2"> Custom Page 2</label>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label mb-2 block">Features & Functionality</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <label class="form-checkbox-label flex items-center p-3 cursor-pointer">
                                            <input type="checkbox" name="features[]" value="social_media" class="form-checkbox mr-3">
                                            <span>Social Media Integration</span>
                                        </label>
                                        <label class="form-checkbox-label flex items-center p-3 cursor-pointer">
                                            <input type="checkbox" name="features[]" value="whatsapp_button" class="form-checkbox mr-3">
                                            <span>WhatsApp Button</span>
                                        </label>
                                        <label class="form-checkbox-label flex items-center p-3 cursor-pointer">
                                            <input type="checkbox" name="features[]" value="custom_contact_form" class="form-checkbox mr-3">
                                            <span>Custom Contact Form</span>
                                        </label>
                                        <label class="form-checkbox-label flex items-center p-3 cursor-pointer">
                                            <input type="checkbox" name="features[]" value="complex_form" class="form-checkbox mr-3">
                                            <span>Complex Form (Quote/Survey)</span>
                                        </label>
                                        <label class="form-checkbox-label flex items-center p-3 cursor-pointer">
                                            <input type="checkbox" name="features[]" value="live_chat" class="form-checkbox mr-3">
                                            <span>Live Chat</span>
                                        </label>
                                        <label class="form-checkbox-label flex items-center p-3 cursor-pointer">
                                            <input type="checkbox" name="features[]" value="ecommerce" class="form-checkbox mr-3">
                                            <span>E-commerce</span>
                                        </label>
                                        <label class="form-checkbox-label flex items-center p-3 cursor-pointer">
                                            <input type="checkbox" name="features[]" value="booking_system" class="form-checkbox mr-3">
                                            <span>Booking System</span>
                                        </label>
                                        <label class="form-checkbox-label flex items-center p-3 cursor-pointer">
                                            <input type="checkbox" name="features[]" value="multi_language" class="form-checkbox mr-3">
                                            <span>Multi-Language</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="tech_req" class="form-label">Technical Requirements</label>
                                    <textarea id="tech_req" name="tech_req" rows="3" class="form-textarea" placeholder="CMS preference, integrations, etc."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5: Quotation -->
                        <div class="step-content" id="step-4">
                            <h3 class="heading-3 mb-6">5. Quotation & Hosting</h3>
                            <p class="text-secondary mb-4">Based on your input, here is the preliminary quote.</p>
                            
                            <div id="quotation-details" class="mb-6">
                                <!-- Populated by JS -->
                                <div class="flex justify-center p-8">
                                    <div class="spinner spinner-primary"></div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="flex items-center p-4 border rounded-lg bg-surface-50 cursor-pointer">
                                    <input type="checkbox" id="confirmation" name="confirmation" value="1" class="form-checkbox mr-3" required>
                                    <span class="text-sm">I confirm that the information provided is correct and I understand this is a preliminary quote.</span>
                                </label>
                                <p class="text-xs text-error mt-1"></p>
                            </div>
                        </div>

                        <!-- Step 6: Final Submission -->
                        <div class="step-content" id="step-5">
                            <h3 class="heading-3 mb-6">6. Final Review</h3>
                            
                            <div class="bg-surface-50 p-6 rounded-lg border border-border mb-6">
                                <h4 class="font-semibold mb-4">Summary</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-secondary block">Company:</span>
                                        <span id="review-company" class="font-medium"></span>
                                    </div>
                                    <div>
                                        <span class="text-secondary block">Contact:</span>
                                        <span id="review-contact" class="font-medium"></span>
                                    </div>
                                    <div>
                                        <span class="text-secondary block">Email:</span>
                                        <span id="review-email" class="font-medium"></span>
                                    </div>
                                </div>
                            </div>

                            <div id="final-quotation-table" class="mb-8">
                                <!-- Populated by JS -->
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="form-navigation-footer mt-8 flex justify-between items-center">
                            <button type="button" id="prevBtn" class="btn btn-secondary hidden">
                                <i class="fa-solid fa-arrow-left mr-2"></i> Previous
                            </button>
                            
                            <button type="button" id="nextBtn" class="btn btn-primary ml-auto">
                                Next Step <i class="fa-solid fa-arrow-right ml-2"></i>
                            </button>
                            
                            <button type="submit" id="submitBtn" class="btn btn-success ml-auto hidden">
                                Submit Project <i class="fa-solid fa-check ml-2"></i>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Loading Overlay -->
<div id="loading-overlay" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center">
    <div class="bg-surface-100 p-6 rounded-lg shadow-xl flex flex-col items-center">
        <div class="spinner spinner-primary mb-3"></div>
        <p class="text-secondary font-medium">Saving progress...</p>
    </div>
</div>

<!-- Autosave Indicator -->
<div id="autosave-indicator" class="hidden">
    <span id="autosave-text">Saved</span>
</div>

<!-- Scripts -->
<script src="<?= BASE_PATH ?>/assets/js/onboarding.js"></script>
<script src="<?= BASE_PATH ?>/assets/js/quote-calculator.js"></script>
<link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/onboarding.css">