<!-- Step 1: Business Fundamentals -->
<h2 class="heading-4 mb-6">
    <i class="fa-solid fa-building mr-2" style="color: var(--clr-primary);"></i><?= e($steps[0]) ?>
</h2>

<div class="space-y-6">
    <div class="form-group">
        <label for="company_name" class="form-label">
            Company Name <span class="text-error">*</span>
        </label>
        <input type="text" id="company_name" name="company_name" 
                class="form-input <?= isset($errors['company_name']) ? 'form-input-error' : '' ?>"
                value="<?= e($data['company_name'] ?? '') ?>" 
                placeholder="Acme Corp" 
                required>
        <p class="text-xs text-error mt-1" data-field="company_name"><?= htmlspecialchars($errors['company_name'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label for="contact_name" class="form-label">
            Contact Name <span class="text-error">*</span>
        </label>
        <input type="text" id="contact_name" name="contact_name" 
                class="form-input <?= isset($errors['contact_name']) ? 'form-input-error' : '' ?>"
                value="<?= e($data['contact_name'] ?? '') ?>" 
                placeholder="John Doe"
                required>
        <p class="text-xs text-error mt-1" data-field="contact_name"><?= htmlspecialchars($errors['contact_name'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label for="contact_email" class="form-label">
            Contact Email <span class="text-error">*</span>
        </label>
        <input type="email" id="contact_email" name="contact_email" 
                class="form-input <?= isset($errors['contact_email']) ? 'form-input-error' : '' ?>"
                value="<?= e($data['contact_email'] ?? '') ?>" 
                placeholder="john@acme.com"
                required>
        <p class="text-xs text-error mt-1" data-field="contact_email"><?= htmlspecialchars($errors['contact_email'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label for="products_services" class="form-label">
            Products/Services Offered
        </label>
        <textarea id="products_services" name="products_services" rows="3"
                    class="form-textarea <?= isset($errors['products_services']) ? 'form-input-error' : '' ?>"
                    placeholder="Describe your main products or services..."><?= e($data['products_services'] ?? '') ?></textarea>
        <p class="text-xs text-error mt-1" data-field="products_services"><?= htmlspecialchars($errors['products_services'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label for="mission_statement" class="form-label">
            Mission Statement
        </label>
        <textarea id="mission_statement" name="mission_statement" rows="3"
                    class="form-textarea <?= isset($errors['mission_statement']) ? 'form-input-error' : '' ?>"
                    placeholder="What is your company's mission?"><?= e($data['mission_statement'] ?? '') ?></textarea>
        <p class="text-xs text-error mt-1" data-field="mission_statement"><?= htmlspecialchars($errors['mission_statement'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label for="unique_selling_point" class="form-label">
            Unique Selling Point
        </label>
        <textarea id="unique_selling_point" name="unique_selling_point" rows="2"
                    class="form-textarea <?= isset($errors['unique_selling_point']) ? 'form-input-error' : '' ?>"
                    placeholder="What makes you different from competitors?"><?= e($data['unique_selling_point'] ?? '') ?></textarea>
        <p class="text-xs text-error mt-1" data-field="unique_selling_point"><?= htmlspecialchars($errors['unique_selling_point'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label for="competitors" class="form-label">
            Main Competitors (comma-separated)
        </label>
        <input type="text" id="competitors" name="competitors" 
                class="form-input <?= isset($errors['competitors']) ? 'form-input-error' : '' ?>"
                value="<?= e($data['competitors'] ?? '') ?>" 
                placeholder="Competitor A, Competitor B, Competitor C">
        <p class="text-xs text-error mt-1" data-field="competitors"><?= htmlspecialchars($errors['competitors'] ?? '') ?></p>
    </div>
</div>