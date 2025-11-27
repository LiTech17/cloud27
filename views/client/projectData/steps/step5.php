<!-- Step 5: Features & Functionality -->
<h2 class="heading-4 mb-6">
    <i class="fa-solid fa-cogs mr-2" style="color: var(--clr-primary);"></i><?= e($steps[4]) ?>
</h2>

<div class="form-group">
    <label class="form-label">
        Select desired features <span class="text-error">*</span>
    </label>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3" data-required-group>
        <?php
        $features = [
            'contact_form' => 'Contact Form',
            'newsletter' => 'Newsletter Signup',
            'search' => 'Search Functionality',
            'social_media' => 'Social Media Integration',
            'gallery' => 'Image/Video Gallery',
            'testimonials' => 'Testimonials Section',
            'live_chat' => 'Live Chat',
            'booking' => 'Booking/Appointment System',
            'ecommerce' => 'E-commerce/Shopping Cart',
            'payment_gateway' => 'Payment Gateway',
            'user_accounts' => 'User Login/Accounts',
            'multilingual' => 'Multi-language Support'
        ];
        
        // Fixed: Better handling of special_features data
        $selectedFeatures = [];
        if (isset($data['special_features'])) {
            if (is_string($data['special_features'])) {
                $selectedFeatures = !empty($data['special_features']) ? explode(',', $data['special_features']) : [];
            } else if (is_array($data['special_features'])) {
                $selectedFeatures = $data['special_features'];
            }
        }
        ?>
        <?php foreach ($features as $value => $label): ?>
            <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition form-checkbox-label">
                <input type="checkbox" name="special_features[]" value="<?= e($value) ?>" 
                       class="form-checkbox mr-3" 
                       <?= in_array($value, $selectedFeatures) ? 'checked' : '' ?>>
                <span style="color: var(--clr-text);"><?= e($label) ?></span>
            </label>
        <?php endforeach; ?>
        <p class="text-xs text-error mt-1 hidden md:col-span-2" data-field="special_features">Please select at least one feature.</p>
    </div>
</div>