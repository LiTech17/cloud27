<?php 
// views/home.php

/**
 * @var array $data Contains data passed from the controller, including 'services'
 */
$services = $data['services'] ?? [];
// Removed: $featuredProjects = $data['featuredProjects'] ?? []; 
?>

<div class="hero" style="text-align: center; padding: 80px 20px; background-color: #f8f9fa; border-radius: 8px;">
    <h1>Building the Future, Today.</h1>
    <p class="lead" style="margin-top: 20px;">
        Cloud27 provides custom web development and cloud solutions to power your success.
    </p>
    <a href="<?= BASE_PATH ?>/contact" class="btn-primary" 
       style="display: inline-block; margin-top: 30px;">Start Your Project</a>
</div>

<div class="container" style="margin-top: 60px;">
    <h2>Our Core Capabilities</h2>
    <p>We combine cutting-edge technology with thoughtful design to deliver exceptional digital products.</p>

    <?php if (!empty($services)): ?>
        <div class="service-grid" style="
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 30px; 
            margin-top: 30px;
        ">
        
        <?php 
        // Show only the first 3 services on the home page
        $count = 0;
        foreach ($services as $service): 
            if ($count >= 3) break;
            $count++;
        ?>
            <div class="service-card-home" style="
                padding: 20px; 
                border: 1px solid #007bff; /* Primary color border */
                border-radius: 6px;
                background-color: #e9f5ff; /* Light background */
                text-align: center;
            ">
                <span class="<?= htmlspecialchars($service['icon_class'] ?? 'bi-gear') ?>" 
                      style="font-size: 2.5em; color: #007bff; display: block; margin-bottom: 10px;"></span>
                
                <h4 style="margin-top: 0;"><?= htmlspecialchars($service['title']) ?></h4>
                
                <p style="font-size: 0.9em;"><?= htmlspecialchars($service['description']) ?></p>
            </div>
        <?php endforeach; ?>

        </div>
        <div style="text-align: center; margin-top: 40px;">
             <a href="<?= BASE_PATH ?>/services" class="btn-secondary">View All Services →</a>
        </div>
    <?php endif; ?>

</div>