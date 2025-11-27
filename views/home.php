<?php 
// views/home.php

/**
 * @var array $data Contains data passed from the controller, including 'services' and 'packages'
 */
$services = $data['services'] ?? [];
$packages = $data['packages'] ?? []; 
?>

<!-- Hero Section -->
<section class="section text-center bg-gradient" style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%); color: white; border-radius: var(--radius-2xl); padding: var(--space-12) var(--space-4);">
    <div class="container-md mx-auto">
        <h1 class="heading-1 mb-4" style="color: white;">Building the Future, Today.</h1>
        <p class="text-xl md:text-2xl mb-6" style="color: rgba(255, 255, 255, 0.95); max-width: 700px; margin-left: auto; margin-right: auto;">
            Cloud27 provides custom web development and cloud solutions to power your success.
        </p>
        <a href="<?= BASE_PATH ?>/contact" class="btn btn-secondary btn-lg shadow-lg">
            Start Your Project
        </a>
    </div>
</section>

<!-- Services Section -->
<section class="section">
    <div class="container">
        <div class="text-center mb-8">
            <h2 class="heading-2 mb-3">Our Core Capabilities</h2>
            <p class="text-lg text-secondary max-w-2xl mx-auto">
                We combine cutting-edge technology with thoughtful design to deliver exceptional digital products.
            </p>
        </div>

        <?php if (!empty($services)): ?>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <?php 
                // Show only the first 3 services on the home page
                $count = 0;
                foreach ($services as $service): 
                    if ($count >= 3) break;
                    $count++;
                ?>
                    <div class="card text-center animate-fade-in">
                        <span class="<?= htmlspecialchars($service['icon_class'] ?? 'bi-gear') ?>" 
                              style="font-size: 3rem; color: var(--color-primary); display: block; margin-bottom: var(--space-4);"></span>
                        
                        <h4 class="card-title text-xl mb-3"><?= htmlspecialchars($service['title']) ?></h4>
                        
                        <p class="text-secondary"><?= htmlspecialchars($service['description']) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="text-center mt-8">
                <a href="<?= BASE_PATH ?>/services" class="btn btn-outline">
                    View All Services →
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Pricing Packages Section -->
<?php if (!empty($packages)): ?>
<section class="section bg-secondary">
    <div class="container">
        <div class="text-center mb-8">
            <h2 class="heading-2 mb-3">Transparent Pricing. Powerful Solutions.</h2>
            <p class="text-lg text-secondary max-w-2xl mx-auto">
                Choose the web development package that fits your business goals.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <?php foreach ($packages as $package): ?>
            <div class="card relative animate-slide-up" style="transition: transform var(--transition-base);">
                
                <?php if ($package['tag']): ?>
                    <span class="badge badge-warning" style="position: absolute; top: -12px; right: var(--space-4);">
                        <?= htmlspecialchars($package['tag']) ?>
                    </span>
                <?php endif; ?>

                <div class="card-header text-center border-b-0">
                    <h3 class="card-title text-brand text-2xl"><?= htmlspecialchars($package['title']) ?></h3>
                </div>

                <div class="card-body text-center">
                    <div class="mb-6">
                        <p class="text-4xl md:text-5xl font-bold text-primary mb-2">
                            R<?= number_format($package['price_base'] ?? 0, 0) ?>
                        </p>
                        <p class="text-sm text-tertiary">Base Setup Fee</p>
                    </div>

                    <p class="text-base mb-6 text-secondary">
                        Includes: <strong class="text-primary"><?= htmlspecialchars($package['pages_count'] ?? 'Custom Pages') ?></strong>
                    </p>

                    <div class="text-left mb-6">
                        <ul style="list-style: none; padding: 0;">
                            <?php 
                            $features = explode("\n", $package['features_string'] ?? '');
                            foreach (array_slice($features, 0, 5) as $feature): 
                            ?>
                                <li class="mb-3 text-sm flex items-start gap-2">
                                    <span class="text-success font-bold" style="flex-shrink: 0;">✓</span>
                                    <span class="text-secondary"><?= htmlspecialchars(trim($feature)) ?></span>
                                </li>
                            <?php endforeach; ?>
                            <?php if (count($features) > 5): ?>
                                <li class="mb-3 text-sm text-tertiary">
                                    ... and more features!
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="card-footer">
                    <a href="<?= BASE_PATH ?>/contact?package_id=<?= htmlspecialchars($package['id']) ?>" 
                       class="btn btn-primary w-full">
                        Get Quote for This Package
                    </a>
                    
                    <p class="mt-3 text-xs text-center text-tertiary">
                        + R<?= number_format($package['price_hosting_monthly'] ?? 0, 2) ?>/month hosting
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Call to Action Section -->
<section class="section text-center">
    <div class="container-md mx-auto">
        <div class="card card-gradient p-8 md:p-12">
            <h2 class="heading-2 mb-4" style="color: white;">Ready to Transform Your Digital Presence?</h2>
            <p class="text-lg mb-6" style="color: rgba(255, 255, 255, 0.9);">
                Let's discuss how we can help you achieve your business goals with cutting-edge web solutions.
            </p>
            <a href="<?= BASE_PATH ?>/get-started" class="btn btn-secondary btn-lg">
                Get Started Today
            </a>
        </div>
    </div>
</section>