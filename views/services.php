<?php 
// views/services.php

/**
 * @var array $data Contains data passed from the controller, including 'services'
 */
$services = $data['services'] ?? [];
?>

<div class="container">
    <h2>Explore Our Services</h2>
    <p>We offer tailored solutions designed to drive your business forward. Here are some of our core offerings.</p>

    <?php if (empty($services)): ?>
        <div style="padding: 20px; border: 1px solid #ffc107; background-color: #fff3cd; color: #856404; margin-top: 20px;">
            <p>We currently have no services listed. Please check back later!</p>
        </div>
    <?php else: ?>
        
        <div class="service-grid" style="
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 30px; 
            margin-top: 40px;
        ">
        
        <?php foreach ($services as $service): ?>
            <div class="service-card" style="
                border: 1px solid #ddd; 
                border-radius: 8px; 
                padding: 25px; 
                box-shadow: 0 4px 6px rgba(0,0,0,0.05);
                transition: transform 0.3s;
            ">
                
                <h3 style="color: #007bff; margin-top: 0;">
                    <span class="<?= htmlspecialchars($service['icon_class'] ?? 'bi-gear') ?>" 
                          style="margin-right: 10px; font-size: 1.2em;"></span>
                    <?= htmlspecialchars($service['title']) ?>
                </h3>
                
                <p><?= nl2br(htmlspecialchars($service['description'])) ?></p>
            </div>
        <?php endforeach; ?>

        </div>
    <?php endif; ?>

</div>  