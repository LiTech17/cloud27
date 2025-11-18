<?php 
// views/services.php

/**
 * @var array $data Contains data passed from the controller, including 'services'
 */

$services = $data['services'] ?? [];
?>

<style>
    /* Service Page Specific Styles */
    .service-grid {
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
        gap: 30px; 
        margin-top: 40px;
    }
    .service-card {
        border: 1px solid #ddd; 
        border-radius: 8px; 
        padding: 25px; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: transform 0.3s;
        background-color: #fff; /* Ensure background is white */
    }
    .service-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 12px rgba(0,0,0,0.1);
    }
    .service-card h3 {
        color: var(--primary-color, #007bff);
        margin-top: 0;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
</style>

<div class="container">
    <h1 style="text-align: center; margin-bottom: 40px; color: #343a40;">Explore Our Cloud Solutions</h1>
    
    <p style="text-align: center;">We offer tailored solutions designed to drive your business forward. Here are some of our core offerings.</p>

    <?php if (empty($services)): ?>
        <div style="padding: 20px; border: 1px solid #ffc107; background-color: #fff3cd; color: #856404; margin-top: 20px; text-align: center;">
            <p>We currently have no services listed. Please check back later!</p>
        </div>
    <?php else: ?>
        
        <div class="service-grid">
        
        <?php foreach ($services as $service): ?>
            <div class="service-card">
                
                <h3 style="color: #007bff; margin-top: 0;">
                    <span style="margin-right: 10px; font-size: 1.2em;">⭐</span>
                    <?= htmlspecialchars($service['title']) ?>
                </h3>
                
                <p><?= nl2br(htmlspecialchars($service['description'])) ?></p>
            </div>
        <?php endforeach; ?>

        </div>
    <?php endif; ?>

</div>

