<?php 
// views/admin/edit_team_member.php

/**
 * @var array $data Contains team member data and error messages
 */
include VIEW_PATH . '_partials/AdminNav.php';

$member = $data['member'] ?? null;
$error = $data['error'] ?? null;
$isEdit = $member !== null;
$title = $isEdit ? 'Edit Team Member: ' . htmlspecialchars($member['name']) : 'Add New Team Member';
?>

<section class="section">
    <div class="container-md">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h2 class="heading-2 mb-2"><?= $isEdit ? '✏️' : '➕' ?> <?= htmlspecialchars($title) ?></h2>
                <p class="text-secondary">Fill in the team member details below</p>
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
            <form method="POST" action="<?= BASE_PATH ?>/admin/about/team/save" enctype="multipart/form-data" id="teamForm">
                
                <?php if ($isEdit): ?>
                    <input type="hidden" name="id" value="<?= htmlspecialchars($member['id']) ?>">
                <?php endif; ?>

                <!-- Profile Image -->
                <div class="mb-8 text-center">
                    <div class="mb-4">
                        <?php if ($isEdit && !empty($member['image_path'])): ?>
                            <img src="<?= BASE_PATH ?>/uploads/about/<?= htmlspecialchars($member['image_path']) ?>" 
                                 alt="<?= htmlspecialchars($member['name']) ?>"
                                 id="preview-image"
                                 class="w-32 h-32 mx-auto rounded-full object-cover shadow-lg">
                        <?php else: ?>
                            <div id="preview-placeholder" class="w-32 h-32 mx-auto rounded-full bg-brand-light flex items-center justify-center">
                                <svg class="w-16 h-16" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <img id="preview-image" class="w-32 h-32 mx-auto rounded-full object-cover shadow-lg hidden">
                        <?php endif; ?>
                    </div>

                    <div class="form-group inline-block">
                        <label for="image" class="btn btn-outline btn-sm cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Choose Photo
                        </label>
                        <input type="file" 
                               id="image" 
                               name="image" 
                               class="hidden" 
                               accept="image/png,image/jpeg,image/jpg,image/webp"
                               onchange="previewImage(this)">
                    </div>
                    <p class="text-xs text-tertiary mt-2">Square image recommended (400x400px). Max 5MB.</p>
                </div>

                <!-- Basic Information -->
                <div class="mb-8">
                    <h3 class="heading-3 mb-4 pb-3 border-b">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="form-group">
                            <label for="name" class="form-label form-label-required">Full Name</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="form-input" 
                                   value="<?= htmlspecialchars($member['name'] ?? '') ?>"
                                   placeholder="John Doe"
                                   required
                                   autofocus>
                        </div>

                        <div class="form-group">
                            <label for="position" class="form-label form-label-required">Position/Title</label>
                            <input type="text" 
                                   id="position" 
                                   name="position" 
                                   class="form-input" 
                                   value="<?= htmlspecialchars($member['position'] ?? '') ?>"
                                   placeholder="CEO & Founder"
                                   required>
                        </div>
                    </div>

                    <div class="form-group mt-6">
                        <label for="display_order" class="form-label">Display Order</label>
                        <input type="number" 
                               id="display_order" 
                               name="display_order" 
                               class="form-input" 
                               value="<?= htmlspecialchars($member['display_order'] ?? '0') ?>"
                               min="0"
                               style="max-width: 200px;">
                        <span class="form-helper">Lower numbers appear first (0 = first)</span>
                    </div>
                </div>

                <!-- Biography -->
                <div class="mb-8">
                    <h3 class="heading-3 mb-4 pb-3 border-b">Biography</h3>
                    
                    <div class="form-group">
                        <label for="bio" class="form-label">Short Bio</label>
                        <textarea id="bio" 
                                  name="bio" 
                                  class="form-textarea" 
                                  rows="6"
                                  placeholder="Brief professional background and expertise (optional)"><?= htmlspecialchars($member['bio'] ?? '') ?></textarea>
                        <span class="form-helper">
                            Keep it concise - 2-3 sentences that highlight key achievements and expertise
                        </span>
                        <div class="mt-2 text-sm text-tertiary">
                            <span id="char-count">0</span> characters
                        </div>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="mb-8">
                    <h3 class="heading-3 mb-4 pb-3 border-b">Preview</h3>
                    <div class="card bg-secondary p-6 text-center">
                        <div class="mb-4">
                            <?php if ($isEdit && !empty($member['image_path'])): ?>
                                <img id="card-preview-image" 
                                     src="<?= BASE_PATH ?>/uploads/about/<?= htmlspecialchars($member['image_path']) ?>" 
                                     alt="Preview"
                                     class="w-24 h-24 mx-auto rounded-full object-cover shadow">
                            <?php else: ?>
                                <div id="card-preview-placeholder" class="w-24 h-24 mx-auto rounded-full bg-brand-light flex items-center justify-center">
                                    <svg class="w-12 h-12" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <img id="card-preview-image" class="w-24 h-24 mx-auto rounded-full object-cover shadow hidden">
                            <?php endif; ?>
                        </div>
                        <h4 class="font-semibold text-lg mb-1" id="preview-name">
                            <?= htmlspecialchars($member['name'] ?? 'Team Member Name') ?>
                        </h4>
                        <p class="text-sm font-semibold text-brand mb-3" id="preview-position">
                            <?= htmlspecialchars($member['position'] ?? 'Position') ?>
                        </p>
                        <p class="text-sm text-secondary" id="preview-bio">
                            <?= htmlspecialchars($member['bio'] ?? 'Bio will appear here...') ?>
                        </p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t">
                    <button type="submit" class="btn btn-primary btn-lg flex-1 sm:flex-initial" id="submitBtn">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M5 13l4 4L19 7" />
                        </svg>
                        <span id="btnText"><?= $isEdit ? 'Update Team Member' : 'Add Team Member' ?></span>
                    </button>
                    <a href="<?= BASE_PATH ?>/admin/about" class="btn btn-ghost btn-lg flex-1 sm:flex-initial">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
.w-4 { width: 1rem; }
.h-4 { height: 1rem; }
.w-5 { width: 1.25rem; }
.h-5 { height: 1.25rem; }
.w-12 { width: 3rem; }
.h-12 { height: 3rem; }
.w-16 { width: 4rem; }
.h-16 { height: 4rem; }
.w-24 { width: 6rem; }
.h-24 { height: 6rem; }
.w-32 { width: 8rem; }
.h-32 { height: 8rem; }
</style>

<script>
// Image preview functionality
function previewImage(input) {
    const previewImg = document.getElementById('preview-image');
    const previewPlaceholder = document.getElementById('preview-placeholder');
    const cardPreviewImg = document.getElementById('card-preview-image');
    const cardPreviewPlaceholder = document.getElementById('card-preview-placeholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            // Update main preview
            previewImg.src = e.target.result;
            previewImg.classList.remove('hidden');
            if (previewPlaceholder) {
                previewPlaceholder.classList.add('hidden');
            }
            
            // Update card preview
            if (cardPreviewImg) {
                cardPreviewImg.src = e.target.result;
                cardPreviewImg.classList.remove('hidden');
            }
            if (cardPreviewPlaceholder) {
                cardPreviewPlaceholder.classList.add('hidden');
            }
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const positionInput = document.getElementById('position');
    const bioInput = document.getElementById('bio');
    const previewName = document.getElementById('preview-name');
    const previewPosition = document.getElementById('preview-position');
    const previewBio = document.getElementById('preview-bio');
    const charCount = document.getElementById('char-count');
    
    // Update character count
    function updateCharCount() {
        const count = bioInput.value.length;
        charCount.textContent = count;
        
        if (count < 50) {
            charCount.className = 'text-warning';
        } else if (count > 300) {
            charCount.className = 'text-error';
        } else {
            charCount.className = 'text-success';
        }
    }
    
    // Live preview updates
    if (nameInput && previewName) {
        nameInput.addEventListener('input', function() {
            previewName.textContent = this.value || 'Team Member Name';
        });
    }
    
    if (positionInput && previewPosition) {
        positionInput.addEventListener('input', function() {
            previewPosition.textContent = this.value || 'Position';
        });
    }
    
    if (bioInput && previewBio) {
        bioInput.addEventListener('input', function() {
            previewBio.textContent = this.value || 'Bio will appear here...';
            updateCharCount();
        });
        updateCharCount();
    }
    
    // Form submission
    const form = document.getElementById('teamForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    
    if (form) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            btnText.textContent = 'Saving...';
        });
    }
});
</script>

<?php 
include VIEW_PATH . '_partials/Footer.php';
?>