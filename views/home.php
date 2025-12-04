<?php 
// views/home.php

/**
 * @var array $data Contains data passed from the controller, including 'services' and 'packages'
 */
$services = $data['services'] ?? [];
$packages = $data['packages'] ?? []; 
?>

<!-- Hero Section -->
<?php
// Fetch active slides
$carouselModel = new \Models\HeroCarousel();
$slides = $carouselModel->getActiveSlides();
?>

<?php if (!empty($slides)): ?>
    <!-- Dynamic Carousel -->
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($slides as $index => $slide): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>" data-bs-interval="5000">
                    <div class="hero-slide-bg" style="background-image: url('<?= BASE_PATH ?>/uploads/hero/optimized/<?= $slide['image_filename'] ?>');">
                        <div class="hero-overlay"></div>
                        <div class="container hero-content">
                            <h1><?= htmlspecialchars($slide['title'] ?? '') ?></h1>
                            <p class="lead"><?= htmlspecialchars($slide['subtitle'] ?? '') ?></p>
                            
                            <?php if ($slide['cta_text'] && $slide['cta_link']): ?>
                                <div class="d-flex justify-content-center gap-4 flex-wrap mt-4">
                                    <a href="<?= $slide['cta_link'] ?>" class="btn btn-primary btn-lg">
                                        <?= htmlspecialchars($slide['cta_text']) ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php if (count($slides) > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        <?php endif; ?>
    </div>
<?php else: ?>
    <!-- Fallback Static Hero (if no slides) -->
    <section class="hero-section">
        <div class="hero-bg"></div>
        <div class="container hero-content">
            <h1>Building the Future, Today.</h1>
            <p class="lead">Cloud27 provides custom web development and cloud solutions to power your success.</p>
            
            <div class="d-flex justify-content-center flex-wrap gap-3 mb-5 mt-4">
                <span class="badge badge-pill rounded-pill floating-element">⚡ Custom Development</span>
                <span class="badge badge-pill rounded-pill floating-element floating-element-delay-1">🔒 Secure Infrastructure</span>
                <span class="badge badge-pill rounded-pill floating-element floating-element-delay-2">📱 Mobile-First Design</span>
                <span class="badge badge-pill rounded-pill floating-element">🚀 Cloud Solutions</span>
            </div>
            
            <div class="d-flex justify-content-center gap-4 flex-wrap">
                <a href="<?= BASE_PATH ?>/contact" class="btn btn-primary btn-lg">
                    <i class="bi bi-rocket-takeoff me-2"></i> Start Your Project
                </a>
                <a href="<?= BASE_PATH ?>/services" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-eye me-2"></i> View Services
                </a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Services Section -->
<section class="section section-light">
    <div class="container">
        <div class="text-center mb-5 animate-on-scroll">
            <h2 class="section-title">Our Core Capabilities</h2>
            <p class="lead mt-3">We combine cutting-edge technology with thoughtful design to deliver exceptional digital products.</p>
        </div>

        <?php if (!empty($services)): ?>
            <div class="row text-center mt-5">
                <?php 
                // Show only the first 3 services on the home page
                $count = 0;
                $delays = ['0s', '0.1s', '0.2s'];
                $colors = ['#667eea', '#8b5cf6', '#06b6d4'];
                foreach ($services as $service): 
                    if ($count >= 3) break;
                ?>
                    <div class="col-md-4 mb-4 animate-on-scroll" style="animation-delay: <?= $delays[$count] ?>;">
                        <div class="feature-card floating-element" style="animation-delay: <?= $count * 0.5 ?>s;">
                            <div class="icon-wrapper mb-4">
                                <i class="<?= htmlspecialchars($service['icon_class'] ?? 'bi bi-gear') ?> icon-lg"></i>
                            </div>
                            <h5 class="fw-bold mb-3"><?= htmlspecialchars($service['title']) ?></h5>
                            <p class="text-muted"><?= htmlspecialchars($service['description']) ?></p>
                            <div class="mt-4">
                                <a href="<?= BASE_PATH ?>/services#service-<?= $service['id'] ?>" class="btn btn-sm btn-outline-primary">
                                    Learn More <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php 
                    $count++;
                endforeach; 
                ?>
            </div>

            <div class="text-center mt-5 animate-on-scroll">
                <a href="<?= BASE_PATH ?>/services" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-grid-3x3-gap me-2"></i> View All Services →
                </a>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Pricing Packages Section -->
<?php if (!empty($packages)): ?>
<section class="section packages-section">
    <div class="container">
        <div class="text-center mb-5 animate-on-scroll">
            <h2 class="section-title">Transparent Pricing. Powerful Solutions.</h2>
            <p class="lead mt-3">Choose the web development package that fits your business goals.</p>
        </div>

        <div class="row justify-content-center">
            <?php 
            $count = 0;
            $delays = ['0s', '0.1s', '0.2s'];
            foreach ($packages as $package): 
            ?>
            <div class="col-lg-4 col-md-6 mb-4 animate-on-scroll" style="animation-delay: <?= $delays[$count % 3] ?>;">
                <div class="card package-card h-100 shadow-sm <?= $package['tag'] ? 'featured-package' : '' ?> floating-element" 
                     style="animation-delay: <?= $count * 0.3 ?>s;">
                    <?php if ($package['tag']): ?>
                        <div class="popular-badge"><?= strtoupper(htmlspecialchars($package['tag'])) ?></div>
                    <?php endif; ?>
                    
                    <div class="card-body d-flex flex-column">
                        <div class="mb-4">
                            <h4 class="fw-bold mb-3"><?= htmlspecialchars($package['title']) ?></h4>
                            <p class="package-description text-muted">Includes: <?= htmlspecialchars($package['pages_count'] ?? 'Custom Pages') ?></p>
                        </div>
                        
                        <div class="price-wrapper mb-4">
                            <div class="price">R<?= number_format($package['price_base'] ?? 0, 0) ?></div>
                            <span class="fs-6 fw-normal price-suffix text-muted">+ hosting</span>
                        </div>
                        
                        <ul class="list-unstyled flex-grow-1 mb-4">
                            <?php 
                            $features = explode("\n", $package['features_string'] ?? '');
                            foreach ($features as $feature): 
                                $trimmed = trim($feature);
                                if (!empty($trimmed)):
                            ?>
                                <li class="d-flex align-items-start mb-3">
                                    <i class="bi bi-check-circle-fill check-icon me-2 mt-1"></i>
                                    <span class="text-muted"><?= htmlspecialchars($trimmed) ?></span>
                                </li>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </ul>
                        
                        <div class="mt-auto">
                            <a href="<?= BASE_PATH ?>/get-started?package_id=<?= htmlspecialchars($package['id']) ?>" 
                               class="btn <?= $package['tag'] ? 'btn-primary' : 'btn-outline-primary' ?> w-100">
                                Select <?= htmlspecialchars($package['title']) ?>
                            </a>
                            
                            <p class="mt-3 text-center" style="font-size: 0.85rem; color: var(--text-muted);">
                                + R<?= number_format($package['price_hosting_monthly'] ?? 0, 2) ?>/month hosting
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
                $count++;
            endforeach; 
            ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Custom Development Section -->
<section class="section custom-section">
    <div class="container text-center">
        <h2 class="section-title text-white animate-on-scroll">Beyond Standard Packages: Custom Development</h2>
        <p class="lead mb-5" style="color: #cbd5e1;">Need something unique? We build specialized, secure web systems tailored to your exact requirements.</p>
        
        <div class="row">
            <div class="col-md-4 mb-4 animate-on-scroll">
                <div class="custom-card floating-element">
                    <div class="icon-wrapper mb-4">
                        <i class="bi bi-cart4 icon-lg"></i>
                    </div>
                    <h5 class="fw-bold mb-3">E-commerce Solutions</h5>
                    <ul class="list-unstyled text-start">
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle me-2" style="color: var(--wave-light);"></i>
                            <span>Custom Online Stores</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle me-2" style="color: var(--wave-light);"></i>
                            <span>Payment Integration</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle me-2" style="color: var(--wave-light);"></i>
                            <span>Inventory Management</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle me-2" style="color: var(--wave-light);"></i>
                            <span>Order Processing Systems</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 mb-4 animate-on-scroll" style="animation-delay: 0.1s;">
                <div class="custom-card floating-element" style="animation-delay: 0.5s;">
                    <div class="icon-wrapper mb-4">
                        <i class="bi bi-people icon-lg"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Web Applications</h5>
                    <ul class="list-unstyled text-start">
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle me-2" style="color: var(--wave-light);"></i>
                            <span>SaaS Platforms</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle me-2" style="color: var(--wave-light);"></i>
                            <span>Custom Dashboards</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle me-2" style="color: var(--wave-light);"></i>
                            <span>CRM Systems</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle me-2" style="color: var(--wave-light);"></i>
                            <span>Business Automation</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4 mb-4 animate-on-scroll" style="animation-delay: 0.2s;">
                <div class="custom-card floating-element" style="animation-delay: 1s;">
                    <div class="icon-wrapper mb-4">
                        <i class="bi bi-cloud icon-lg"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Cloud Solutions</h5>
                    <ul class="list-unstyled text-start">
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle me-2" style="color: var(--wave-light);"></i>
                            <span>Cloud Infrastructure</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle me-2" style="color: var(--wave-light);"></i>
                            <span>API Development</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle me-2" style="color: var(--wave-light);"></i>
                            <span>Database Design</span>
                        </li>
                        <li class="mb-2 d-flex align-items-center">
                            <i class="bi bi-check-circle me-2" style="color: var(--wave-light);"></i>
                            <span>System Integration</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="animate-on-scroll" style="animation-delay: 0.3s;">
            <a href="<?= BASE_PATH ?>/contact" class="btn btn-primary btn-lg mt-5">
                <i class="bi bi-chat-dots me-2"></i> Discuss Your Custom Project
            </a>
        </div>
    </div>
</section>

<!-- Testimonial Section with Slider -->
<section class="section testimonial-section">
    <div class="container">
        <div class="text-center mb-5 animate-on-scroll">
            <h2 class="section-title">Trusted by Businesses Across South Africa</h2>
            <p class="lead mt-3">Real results from real clients</p>
        </div>
        
        <div class="testimonial-slider" id="testimonialSlider">
            <div class="testimonial-slide active">
                <p class="fs-4 mb-4" style="position: relative; z-index: 1;">
                    "Cloud27 delivered exactly what we needed. Their expertise in custom web development and 
                    attention to detail made the entire process smooth and professional. The oceanic theme 
                    implementation was breathtaking. Highly recommended!"
                </p>
                <div class="text-end">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="me-3 text-end">
                            <strong>Business Owner</strong>
                            <p class="testimonial-meta mb-0 small">Cape Town, South Africa</p>
                        </div>
                        <div class="testimonial-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-slide">
                <p class="fs-4 mb-4" style="position: relative; z-index: 1;">
                    "The oceanic theme and modern design of our new website perfectly represents our brand. 
                    Cloud27 understood our vision and executed it flawlessly. The wave animations are 
                    absolutely stunning and our customers love it!"
                </p>
                <div class="text-end">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="me-3 text-end">
                            <strong>Marketing Director</strong>
                            <p class="testimonial-meta mb-0 small">Johannesburg, South Africa</p>
                        </div>
                        <div class="testimonial-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-slide">
                <p class="fs-4 mb-4" style="position: relative; z-index: 1;">
                    "From concept to launch, the team at Cloud27 was professional, responsive, and delivered 
                    beyond our expectations. The visual hierarchy and smooth transitions make our e-commerce 
                    site a pleasure to use. Our sales have increased by 40%!"
                </p>
                <div class="text-end">
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="me-3 text-end">
                            <strong>E-commerce Manager</strong>
                            <p class="testimonial-meta mb-0 small">Durban, South Africa</p>
                        </div>
                        <div class="testimonial-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="testimonial-slider-nav" id="testimonialNav">
            <div class="testimonial-dot active"></div>
            <div class="testimonial-dot"></div>
            <div class="testimonial-dot"></div>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="section section-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="text-center animate-on-scroll" 
                     style="background: var(--gradient-ocean); 
                            border-radius: 25px; 
                            padding: 5rem 2rem; 
                            color: white;
                            position: relative;
                            overflow: hidden;">
                    
                    <!-- Wave pattern overlay -->
                    <div style="
                        position: absolute; 
                        top: 0; 
                        left: 0; 
                        width: 100%; 
                        height: 100%;
                        background: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 1200 120%22 preserveAspectRatio=%22none%22%3E%3Cpath d=%22M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z%22 fill=%22%23ffffff%22 opacity=%220.1%22/%3E%3C/svg%3E') 
                        background-size: cover;
                        opacity: 0.3; 
                        animation: waveFlow 20s linear infinite;
">
</div>

                    
                    <h2 class="mb-4" style="color: white; font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; position: relative; z-index: 1;">
                        Ready to Transform Your Digital Presence?
                    </h2>
                    <p class="fs-5 mb-5" style="color: rgba(255, 255, 255, 0.9); position: relative; z-index: 1;">
                        Let's discuss how we can help you achieve your business goals with cutting-edge web solutions.
                    </p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap" style="position: relative; z-index: 1;">
                        <a href="<?= BASE_PATH ?>/get-started" class="btn btn-lg" 
                           style="background: white; 
                                  color: var(--ocean-blue); 
                                  font-weight: 800; 
                                  padding: 1rem 3rem; 
                                  border-radius: 50px; 
                                  box-shadow: 0 10px 30px rgba(0,0,0,0.3); 
                                  transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                            Get Started Today
                        </a>
                        <a href="<?= BASE_PATH ?>/contact" class="btn btn-outline-light btn-lg">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional Styles for Enhanced Elements -->
<style>
    .icon-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(10, 36, 99, 0.1) 0%, rgba(30, 96, 145, 0.1) 100%);
        margin-bottom: 1.5rem;
        position: relative;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .feature-card:hover .icon-wrapper {
        transform: scale(1.2) rotate(10deg);
        background: linear-gradient(135deg, rgba(10, 36, 99, 0.2) 0%, rgba(30, 96, 145, 0.2) 100%);
        box-shadow: 0 10px 25px rgba(10, 36, 99, 0.2);
    }
    
    .custom-card:hover .icon-wrapper {
        transform: scale(1.2) rotate(10deg);
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 10px 25px rgba(255, 255, 255, 0.2);
    }
    
    .price-wrapper {
        padding: 1.5rem;
        background: linear-gradient(135deg, rgba(10, 36, 99, 0.05) 0%, rgba(30, 96, 145, 0.05) 100%);
        border-radius: 15px;
        border: 1px solid rgba(10, 36, 99, 0.1);
    }
    
    .testimonial-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--ocean-teal) 0%, var(--ocean-blue) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }
    
    .btn-outline-light {
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        transition: all 0.3s ease;
    }
    
    .btn-outline-light:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: white;
        transform: translateY(-2px);
    }
    
    /* Oceanic wave animation for CTA section */
    @keyframes waveFlow {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(-50%);
        }
    }
</style>

<script>
// Enhanced Animate on scroll functionality
document.addEventListener('DOMContentLoaded', function() {
    const animateElements = document.querySelectorAll('.animate-on-scroll');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, index * 100);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    animateElements.forEach(element => {
        observer.observe(element);
    });
    
    // Floating elements animation
    const floatingElements = document.querySelectorAll('.floating-element');
    floatingElements.forEach((element, index) => {
        const delay = element.getAttribute('style')?.match(/animation-delay:\s*([\d.]+)s/) || [null, index * 0.5];
        element.style.animationDelay = `${delay[1]}s`;
    });
    
    // Testimonial slider functionality
    const slider = document.getElementById('testimonialSlider');
    const slides = slider.querySelectorAll('.testimonial-slide');
    const dots = document.querySelectorAll('.testimonial-dot');
    let currentSlide = 0;
    let slideInterval;
    
    function showSlide(n) {
        // Remove active classes
        slides.forEach(slide => {
            slide.classList.remove('active', 'prev');
        });
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Add prev class to current slide before changing
        slides[currentSlide].classList.add('prev');
        
        // Update current slide
        currentSlide = (n + slides.length) % slides.length;
        
        // Add active classes
        slides[currentSlide].classList.add('active');
        dots[currentSlide].classList.add('active');
    }
    
    function startSlider() {
        slideInterval = setInterval(() => {
            showSlide(currentSlide + 1);
        }, 5000);
    }
    
    // Initialize slider
    showSlide(0);
    startSlider();
    
    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            clearInterval(slideInterval);
            showSlide(index);
            startSlider();
        });
    });
    
    // Pause slider on hover
    slider.addEventListener('mouseenter', () => {
        clearInterval(slideInterval);
    });
    
    slider.addEventListener('mouseleave', startSlider);
    
    // Enhanced wave effect on scroll
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const heroSection = document.querySelector('.hero-section');
        
        if (heroSection) {
            const heroHeight = heroSection.offsetHeight;
            const scrollPercent = Math.min(scrolled / heroHeight, 1);
            
            // Adjust wave opacity based on scroll
            const waves = document.querySelectorAll('.wave-layer');
            waves.forEach((wave, index) => {
                const opacity = 0.3 - (scrollPercent * 0.2) + (index * 0.1);
                wave.style.opacity = Math.max(opacity, 0.1);
            });
        }
    });
    
    // Add ripple effect to buttons
    document.querySelectorAll('.btn-primary, .btn-outline-primary').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.6);
                transform: scale(0);
                animation: ripple 0.6s linear;
                width: ${size}px;
                height: ${size}px;
                top: ${y}px;
                left: ${x}px;
            `;
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Add ripple animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes ripple {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
});
</script>