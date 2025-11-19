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

<style>
    /* Page-Specific Professional Styles */
    .hero-modern {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%);
        color: white;
        border-radius: var(--radius-2xl);
        padding: var(--space-16) var(--space-4);
        position: relative;
        overflow: hidden;
        margin-bottom: var(--space-12);
    }

    /* Abstract background shape for visual interest */
    .hero-modern::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 600px;
        height: 600px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        filter: blur(60px);
        pointer-events: none;
    }

    .stat-card {
        background: var(--color-bg-primary);
        border: 1px solid var(--color-border-light);
        padding: var(--space-6);
        border-radius: var(--radius-xl);
        box-shadow: var(--shadow-lg);
        transition: transform var(--transition-base);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        border-color: var(--color-primary-light);
    }

    .team-avatar {
        width: 120px;
        height: 120px;
        border: 4px solid white;
        box-shadow: var(--shadow-md);
        object-fit: cover;
    }
    
    /* Process Section Styles */
    .process-section {
        background-color: var(--color-bg-secondary);
        border-radius: var(--radius-2xl);
        padding: var(--space-8);
        margin-bottom: var(--space-12);
    }
</style>

<section class="section animate-fade-in">
    <div class="container">
        <div class="hero-modern text-center">
            <div class="relative z-10 max-w-3xl mx-auto">
                <?php if (!empty($content['hero_image'])): ?>
                    <img src="<?= BASE_PATH ?>/uploads/about/<?= e($content['hero_image']) ?>" 
                         alt="Brand Logo"
                         class="mx-auto mb-6 rounded-xl shadow-lg bg-white p-2"
                         width="120" height="auto">
                <?php endif; ?>
                
                <h1 class="heading-1 text-white mb-4">
                    <?= e($content['company_name'] ?? 'Cloud27') ?>
                </h1>
                
                <?php if (!empty($content['tagline'])): ?>
                    <p class="text-xl md:text-2xl opacity-90 font-light leading-relaxed">
                        <?= e($content['tagline']) ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="section mb-12">
    <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            
            <div class="animate-slide-up">
                <span class="text-brand font-bold tracking-wider uppercase text-sm mb-2 block">Who We Are</span>
                <h2 class="heading-2 mb-6">Building the Future, One Line of Code at a Time</h2>
                <div class="text-secondary space-y-4 text-lg leading-relaxed">
                    <?= nl2br(e($content['about_text'])) ?>
                </div>
                
                <?php if (!empty($content['mission_statement'])): ?>
                    <div class="mt-8 p-6 bg-brand-light rounded-xl border-l-4 border-brand">
                        <h3 class="font-bold text-brand mb-2">Our Mission</h3>
                        <p class="text-secondary italic">"<?= e($content['mission_statement']) ?>"</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="space-y-6 animate-slide-up" style="animation-delay: 0.2s;">
                <?php if (!empty($content['company_image'])): ?>
                    <img src="<?= BASE_PATH ?>/uploads/about/<?= e($content['company_image']) ?>" 
                         alt="Our Office"
                         class="rounded-2xl shadow-xl w-full object-cover h-64 md:h-80">
                <?php else: ?>
                    <div class="bg-secondary rounded-2xl w-full h-64 flex items-center justify-center">
                         <svg class="w-16 h-16 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-3 gap-4">
                    <div class="stat-card text-center">
                        <div class="text-3xl font-bold text-primary mb-1">
                            <?= e($content['founded_year'] ?? '2020') ?>
                        </div>
                        <div class="text-xs text-secondary uppercase font-bold">Founded</div>
                    </div>
                    <div class="stat-card text-center">
                        <div class="text-3xl font-bold text-success mb-1">
                            <?= e($content['employee_count'] ?? '10+') ?>
                        </div>
                        <div class="text-xs text-secondary uppercase font-bold">Experts</div>
                    </div>
                    <div class="stat-card text-center">
                        <div class="text-3xl font-bold text-info mb-1">
                            100%
                        </div>
                        <div class="text-xs text-secondary uppercase font-bold">Remote</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section mb-12">
    <div class="container">
        <div class="process-section text-center">
            <h2 class="heading-2 mb-4">Our Delivery Process</h2>
            <p class="text-secondary max-w-2xl mx-auto mb-8">
                We believe in transparency. Here is how we take your idea from concept to deployment.
            </p>
            
            <div class="bg-white p-6 rounded-xl shadow-sm mx-auto max-w-4xl">
                

[Image of software development lifecycle agile process diagram]

            </div>
        </div>
    </div>
</section>

<?php if (!empty($teamMembers)): ?>
<section class="section">
    <div class="container">
        <div class="text-center mb-10">
            <h2 class="heading-2 mb-3">Meet the Leadership</h2>
            <p class="text-lg text-secondary max-w-2xl mx-auto">
                The minds committed to your success.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($teamMembers as $index => $member): ?>
                <div class="card text-center hover-lift animate-fade-in p-0 overflow-hidden" style="animation-delay: <?= $index * 0.1 ?>s;">
                    
                    <div class="h-24 bg-gradient-to-r from-primary-light to-white"></div>
                    
                    <div class="px-6 pb-8 -mt-12 relative">
                        <?php if (!empty($member['image_path'])): ?>
                            <img src="<?= BASE_PATH ?>/uploads/about/<?= e($member['image_path']) ?>" 
                                 alt="<?= e($member['name']) ?>"
                                 class="team-avatar rounded-full mx-auto bg-white">
                        <?php else: ?>
                            <div class="team-avatar rounded-full mx-auto bg-white flex items-center justify-center text-primary">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            </div>
                        <?php endif; ?>

                        <h3 class="text-xl font-bold mt-4 text-primary"><?= e($member['name']) ?></h3>
                        <p class="text-sm font-medium text-accent mb-4"><?= e($member['position']) ?></p>
                        
                        <?php if (!empty($member['bio'])): ?>
                            <p class="text-secondary text-sm leading-relaxed mb-4">
                                <?= e($member['bio']) ?>
                            </p>
                        <?php endif; ?>

                        <div class="flex justify-center gap-3 mt-4">
                            <a href="#" class="text-tertiary hover:text-primary transition-colors">
                                <span class="sr-only">LinkedIn</span>
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section text-center mb-12">
    <div class="container-md mx-auto">
        <div class="relative rounded-2xl overflow-hidden p-12 bg-primary text-white shadow-2xl">
            <div class="absolute top-0 left-0 w-full h-full bg-white opacity-5 pointer-events-none" 
                 style="background-image: radial-gradient(circle, #ffffff 2px, transparent 2.5px); background-size: 30px 30px;">
            </div>

            <div class="relative z-10">
                <h2 class="heading-2 text-white mb-4">Ready to start your project?</h2>
                <p class="text-lg text-blue-100 mb-8 max-w-xl mx-auto">
                    We are ready to turn your vision into a digital reality.
                </p>
                <a href="<?= BASE_PATH ?>/contact" class="inline-block bg-white text-primary font-bold py-3 px-8 rounded-lg shadow-md hover:bg-gray-50 hover:shadow-lg transform transition hover:-translate-y-1">
                    Get in Touch
                </a>
            </div>
        </div>
    </div>
</section>