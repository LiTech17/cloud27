<!-- Step 3: Target Audience -->
<h2 class="heading-4 mb-6">
    <i class="fa-solid fa-users mr-2" style="color: var(--clr-primary);"></i><?= e($steps[2]) ?>
</h2>

<div class="form-group">
    <label for="target_audience" class="form-label">
        Describe your target audience <span class="text-error">*</span>
    </label>
    <textarea name="target_audience" rows="5" required
              class="form-textarea <?= isset($errors['target_audience']) ? 'form-input-error' : '' ?>"
              placeholder="Age range, demographics, interests, pain points..."><?= e($data['target_audience'] ?? '') ?></textarea>
    <p class="text-xs text-error mt-1" data-field="target_audience"><?= htmlspecialchars($errors['target_audience'] ?? '') ?></p>
</div>