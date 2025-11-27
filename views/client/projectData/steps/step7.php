<!-- Step 7: Technical Requirements -->
<h2 class="heading-4 mb-6">
    <i class="fa-solid fa-code mr-2" style="color: var(--clr-primary);"></i><?= e($steps[6]) ?>
</h2>

<div class="space-y-6">
    <div class="form-group">
        <label for="cms_needed" class="form-label">
            Do you need a Content Management System (CMS)?
        </label>
        <select id="cms_needed" name="cms_needed" 
                 class="form-select <?= isset($errors['cms_needed']) ? 'form-input-error' : '' ?>">
            <option value="">Select...</option>
            <option value="yes" <?= ($data['cms_needed'] ?? '') === 'yes' ? 'selected' : '' ?>>Yes, I want to manage content myself</option>
            <option value="no" <?= ($data['cms_needed'] ?? '') === 'no' ? 'selected' : '' ?>>No, static website is fine</option>
            <option value="unsure" <?= ($data['cms_needed'] ?? '') === 'unsure' ? 'selected' : '' ?>>Unsure, need advice</option>
        </select>
        <p class="text-xs text-error mt-1" data-field="cms_needed"><?= htmlspecialchars($errors['cms_needed'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label for="technical_requirements" class="form-label">
            Third-Party Integrations Needed
        </label>
        <textarea id="technical_requirements" name="technical_requirements" rows="3"
                  class="form-textarea <?= isset($errors['technical_requirements']) ? 'form-input-error' : '' ?>"
                  placeholder="E.g., Google Analytics, Mailchimp, Payment gateways, CRM systems..."><?= e($data['technical_requirements'] ?? '') ?></textarea>
        <p class="text-xs text-error mt-1" data-field="technical_requirements"><?= htmlspecialchars($errors['technical_requirements'] ?? '') ?></p>
    </div>

    <div class="form-group">
        <label for="seo_requirements" class="form-label">
            SEO Requirements
        </label>
        <textarea id="seo_requirements" name="seo_requirements" rows="3"
                  class="form-textarea <?= isset($errors['seo_requirements']) ? 'form-input-error' : '' ?>"
                  placeholder="Target keywords, geographic focus, SEO goals..."><?= e($data['seo_requirements'] ?? '') ?></textarea>
        <p class="text-xs text-error mt-1" data-field="seo_requirements"><?= htmlspecialchars($errors['seo_requirements'] ?? '') ?></p>
    </div>
</div>