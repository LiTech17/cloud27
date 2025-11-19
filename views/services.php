<?php 
// views/services.php

/**
 * @var array $data Contains data passed from the controller, including 'services'
 */

$services = $data['services'] ?? [];
?>

<!-- Hero Section -->
<section class="section text-center bg-gradient" style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%); color: white; border-radius: var(--radius-2xl); padding: var(--space-10) var(--space-4); margin-bottom: var(--space-8);">
    <div class="container-md mx-auto">
        <h1 class="heading-1 mb-4" style="color: white;">Explore Our Cloud Solutions</h1>
        <p class="text-lg md:text-xl" style="color: rgba(255, 255, 255, 0.95); max-width: 700px; margin-left: auto; margin-right: auto;">
            We offer tailored solutions designed to drive your business forward with cutting-edge technology and expert support.
        </p>
    </div>
</section>

<section class="section">
    <div class="container">
        <?php if (empty($services)): ?>
            <!-- Empty State -->
            <div class="card text-center bg-warning-light animate-fade-in" style="max-width: 600px; margin: 0 auto; padding: var(--space-8);">
                <svg class="mx-auto mb-4" style="width: 64px; height: 64px; color: var(--color-warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="heading-3 mb-3" style="color: var(--color-warning);">No Services Available</h3>
                <p class="text-secondary mb-6">
                    We're currently updating our services catalog. Please check back soon or contact us for custom solutions.
                </p>
                <a href="<?= BASE_PATH ?>/contact" class="btn btn-warning">
                    Contact Us for Custom Solutions
                </a>
            </div>
        <?php else: ?>
            
            <!-- Services Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <?php foreach ($services as $index => $service): ?>
                    <div class="card hover-card animate-fade-in" style="animation-delay: <?= $index * 0.1 ?>s;">
                        <!-- Service Icon -->
                        <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 rounded-full bg-brand-light">
                            <?php if (!empty($service['icon_class'])): ?>
                                <span class="<?= htmlspecialchars($service['icon_class']) ?>" 
                                      style="font-size: 2rem; color: var(--color-primary);"></span>
                            <?php else: ?>
                                <svg style="width: 2rem; height: 2rem; color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            <?php endif; ?>
                        </div>

                        <!-- Service Title -->
                        <h3 class="card-title text-center mb-4 text-brand">
                            <?= htmlspecialchars($service['title']) ?>
                        </h3>

                        <!-- Service Description -->
                        <p class="text-secondary text-center mb-6 line-clamp-4">
                            <?= nl2br(htmlspecialchars($service['description'])) ?>
                        </p>

                        <!-- CTA Button -->
                        <div class="text-center">
                            <a href="<?= BASE_PATH ?>/contact?service=<?= urlencode($service['title']) ?>" 
                               class="btn btn-outline btn-sm">
                                Learn More
                            </a>
                        </div>

                        <!-- Decorative Corner Badge (Optional) -->
                        <?php if ($index < 3): ?>
                            <div class="badge badge-primary" style="position: absolute; top: var(--space-3); right: var(--space-3);">
                                Popular
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Call to Action Section -->
            <div class="mt-12">
                <div class="card card-gradient text-center p-8 md:p-12 animate-slide-up">
                    <h2 class="heading-3 mb-4" style="color: white;">Can't Find What You're Looking For?</h2>
                    <p class="text-lg mb-6" style="color: rgba(255, 255, 255, 0.9); max-width: 600px; margin-left: auto; margin-right: auto;">
                        We specialize in custom solutions tailored to your unique business needs. Let's discuss your project.
                    </p>
                    <a href="<?= BASE_PATH ?>/contact" class="btn btn-secondary btn-lg">
                        Request Custom Solution
                    </a>
                </div>
            </div>

            <!-- Feature Highlights -->
            <div class="mt-12">
                <h3 class="heading-3 text-center mb-8">Why Choose Our Services?</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 rounded-full bg-success-light">
                            <svg class="w-6 h-6" style="color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="font-semibold mb-2">Expert Team</h4>
                        <p class="text-sm text-secondary">Experienced professionals dedicated to your success</p>
                    </div>

                    <div class="text-center">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 rounded-full bg-info-light">
                            <svg class="w-6 h-6" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h4 class="font-semibold mb-2">Fast Delivery</h4>
                        <p class="text-sm text-secondary">Quick turnaround times without compromising quality</p>
                    </div>

                    <div class="text-center">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-3 rounded-full bg-warning-light">
                            <svg class="w-6 h-6" style="color: var(--color-warning);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="font-semibold mb-2">Competitive Pricing</h4>
                        <p class="text-sm text-secondary">Transparent pricing with excellent value for money</p>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>
</section>

<style>
/* Service Card Hover Effect */
.hover-card {
    transition: all var(--transition-base);
    position: relative;
}

.hover-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-xl);
}

/* Line Clamp for Description */
.line-clamp-4 {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Animation Delays for Staggered Effect */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.6s ease-out forwards;
    opacity: 0;
}

/* Utility Classes */
.w-6 { width: 1.5rem; }
.h-6 { height: 1.5rem; }
.w-12 { width: 3rem; }
.h-12 { height: 3rem; }
.w-16 { width: 4rem; }
.h-16 { height: 4rem; }
</style>