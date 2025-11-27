<!-- Step 4: Website Structure -->
<h2 class="heading-4 mb-6">
    <i class="fa-solid fa-sitemap mr-2" style="color: var(--clr-primary);"></i><?= e($steps[3]) ?>
</h2>

<div class="form-group">
    <label class="form-label">
        Which pages do you need? <span class="text-error">*</span>
    </label>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3" data-required-group>
        <?php
        $pages = [
            'home' => 'Home Page',
            'about' => 'About Us',
            'services' => 'Services/Products',
            'portfolio' => 'Portfolio/Gallery',
            'blog' => 'Blog/News',
            'contact' => 'Contact Us',
            'testimonials' => 'Testimonials',
            'faq' => 'FAQ Page',
            'team' => 'Team/Staff',
            'pricing' => 'Pricing',
            'careers' => 'Careers',
            'custom' => 'Custom Pages'
        ];
        
        // Fixed: Better handling of pages_needed data
        $selectedPages = [];
        if (isset($data['pages_needed'])) {
            if (is_string($data['pages_needed'])) {
                $selectedPages = !empty($data['pages_needed']) ? explode(',', $data['pages_needed']) : [];
            } else if (is_array($data['pages_needed'])) {
                $selectedPages = $data['pages_needed'];
            }
        }
        ?>
        <?php foreach ($pages as $value => $label): ?>
            <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition form-checkbox-label">
                <input type="checkbox" name="pages_needed[]" value="<?= e($value) ?>" 
                       class="form-checkbox mr-3" 
                       <?= in_array($value, $selectedPages) ? 'checked' : '' ?>>
                <span style="color: var(--clr-text);"><?= e($label) ?></span>
            </label>
        <?php endforeach; ?>
        <p class="text-xs text-error mt-1 hidden md:col-span-2" data-field="pages_needed">Please select at least one page.</p>
    </div>
</div>