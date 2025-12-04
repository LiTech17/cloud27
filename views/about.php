<?php 
// views/about.php

/**
 * @var array $data Contains about content and team members
 */

// Helper for safe HTML output to keep views clean
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$content = $data['content'] ?? [];
$teamMembers = $data['teamMembers'] ?? [];
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-content">
        <?php if (!empty($content['hero_image'])): ?>
            <img src="<?= BASE_PATH ?>/uploads/about/<?= e($content['hero_image']) ?>" 
                 alt="Brand Logo"
                 class="mx-auto mb-4 rounded-circle shadow-lg bg-white p-2 floating-element"
                 width="120" height="auto">
        <?php endif; ?>
        
        <h1 class="mb-4 animate-on-scroll">
            <?= e($content['company_name'] ?? 'Cloud27') ?>
        </h1>
        
        <?php if (!empty($content['tagline'])): ?>
            <p class="lead animate-on-scroll" style="animation-delay: 0.2s;">
                <?= e($content['tagline']) ?>
            </p>
        <?php endif; ?>
    </div>
</section>

<!-- Who We Are Section -->
<section class="section section-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-5 mb-lg-0 animate-on-scroll">
                <span class="badge badge-pill bg-primary mb-3">Who We Are</span>
                <h2 class="section-title mb-4">Building the Future, One Line of Code at a Time</h2>
                <div class="text-muted fs-5 lh-lg mb-4">
                    <?= nl2br(e($content['about_text'])) ?>
                </div>
                
                <?php if (!empty($content['mission_statement'])): ?>
                    <div class="p-4 bg-white rounded-4 border-start border-4 border-primary shadow-sm">
                        <h5 class="fw-bold text-primary mb-2">Our Mission</h5>
                        <p class="fst-italic mb-0">"<?= e($content['mission_statement']) ?>"</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-6 animate-on-scroll" style="animation-delay: 0.2s;">
                <div class="position-relative">
                    <?php if (!empty($content['company_image'])): ?>
                        <img src="<?= BASE_PATH ?>/uploads/about/<?= e($content['company_image']) ?>" 
                             alt="Our Office"
                             class="img-fluid rounded-4 shadow-lg w-100 object-fit-cover"
                             style="height: 400px;">
                    <?php else: ?>
                        <div class="bg-light rounded-4 w-100 d-flex align-items-center justify-content-center shadow-sm" style="height: 400px;">
                             <i class="bi bi-building text-muted" style="font-size: 4rem;"></i>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Stats Overlay -->
                    <div class="position-absolute bottom-0 start-0 w-100 p-4">
                        <div class="row g-2">
                            <div class="col-4">
                                <div class="card border-0 shadow-sm text-center py-3 bg-white bg-opacity-90 backdrop-blur">
                                    <div class="h3 fw-bold text-primary mb-0"><?= e($content['founded_year'] ?? '2020') ?></div>
                                    <small class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">Founded</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card border-0 shadow-sm text-center py-3 bg-white bg-opacity-90 backdrop-blur">
                                    <div class="h3 fw-bold text-success mb-0"><?= e($content['employee_count'] ?? '10+') ?></div>
                                    <small class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">Experts</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="card border-0 shadow-sm text-center py-3 bg-white bg-opacity-90 backdrop-blur">
                                    <div class="h3 fw-bold text-info mb-0">100%</div>
                                    <small class="text-uppercase fw-bold text-muted" style="font-size: 0.7rem;">Remote</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Process Section -->
<section class="section bg-light">
    <div class="container">
        <div class="text-center mb-5 animate-on-scroll">
            <h2 class="section-title">Our Delivery Process</h2>
            <p class="lead mt-3">We believe in transparency. Here is how we take your idea from concept to deployment.</p>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10 animate-on-scroll">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="card-body p-5 text-center">
                        <div class="process-steps d-flex justify-content-between position-relative flex-wrap gap-4">
                            <!-- Step 1 -->
                            <div class="process-step text-center position-relative z-1">
                                <div class="icon-wrapper mb-3 mx-auto bg-primary text-white">
                                    <i class="bi bi-chat-dots fs-3"></i>
                                </div>
                                <h5 class="fw-bold">Discovery</h5>
                                <p class="small text-muted">Understanding your needs</p>
                            </div>
                            
                            <!-- Step 2 -->
                            <div class="process-step text-center position-relative z-1">
                                <div class="icon-wrapper mb-3 mx-auto bg-info text-white">
                                    <i class="bi bi-pencil-square fs-3"></i>
                                </div>
                                <h5 class="fw-bold">Design</h5>
                                <p class="small text-muted">Prototyping & UI/UX</p>
                            </div>
                            
                            <!-- Step 3 -->
                            <div class="process-step text-center position-relative z-1">
                                <div class="icon-wrapper mb-3 mx-auto bg-warning text-white">
                                    <i class="bi bi-code-slash fs-3"></i>
                                </div>
                                <h5 class="fw-bold">Development</h5>
                                <p class="small text-muted">Building the solution</p>
                            </div>
                            
                            <!-- Step 4 -->
                            <div class="process-step text-center position-relative z-1">
                                <div class="icon-wrapper mb-3 mx-auto bg-success text-white">
                                    <i class="bi bi-rocket-takeoff fs-3"></i>
                                </div>
                                <h5 class="fw-bold">Launch</h5>
                                <p class="small text-muted">Deployment & Support</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($teamMembers)): ?>
<!-- Team Section -->
<section class="section section-light">
    <div class="container">
        <div class="text-center mb-5 animate-on-scroll">
            <h2 class="section-title">Meet the Leadership</h2>
            <p class="lead mt-3">The minds committed to your success.</p>
        </div>

        <div class="row g-4">
            <?php foreach ($teamMembers as $index => $member): ?>
                <div class="col-md-6 col-lg-4 animate-on-scroll" style="animation-delay: <?= $index * 0.1 ?>s;">
                    <div class="card h-100 border-0 shadow-sm hover-lift overflow-hidden text-center">
                        <div class="card-header border-0 p-0" style="height: 100px; background: var(--gradient-ocean);"></div>
                        
                        <div class="card-body pt-0 position-relative">
                            <div class="position-absolute start-50 translate-middle" style="top: 0;">
                                <?php if (!empty($member['image_path'])): ?>
                                    <img src="<?= BASE_PATH ?>/uploads/about/<?= e($member['image_path']) ?>" 
                                         alt="<?= e($member['name']) ?>"
                                         class="rounded-circle border border-4 border-white shadow-md bg-white"
                                         style="width: 100px; height: 100px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle border border-4 border-white shadow-md bg-white d-flex align-items-center justify-content-center text-primary"
                                         style="width: 100px; height: 100px;">
                                        <i class="bi bi-person fs-1"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-5 pt-4">
                                <h4 class="fw-bold mb-1"><?= e($member['name']) ?></h4>
                                <p class="text-primary fw-medium mb-3"><?= e($member['position']) ?></p>
                                
                                <?php if (!empty($member['bio'])): ?>
                                    <p class="text-muted small mb-4">
                                        <?= e($member['bio']) ?>
                                    </p>
                                <?php endif; ?>
                                
                                <div class="d-flex justify-content-center gap-3">
                                    <a href="#" class="text-muted hover-primary transition">
                                        <i class="bi bi-linkedin fs-5"></i>
                                    </a>
                                    <a href="#" class="text-muted hover-primary transition">
                                        <i class="bi bi-twitter-x fs-5"></i>
                                    </a>
                                    <a href="#" class="text-muted hover-primary transition">
                                        <i class="bi bi-envelope fs-5"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="section">
    <div class="container">
        <div class="text-center animate-on-scroll" 
             style="background: var(--gradient-ocean); 
                    border-radius: 25px; 
                    padding: 4rem 2rem; 
                    color: white;
                    position: relative;
                    overflow: hidden;">
            
            <!-- Wave pattern overlay -->
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; 
                        background: url('data:image/svg+xml,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1200 120\' preserveAspectRatio=\'none\'><path d=\'M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z\' fill=\'%23ffffff\' opacity=\'0.1\'/></svg>');
                        background-size: cover;
                        opacity: 0.3;">
            </div>

            <div class="position-relative z-1">
                <h2 class="fw-bold mb-3">Ready to start your project?</h2>
                <p class="fs-5 mb-4 text-white-50">
                    We are ready to turn your vision into a digital reality.
                </p>
                <a href="<?= BASE_PATH ?>/contact" class="btn btn-light btn-lg rounded-pill fw-bold px-5 shadow-sm hover-scale">
                    Get in Touch
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg) !important;
    }
    .hover-scale:hover {
        transform: scale(1.05);
    }
    .backdrop-blur {
        backdrop-filter: blur(5px);
    }
    .hover-primary:hover {
        color: var(--bs-primary) !important;
    }
</style>