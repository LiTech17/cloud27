<?php
// views/admin/package_edit.php

/**
 * @var array $data Contains data passed from the controller, including 'package' and 'error'
 */
include VIEW_PATH . '_partials/AdminNav.php';

// Extract data and variables
$package = $data['package'] ?? [];
$error = $_SESSION['error'] ?? ($data['error'] ?? null);
unset($_SESSION['error']);

$isEdit = isset($package['id']);
$formAction = BASE_PATH . '/admin/packages/save';

// Default values for new package
$package = [
    'id' => $package['id'] ?? null,
    'title' => $package['title'] ?? '',
    'price_base' => $package['price_base'] ?? '',
    'price_hosting_monthly' => $package['price_hosting_monthly'] ?? '',
    'pages_count' => $package['pages_count'] ?? '',
    'tag' => $package['tag'] ?? '',
    'features_string' => $package['features_string'] ?? "Free .co.za domain registration\n1 month free hosting\nSSL Encryption Certificate"
];

$title = $isEdit ? '✏️ Edit Package: ' . htmlspecialchars($package['title']) : '✨ Add New Package';
?>

<section class="section">
    <div class="container-lg">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="heading-2 mb-2"><?= $title ?></h2>
                <p class="text-secondary">Fill in the details below to <?= $isEdit ? 'update' : 'create' ?> a pricing package</p>
            </div>
            <a href="<?= BASE_PATH ?>/admin/packages" class="btn btn-ghost">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Packages
            </a>
        </div>

        <!-- Error Alert -->
        <?php if (!empty($error)): ?>
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
            <form method="POST" action="<?= $formAction ?>" id="packageForm">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($package['id']) ?>">
                <?php endif; ?>

                <!-- Basic Information Section -->
                <div class="mb-8">
                    <h3 class="heading-3 mb-4 pb-3 border-b">Basic Information</h3>
                    
                    <div class="form-group">
                        <label for="title" class="form-label form-label-required">Package Title</label>
                        <input type="text" 
                               class="form-input" 
                               id="title" 
                               name="title" 
                               value="<?= htmlspecialchars($package['title']) ?>" 
                               placeholder="e.g., Starter Package, Premium Package"
                               required>
                        <span class="form-helper">Give your package a descriptive, customer-friendly name</span>
                    </div>
                </div>

                <!-- Pricing Section -->
                <div class="mb-8">
                    <h3 class="heading-3 mb-4 pb-3 border-b">Pricing Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="price_base" class="form-label form-label-required">Base Setup Price (R)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-tertiary font-semibold">R</span>
                                <input type="number" 
                                       step="0.01" 
                                       class="form-input" 
                                       style="padding-left: 2.5rem;"
                                       id="price_base" 
                                       name="price_base" 
                                       value="<?= htmlspecialchars($package['price_base']) ?>" 
                                       placeholder="1800.00"
                                       required>
                            </div>
                            <span class="form-helper">One-time setup fee for the website</span>
                        </div>

                        <div class="form-group">
                            <label for="price_hosting_monthly" class="form-label form-label-required">Monthly Hosting Price (R)</label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-tertiary font-semibold">R</span>
                                <input type="number" 
                                       step="0.01" 
                                       class="form-input"
                                       style="padding-left: 2.5rem;"
                                       id="price_hosting_monthly" 
                                       name="price_hosting_monthly" 
                                       value="<?= htmlspecialchars($package['price_hosting_monthly']) ?>" 
                                       placeholder="39.00"
                                       required>
                            </div>
                            <span class="form-helper">Recurring monthly hosting cost</span>
                        </div>
                    </div>
                </div>

                <!-- Package Details Section -->
                <div class="mb-8">
                    <h3 class="heading-3 mb-4 pb-3 border-b">Package Details</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="pages_count" class="form-label form-label-required">Pages Count Description</label>
                            <input type="text" 
                                   class="form-input" 
                                   id="pages_count" 
                                   name="pages_count" 
                                   value="<?= htmlspecialchars($package['pages_count']) ?>" 
                                   placeholder="e.g., 3 Pages, Up to 10 Pages"
                                   required>
                            <span class="form-helper">Describe the number of pages included</span>
                        </div>

                        <div class="form-group">
                            <label for="tag" class="form-label">Package Tag (Optional)</label>
                            <input type="text" 
                                   class="form-input" 
                                   id="tag" 
                                   name="tag" 
                                   value="<?= htmlspecialchars($package['tag']) ?>"
                                   placeholder="e.g., POPULAR, BEST VALUE">
                            <span class="form-helper">Display a badge on this package (leave empty for no badge)</span>
                        </div>
                    </div>
                </div>

                <!-- Features Section -->
                <div class="mb-8">
                    <h3 class="heading-3 mb-4 pb-3 border-b">Package Features</h3>
                    
                    <div class="form-group">
                        <label for="features" class="form-label form-label-required">Features List</label>
                        <textarea class="form-input" 
                                  id="features" 
                                  name="features_string" 
                                  rows="12" 
                                  placeholder="Free .co.za domain registration&#10;1 month free hosting&#10;SSL Encryption Certificate&#10;Mobile responsive design&#10;Contact form integration"
                                  required><?= htmlspecialchars($package['features_string']) ?></textarea>
                        <span class="form-helper">
                            <strong>Enter one feature per line.</strong> These will be displayed as bullet points on your website.
                        </span>
                    </div>

                    <!-- Feature Preview -->
                    <div class="card bg-secondary mt-4 p-4">
                        <h4 class="font-semibold mb-3 text-sm text-tertiary">Preview:</h4>
                        <ul class="space-y-2" id="features-preview">
                            <?php 
                            $features = explode("\n", $package['features_string']);
                            foreach (array_filter($features) as $feature): 
                            ?>
                                <li class="flex items-start gap-2 text-sm">
                                    <span class="text-success font-bold" style="flex-shrink: 0;">✓</span>
                                    <span><?= htmlspecialchars(trim($feature)) ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                    <button type="submit" class="btn btn-primary btn-lg flex-1 sm:flex-initial">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M5 13l4 4L19 7" />
                        </svg>
                        <?= $isEdit ? 'Update Package' : 'Create Package' ?>
                    </button>
                    <a href="<?= BASE_PATH ?>/admin/packages" class="btn btn-ghost btn-lg flex-1 sm:flex-initial">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
.w-5 { width: 1.25rem; }
.h-5 { height: 1.25rem; }
.space-y-2 > * + * { margin-top: 0.5rem; }
.relative { position: relative; }
.absolute { position: absolute; }
.left-3 { left: 0.75rem; }
.top-1\/2 { top: 50%; }
.transform { transform: translateY(-50%); }
</style>

<script>
// Live preview of features
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('features');
    const preview = document.getElementById('features-preview');
    
    if (textarea && preview) {
        textarea.addEventListener('input', function() {
            const features = this.value.split('\n').filter(f => f.trim());
            preview.innerHTML = features.map(feature => `
                <li class="flex items-start gap-2 text-sm">
                    <span class="text-success font-bold" style="flex-shrink: 0;">✓</span>
                    <span>${feature.trim().replace(/</g, '&lt;').replace(/>/g, '&gt;')}</span>
                </li>
            `).join('');
            
            if (features.length === 0) {
                preview.innerHTML = '<li class="text-tertiary text-sm">No features added yet...</li>';
            }
        });
    }
});
</script>

<?php 
include VIEW_PATH . '_partials/Footer.php';
?>