<!-- Step 2: Goals & Objectives -->
<h2 class="heading-4 mb-6">
    <i class="fa-solid fa-bullseye mr-2" style="color: var(--clr-primary);"></i><?= e($steps[1]) ?>
</h2>

<div class="form-group">
    <label class="form-label">
        What are your main objectives for this website? <span class="text-error">*</span>
    </label>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3" data-required-group>
        <?php
        $projectGoals = [
            'brand_awareness' => 'Increase Brand Awareness',
            'generate_leads' => 'Generate Leads',
            'online_sales' => 'Sell Products/Services Online',
            'customer_support' => 'Provide Customer Support',
            'showcase_portfolio' => 'Showcase Portfolio/Work',
            'information_sharing' => 'Share Information/Blog',
            'membership_community' => 'Build Membership/Community'
        ];
        
        // Simple fix: Ensure we always have an array
        $goalsData = $data['goals_objectives'] ?? '';
        $selectedGoals = is_array($goalsData) ? $goalsData : (!empty($goalsData) ? explode(',', $goalsData) : []);
        ?>
        <?php foreach ($projectGoals as $value => $label): ?>
            <label class="flex items-center p-3 border rounded cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition form-checkbox-label">
                <input type="checkbox" name="goals_objectives[]" value="<?= e($value) ?>" 
                       class="form-checkbox mr-3" 
                       <?= in_array($value, $selectedGoals) ? 'checked' : '' ?>>
                <span style="color: var(--clr-text);"><?= e($label) ?></span>
            </label>
        <?php endforeach; ?>
        <p class="text-xs text-error mt-1 hidden md:col-span-2" data-field="goals_objectives">Please select at least one project goal.</p>
    </div>
</div>