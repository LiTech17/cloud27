<?php 
// views/admin/edit_service.php

/**
 * @var array $data Contains data passed from the controller
 */
include VIEW_PATH . '_partials/AdminNav.php';

$service = $data['service'] ?? null;
$error = $data['error'] ?? '';

$isEdit = $service !== null;
$formTitle = $isEdit ? 'Edit Service: ' . htmlspecialchars($service['title']) : 'Add New Service';

// Set values for the form fields
$titleValue = $service['title'] ?? '';
$descriptionValue = $service['description'] ?? '';
$idValue = $service['id'] ?? '';
?>

<section class="section">
    <div class="container-lg">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="heading-2 mb-2"><?= $isEdit ? '✏️' : '✨' ?> <?= htmlspecialchars($formTitle) ?></h2>
                <p class="text-secondary">Fill in the details below to <?= $isEdit ? 'update' : 'create' ?> a service</p>
            </div>
            <a href="<?= BASE_PATH ?>/admin/services" class="btn btn-ghost">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Services
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
            <form method="POST" action="<?= BASE_PATH ?>/admin/services/save" id="serviceForm">
                
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($idValue) ?>">
                <?php endif; ?>

                <!-- Service Title -->
                <div class="form-group mb-6">
                    <label for="title" class="form-label form-label-required">Service Title</label>
                    <input type="text" 
                           id="title" 
                           name="title" 
                           class="form-input" 
                           value="<?= htmlspecialchars($titleValue) ?>"
                           placeholder="e.g., Custom Web Development, Cloud Hosting Solutions"
                           required 
                           autofocus>
                    <span class="form-helper">Give your service a clear, descriptive name</span>
                </div>

                <!-- Service Description -->
                <div class="form-group mb-8">
                    <label for="description" class="form-label form-label-required">Service Description</label>
                    <textarea id="description" 
                              name="description" 
                              class="form-textarea" 
                              rows="12"
                              placeholder="Describe what this service includes, its benefits, and why customers should choose it..."
                              required><?= htmlspecialchars($descriptionValue) ?></textarea>
                    <span class="form-helper">
                        Provide a detailed description of the service. You can use multiple paragraphs.
                    </span>
                    
                    <!-- Character Counter -->
                    <div class="mt-2 text-sm text-tertiary">
                        <span id="char-count">0</span> characters
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="mb-8">
                    <h3 class="heading-3 mb-4 pb-3 border-b">Preview</h3>
                    <div class="card bg-secondary p-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-lg bg-brand-light flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-lg mb-2" id="preview-title">
                                    <?= htmlspecialchars($titleValue ?: 'Service Title') ?>
                                </h4>
                                <p class="text-sm text-secondary" id="preview-description">
                                    <?= htmlspecialchars($descriptionValue ?: 'Service description will appear here...') ?>
                                </p>
                            </div>
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
                        <span id="btnText"><?= $isEdit ? 'Save Changes' : 'Create Service' ?></span>
                    </button>
                    <a href="<?= BASE_PATH ?>/admin/services" class="btn btn-ghost btn-lg flex-1 sm:flex-initial">
                        Cancel
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
                    <h4 class="font-semibold mb-2" style="color: var(--color-info);">Tips for Great Service Descriptions</h4>
                    <ul class="text-sm space-y-1" style="color: var(--color-info); opacity: 0.9;">
                        <li>• Focus on benefits, not just features</li>
                        <li>• Use clear, jargon-free language</li>
                        <li>• Highlight what makes your service unique</li>
                        <li>• Keep it concise but informative (200-400 words ideal)</li>
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
.w-12 { width: 3rem; }
.h-12 { height: 3rem; }
.space-y-1 > * + * { margin-top: 0.25rem; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    const previewTitle = document.getElementById('preview-title');
    const previewDescription = document.getElementById('preview-description');
    const charCount = document.getElementById('char-count');
    
    // Update character count
    function updateCharCount() {
        const count = descriptionInput.value.length;
        charCount.textContent = count;
        
        // Color coding based on length
        if (count < 100) {
            charCount.className = 'text-warning';
        } else if (count > 1000) {
            charCount.className = 'text-error';
        } else {
            charCount.className = 'text-success';
        }
    }
    
    // Live preview updates
    if (titleInput && previewTitle) {
        titleInput.addEventListener('input', function() {
            previewTitle.textContent = this.value || 'Service Title';
        });
    }
    
    if (descriptionInput && previewDescription) {
        descriptionInput.addEventListener('input', function() {
            previewDescription.textContent = this.value || 'Service description will appear here...';
            updateCharCount();
        });
        
        // Initial count
        updateCharCount();
    }
    
    // Form submission
    const form = document.getElementById('serviceForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    
    if (form) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            btnText.textContent = 'Saving...';
        });
    }
    
    // Auto-save draft to localStorage (optional feature)
    let autoSaveTimeout;
    [titleInput, descriptionInput].forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                clearTimeout(autoSaveTimeout);
                autoSaveTimeout = setTimeout(() => {
                    // Save draft (optional - you can implement this)
                    console.log('Auto-saved draft');
                }, 2000);
            });
        }
    });
});
</script>

<?php 
include VIEW_PATH . '_partials/Footer.php';
?>