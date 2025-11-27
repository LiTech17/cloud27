<!-- Step 9: Budget & Timeline -->
<h2 class="heading-4 mb-6">
    <i class="fa-solid fa-money-bill-wave mr-2" style="color: var(--clr-primary);"></i><?= e($steps[8]) ?>
</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="form-group">
        <label for="budget_range" class="form-label">Budget Range</label>
        <select id="budget_range" name="budget_range" 
                class="form-select <?= isset($errors['budget_range']) ? 'form-input-error' : '' ?>">
            <option value="">Select a budget range</option>
            <?php foreach ($fieldOptions['budget_ranges'] as $value => $label): ?>
                <option value="<?= e($value) ?>" <?= ($data['budget_range'] ?? '') === $value ? 'selected' : '' ?>>
                    <?= e($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="text-xs text-error mt-1" data-field="budget_range"><?= htmlspecialchars($errors['budget_range'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label for="timeline" class="form-label">Timeline</label>
        <select id="timeline" name="timeline" 
                class="form-select <?= isset($errors['timeline']) ? 'form-input-error' : '' ?>">
            <option value="">Select a timeline</option>
            <?php foreach ($fieldOptions['timelines'] as $value => $label): ?>
                <option value="<?= e($value) ?>" <?= ($data['timeline'] ?? '') === $value ? 'selected' : '' ?>>
                    <?= e($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="text-xs text-error mt-1" data-field="timeline"><?= htmlspecialchars($errors['timeline'] ?? '') ?></p>
    </div>
</div>