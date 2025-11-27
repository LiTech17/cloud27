<!-- Step 10: File Uploads & Review -->
<h2 class="heading-4 mb-6">
    <i class="fa-solid fa-file-upload mr-2" style="color: var(--clr-primary);"></i><?= e($steps[9]) ?>
</h2>

<div class="space-y-6">
    <div class="form-group">
        <label for="brand_guidelines" class="form-label">
            Brand Guidelines
            <span class="text-secondary text-sm font-normal">(PDF, DOC, or images. Max 10MB)</span>
        </label>
        <input type="file" id="brand_guidelines" name="brand_guidelines" 
               class="file-input w-full <?= isset($errors['brand_guidelines']) ? 'form-input-error' : '' ?>"
               accept=".pdf,.doc,.docx,.png,.jpg,.jpeg"
               data-max-size="10485760">
        <div class="file-info text-sm text-secondary mt-1"></div>
        <p class="text-xs text-error mt-1" data-field="brand_guidelines"><?= htmlspecialchars($errors['brand_guidelines'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label for="business_profile" class="form-label">
            Business Profile
            <span class="text-secondary text-sm font-normal">(PDF or DOC. Max 10MB)</span>
        </label>
        <input type="file" id="business_profile" name="business_profile" 
               class="file-input w-full <?= isset($errors['business_profile']) ? 'form-input-error' : '' ?>"
               accept=".pdf,.doc,.docx"
               data-max-size="10485760">
        <div class="file-info text-sm text-secondary mt-1"></div>
        <p class="text-xs text-error mt-1" data-field="business_profile"><?= htmlspecialchars($errors['business_profile'] ?? '') ?></p>
    </div>

    <!-- Review Summary Section -->
    <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg mt-8">
        <h3 class="text-lg font-semibold mb-4" style="color: var(--clr-text);">Review Your Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <strong>Company:</strong> <span id="review-company"><?= e($data['company_name'] ?? 'Not provided') ?></span>
            </div>
            <div>
                <strong>Contact:</strong> <span id="review-contact"><?= e($data['contact_name'] ?? 'Not provided') ?></span>
            </div>
            <div>
                <strong>Email:</strong> <span id="review-email"><?= e($data['contact_email'] ?? 'Not provided') ?></span>
            </div>
            <div>
                <strong>Budget:</strong> <span id="review-budget"><?= e($fieldOptions['budget_ranges'][$data['budget_range'] ?? ''] ?? 'Not specified') ?></span>
            </div>
        </div>
        <p class="text-secondary text-sm mt-4">
            Please review all information before submitting. You can use the navigation to go back and make changes.
        </p>
    </div>
</div>

<script>
// Update review section dynamically
document.addEventListener('DOMContentLoaded', function() {
    function updateReviewSection() {
        document.getElementById('review-company').textContent = document.getElementById('company_name')?.value || 'Not provided';
        document.getElementById('review-contact').textContent = document.getElementById('contact_name')?.value || 'Not provided';
        document.getElementById('review-email').textContent = document.getElementById('contact_email')?.value || 'Not provided';
        
        const budgetSelect = document.getElementById('budget_range');
        const budgetText = budgetSelect?.options[budgetSelect.selectedIndex]?.text || 'Not specified';
        document.getElementById('review-budget').textContent = budgetText;
    }

    // Update review when form changes
    const form = document.getElementById('project-data-form');
    form.addEventListener('input', updateReviewSection);
    form.addEventListener('change', updateReviewSection);
    
    // Initial update
    updateReviewSection();
});
</script>