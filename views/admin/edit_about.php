<?php 
// views/admin/edit_about.php

/**
 * @var array $data Contains about content and error messages
 */
include VIEW_PATH . '_partials/AdminNav.php';

$content = $data['content'] ?? [];
$error = $data['error'] ?? null;
?>

<section class="section">
    <div class="container-lg">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="heading-2 mb-2">✏️ Edit About Content</h2>
                <p class="text-secondary">Update your company information and images</p>
            </div>
            <a href="<?= BASE_PATH ?>/admin/about" class="btn btn-ghost">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to About Management
            </a>
        </div>

        <!-- Error Alert -->
        <?php if ($error): ?>
            <div class="alert alert-error mb-6 animate-slide-down">
                <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="alert-content">
                    <p class="alert-message"><?= htmlspecialchars($error) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form Card -->
        <div class="card shadow-lg">
            <form method="POST" action="<?= BASE_PATH ?>/admin/about/save" enctype="multipart/form-data" id="aboutForm">
                
                <!-- Basic Information -->
                <div class="mb-8">
                    <h3 class="heading-3 mb-4 pb-3 border-b">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="company_name" class="form-label form-label-required">Company Name</label>
                            <input type="text" 
                                   id="company_name" 
                                   name="company_name" 
                                   class="form-input" 
                                   value="<?= htmlspecialchars($content['company_name'] ?? '') ?>"
                                   required>
                        </div>

                        <div class="form-group">
                            <label for="tagline" class="form-label">Tagline</label>
                            <input type="text" 
                                   id="tagline" 
                                   name="tagline" 
                                   class="form-input" 
                                   value="<?= htmlspecialchars($content['tagline'] ?? '') ?>"
                                   placeholder="e.g., Building the Future, Today">
                        </div>
                    </div>
                </div>

                <!-- About Text & Mission -->
                <div class="mb-8">
                    <h3 class="heading-3 mb-4 pb-3 border-b">Content</h3>
                    
                    <div class="form-group mb-6">
                        <label for="about_text" class="form-label form-label-required">About Text</label>
                        <textarea id="about_text" 
                                  name="about_text" 
                                  class="form-textarea" 
                                  rows="8"
                                  required><?= htmlspecialchars($content['about_text'] ?? '') ?></textarea>
                        <span class="form-helper">Main content about your company (use line breaks for paragraphs)</span>
                    </div>

                    <div class="form-group">
                        <label for="mission_statement" class="form-label">Mission Statement</label>
                        <textarea id="mission_statement" 
                                  name="mission_statement" 
                                  class="form-textarea" 
                                  rows="4"><?= htmlspecialchars($content['mission_statement'] ?? '') ?></textarea>
                        <span class="form-helper">Your company's mission statement</span>
                    </div>
                </div>

                <!-- Company Details -->
                <div class="mb-8">
                    <h3 class="heading-3 mb-4 pb-3 border-b">Company Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="form-group">
                            <label for="founded_year" class="form-label">Founded Year</label>
                            <input type="text" 
                                   id="founded_year" 
                                   name="founded_year" 
                                   class="form-input" 
                                   value="<?= htmlspecialchars($content['founded_year'] ?? '') ?>"
                                   placeholder="<?= date('Y') ?>"
                                   maxlength="4">
                        </div>

                        <div class="form-group">
                            <label for="employee_count" class="form-label">Team Size</label>
                            <input type="text" 
                                   id="employee_count" 
                                   name="employee_count" 
                                   class="form-input" 
                                   value="<?= htmlspecialchars($content['employee_count'] ?? '') ?>"
                                   placeholder="e.g., 10-50, 100+">
                        </div>

                        <div class="form-group">
                            <label for="office_location" class="form-label">Office Location</label>
                            <input type="text" 
                                   id="office_location" 
                                   name="office_location" 
                                   class="form-input" 
                                   value="<?= htmlspecialchars($content['office_location'] ?? '') ?>"
                                   placeholder="e.g., Blantyre, Malawi">
                        </div>
                    </div>
                </div>

                <!-- Images -->
                <div class="mb-8">
                    <h3 class="heading-3 mb-4 pb-3 border-b">Images</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Hero Image -->
                        <div class="form-group">
                            <label for="hero_image" class="form-label">Hero Image</label>
                            <?php if (!empty($content['hero_image'])): ?>
                                <div class="mb-3">
                                    <p class="text-sm text-secondary mb-2">Current image:</p>
                                    <img src="<?= BASE_PATH ?>/uploads/about/<?= htmlspecialchars($content['hero_image']) ?>" 
                                         alt="Current Hero" 
                                         class="w-32 h-32 object-cover rounded-lg shadow">
                                </div>
                            <?php endif; ?>
                            <input type="file" 
                                   id="hero_image" 
                                   name="hero_image" 
                                   class="form-input" 
                                   accept="image/png,image/jpeg,image/jpg,image/webp">
                            <span class="form-helper">
                                Recommended: Square image, 400x400px minimum. Max 5MB. (PNG, JPG, WEBP)
                            </span>
                        </div>

                        <!-- Company Image -->
                        <div class="form-group">
                            <label for="company_image" class="form-label">Company Image</label>
                            <?php if (!empty($content['company_image'])): ?>
                                <div class="mb-3">
                                    <p class="text-sm text-secondary mb-2">Current image:</p>
                                    <img src="<?= BASE_PATH ?>/uploads/about/<?= htmlspecialchars($content['company_image']) ?>" 
                                         alt="Current Company" 
                                         class="w-full h-32 object-cover rounded-lg shadow">
                                </div>
                            <?php endif; ?>
                            <input type="file" 
                                   id="company_image" 
                                   name="company_image" 
                                   class="form-input" 
                                   accept="image/png,image/jpeg,image/jpg,image/webp">
                            <span class="form-helper">
                                Recommended: Landscape, 1200x600px minimum. Max 5MB. (PNG, JPG, WEBP)
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                    <button type="submit" class="btn btn-primary btn-lg flex-1 sm:flex-initial" id="submitBtn">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M5 13l4 4L19 7" />
                        </svg>
                        <span id="btnText">Save Changes</span>
                    </button>
                    <a href="<?= BASE_PATH ?>/admin/about" class="btn btn-ghost btn-lg flex-1 sm:flex-initial">
                        Cancel
                    </a>
                    <a href="<?= BASE_PATH ?>/about" class="btn btn-outline btn-lg flex-1 sm:flex-initial ml-auto" target="_blank">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Preview Page
                    </a>
                </div>
            </form>
        </div>

        <!-- Help Card -->
        <div class="card bg-info-light mt-6">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 flex-shrink-0" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="flex-1">
                    <h4 class="font-semibold mb-2" style="color: var(--color-info);">Content Tips</h4>
                    <ul class="text-sm space-y-1" style="color: var(--color-info); opacity: 0.9;">
                        <li>• Keep your about text concise but informative (300-500 words ideal)</li>
                        <li>• Use high-quality images that represent your brand</li>
                        <li>• Update your content regularly to keep it fresh</li>
                        <li>• Include keywords naturally for better SEO</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.w-5 { width: 1.25rem; }
.h-5 { height: 1.25rem; }
.w-6 { width: 1.5rem; }
.h-6 { height: 1.5rem; }
.w-32 { width: 8rem; }
.h-32 { height: 8rem; }
.space-y-1 > * + * { margin-top: 0.25rem; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('aboutForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    
    // Form submission loading state
    if (form) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            btnText.textContent = 'Saving...';
        });
    }
    
    // Image preview functionality
    function setupImagePreview(inputId) {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        console.log(`${inputId} selected:`, e.target.result.substring(0, 50));
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    }
    
    setupImagePreview('hero_image');
    setupImagePreview('company_image');
});
</script>

<?php 
include VIEW_PATH . '_partials/Footer.php';
?>