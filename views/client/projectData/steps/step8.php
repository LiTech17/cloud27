<!-- Step 8: Content & Support -->
<h2 class="heading-4 mb-6">
    <i class="fa-solid fa-life-ring mr-2" style="color: var(--clr-primary);"></i><?= e($steps[7]) ?>
</h2>

<div class="space-y-6">
    <div class="form-group">
        <label for="content_existing" class="form-label">
            Do you have existing content?
        </label>
        <select id="content_existing" name="content_existing" 
                 class="form-select <?= isset($errors['content_existing']) ? 'form-input-error' : '' ?>">
            <option value="">Select...</option>
            <option value="yes" <?= ($data['content_existing'] ?? '') === 'yes' ? 'selected' : '' ?>>Yes, content is ready</option>
            <option value="partial" <?= ($data['content_existing'] ?? '') === 'partial' ? 'selected' : '' ?>>Partial content available</option>
            <option value="no" <?= ($data['content_existing'] ?? '') === 'no' ? 'selected' : '' ?>>No, need content creation</option>
        </select>
        <p class="text-xs text-error mt-1" data-field="content_existing"><?= htmlspecialchars($errors['content_existing'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label for="ongoing_support" class="form-label">
            Do you need ongoing maintenance & support?
        </label>
        <select id="ongoing_support" name="ongoing_support" 
                 class="form-select <?= isset($errors['ongoing_support']) ? 'form-input-error' : '' ?>">
            <option value="">Select...</option>
            <option value="yes" <?= ($data['ongoing_support'] ?? '') === 'yes' ? 'selected' : '' ?>>Yes, ongoing support needed</option>
            <option value="no" <?= ($data['ongoing_support'] ?? '') === 'no' ? 'selected' : '' ?>>No, one-time project</option>
            <option value="unsure" <?= ($data['ongoing_support'] ?? '') === 'unsure' ? 'selected' : '' ?>>Unsure, need to discuss</option>
        </select>
        <p class="text-xs text-error mt-1" data-field="ongoing_support"><?= htmlspecialchars($errors['ongoing_support'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label for="additional_notes" class="form-label">
            Additional Notes or Requirements
        </label>
        <textarea id="additional_notes" name="additional_notes" rows="4"
                  class="form-textarea <?= isset($errors['additional_notes']) ? 'form-input-error' : '' ?>"
                  placeholder="Any other information you'd like to share..."><?= e($data['additional_notes'] ?? '') ?></textarea>
        <p class="text-xs text-error mt-1" data-field="additional_notes"><?= htmlspecialchars($errors['additional_notes'] ?? '') ?></p>
    </div>
</div>