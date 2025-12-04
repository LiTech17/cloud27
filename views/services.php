<?php 
// views/services.php

/**
 * @var array $data Contains data passed from the controller, including 'services'
 */

$services = $data['services'] ?? [];
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-content">
        <h1 class="mb-4 animate-on-scroll">Explore Our Cloud Solutions</h1>
        <p class="lead animate-on-scroll" style="animation-delay: 0.2s;">
            We offer tailored solutions designed to drive your business forward with cutting-edge technology and expert support.
        </p>
    </div>
</section>

<section class="section section-light">
    <div class="container">
        <?php if (empty($services)): ?>
            <!-- Empty State -->
            <div class="text-center py-5 animate-fade-in">
                <div class="mb-4 text-warning">
                    <i class="bi bi-exclamation-circle" style="font-size: 4rem;"></i>
                </div>
                <h3 class="fw-bold mb-3">No Services Available</h3>
                <p class="text-muted mb-4">
                    We're currently updating our services catalog. Please check back soon or contact us for custom solutions.
                </p>
                <a href="<?= BASE_PATH ?>/contact" class="btn btn-warning rounded-pill px-4">
                    Contact Us for Custom Solutions
                </a>
            </div>
        <?php else: ?>
            
            <!-- Services Grid -->
            <div class="row g-4">
                <?php foreach ($services as $index => $service): ?>
                    <div class="col-md-6 col-lg-4 animate-on-scroll" style="animation-delay: <?= $index * 0.1 ?>s;">
                        <div class="feature-card h-100">
                            <!-- Service Icon -->
                            <div class="icon-wrapper mb-4">
                                <?php if (!empty($service['icon_class'])): ?>
                                    <i class="<?= htmlspecialchars($service['icon_class']) ?> icon-lg"></i>
                                <?php else: ?>
                                    <i class="bi bi-gear icon-lg"></i>
                                <?php endif; ?>
                            </div>

                            <!-- Service Title -->
                            <h4 class="fw-bold mb-3 text-dark">
                                <?= htmlspecialchars($service['title']) ?>
                            </h4>

                            <!-- Service Description -->
                            <p class="text-muted mb-4 line-clamp-4">
                                <?= nl2br(htmlspecialchars($service['description'])) ?>
                            </p>

                            <!-- CTA Button -->
                            <div class="mt-auto">
                                <a href="<?= BASE_PATH ?>/contact?service=<?= urlencode($service['title']) ?>" 
                                   class="btn btn-outline-primary btn-sm rounded-pill px-4">
                                    Learn More <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>

                            <!-- Popular Badge -->
                            <?php if ($index < 3): ?>
                                <div class="position-absolute top-0 end-0 mt-3 me-3">
                                    <span class="badge bg-primary-subtle text-primary rounded-pill">Popular</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Call to Action Section -->
            <div class="mt-5 pt-5">
                <div class="text-center animate-on-scroll" 
                     style="background: var(--gradient-ocean); 
                            border-radius: 25px; 
                            padding: 4rem 2rem; 
                            color: white;
                            position: relative;
                            overflow: hidden;">
                    
                    <div class="position-relative z-1">
                        <h3 class="fw-bold mb-3">Can't Find What You're Looking For?</h3>
                        <p class="fs-5 mb-4 text-white-50" style="max-width: 700px; margin: 0 auto;">
                            We specialize in custom solutions tailored to your unique business needs. Let's discuss your project.
                        </p>
                        <a href="<?= BASE_PATH ?>/contact" class="btn btn-light btn-lg rounded-pill fw-bold px-5 shadow-sm hover-scale">
                            Request Custom Solution
                        </a>
                    </div>
                </div>
            </div>

            <!-- Feature Highlights -->
            <div class="mt-5 pt-5">
                <div class="text-center mb-5 animate-on-scroll">
                    <h3 class="section-title fs-2">Why Choose Our Services?</h3>
                </div>
                
                <div class="row g-4 text-center">
                    <div class="col-md-4 animate-on-scroll">
                        <div class="p-4 rounded-4 bg-white shadow-sm h-100 border border-light">
                            <div class="d-inline-flex align-items-center justify-content-center width-60 height-60 rounded-circle bg-success-subtle text-success mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-people fs-3"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Expert Team</h5>
                            <p class="text-muted small mb-0">Experienced professionals dedicated to your success</p>
                        </div>
                    </div>

                    <div class="col-md-4 animate-on-scroll" style="animation-delay: 0.1s;">
                        <div class="p-4 rounded-4 bg-white shadow-sm h-100 border border-light">
                            <div class="d-inline-flex align-items-center justify-content-center width-60 height-60 rounded-circle bg-info-subtle text-info mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-lightning fs-3"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Fast Delivery</h5>
                            <p class="text-muted small mb-0">Quick turnaround times without compromising quality</p>
                        </div>
                    </div>

                    <div class="col-md-4 animate-on-scroll" style="animation-delay: 0.2s;">
                        <div class="p-4 rounded-4 bg-white shadow-sm h-100 border border-light">
                            <div class="d-inline-flex align-items-center justify-content-center width-60 height-60 rounded-circle bg-warning-subtle text-warning mb-3" style="width: 60px; height: 60px;">
                                <i class="bi bi-tag fs-3"></i>
                            </div>
                            <h5 class="fw-bold mb-2">Competitive Pricing</h5>
                            <p class="text-muted small mb-0">Transparent pricing with excellent value for money</p>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>
</section>

<style>
/* Line Clamp for Description */
.line-clamp-4 {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.hover-scale:hover {
    transform: scale(1.05);
    transition: transform 0.3s ease;
}
</style>