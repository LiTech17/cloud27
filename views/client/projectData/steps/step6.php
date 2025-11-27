<!-- Step 6: Design Preferences -->
<h2 class="heading-4 mb-6">
    <i class="fa-solid fa-palette mr-2" style="color: var(--clr-primary);"></i><?= e($steps[5]) ?>
</h2>

<div class="form-group">
    <label for="design_preferences" class="form-label">
        Design Style Preferences <span class="text-error">*</span>
    </label>
    <textarea name="design_preferences" rows="4" required
              class="form-textarea <?= isset($errors['design_preferences']) ? 'form-input-error' : '' ?>"
              placeholder="Modern, minimalist, bold colors, specific color schemes, reference websites..."><?= e($data['design_preferences'] ?? '') ?></textarea>
    <p class="text-xs text-error mt-1" data-field="design_preferences"><?= htmlspecialchars($errors['design_preferences'] ?? '') ?></p>
</div>