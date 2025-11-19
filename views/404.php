<?php 
// views/404.php

/**
 * @var string $errorMessage Optional detailed error message
 */
$errorMessage = $errorMessage ?? 'The requested URI does not match any route definition.';
?>

<section class="section flex items-center justify-center" style="min-height: 70vh;">
    <div class="container">
        <div class="max-w-3xl mx-auto text-center">
            
            <!-- 404 Illustration -->
            <div class="mb-8 animate-fade-in">
                <svg class="mx-auto" style="width: 200px; height: 200px;" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Background Circle -->
                    <circle cx="100" cy="100" r="80" fill="var(--color-bg-tertiary)" opacity="0.5"/>
                    
                    <!-- 404 Text -->
                    <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" 
                          style="font-size: 60px; font-weight: 700; fill: var(--color-primary);">404</text>
                    
                    <!-- Sad Face -->
                    <circle cx="70" cy="80" r="3" fill="var(--color-text-secondary)"/>
                    <circle cx="130" cy="80" r="3" fill="var(--color-text-secondary)"/>
                    <path d="M 70 130 Q 100 115 130 130" stroke="var(--color-text-secondary)" 
                          stroke-width="3" fill="none" stroke-linecap="round"/>
                </svg>
            </div>

            <!-- Error Title -->
            <h1 class="heading-1 mb-4 animate-slide-down" style="color: var(--color-error);">
                🚨 Page Not Found
            </h1>

            <!-- Error Description -->
            <p class="text-lg text-secondary mb-8 animate-slide-up" style="animation-delay: 0.1s;">
                Oops! We couldn't find the page you were looking for on our server.
                The page may have been moved, deleted, or never existed.
            </p>

            <!-- Error Details Card -->
            <div class="card bg-error-light border-l-4 mb-8 animate-slide-up" 
                 style="border-left-color: var(--color-error); animation-delay: 0.2s;">
                <div class="flex items-start gap-4">
                    <svg class="flex-shrink-0 mt-1" style="width: 24px; height: 24px; color: var(--color-error);" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-1 text-left">
                        <h4 class="font-semibold mb-2" style="color: var(--color-error);">Error Details:</h4>
                        <p class="text-sm" style="color: var(--color-error); opacity: 0.9;">
                            <?= htmlspecialchars($errorMessage) ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8 animate-slide-up" style="animation-delay: 0.3s;">
                <a href="<?= BASE_PATH ?>/" class="btn btn-primary btn-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Go to Homepage
                </a>
                
                <button onclick="history.back()" class="btn btn-outline btn-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Go Back
                </button>
            </div>

            <!-- Helpful Links -->
            <div class="card bg-secondary p-6 animate-scale" style="animation-delay: 0.4s;">
                <h3 class="font-semibold mb-4">Looking for something specific?</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <a href="<?= BASE_PATH ?>/services" class="p-3 rounded-lg bg-primary hover:bg-tertiary transition-colors">
                        <svg class="w-6 h-6 mx-auto mb-2" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium">Services</span>
                    </a>

                    <a href="<?= BASE_PATH ?>/about" class="p-3 rounded-lg bg-primary hover:bg-tertiary transition-colors">
                        <svg class="w-6 h-6 mx-auto mb-2" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium">About Us</span>
                    </a>

                    <a href="<?= BASE_PATH ?>/contact" class="p-3 rounded-lg bg-primary hover:bg-tertiary transition-colors">
                        <svg class="w-6 h-6 mx-auto mb-2" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium">Contact</span>
                    </a>
                </div>
            </div>

            <!-- Fun Error Code -->
            <div class="mt-8 text-center opacity-50 animate-fade-in" style="animation-delay: 0.5s;">
                <p class="text-xs text-tertiary font-mono">
                    Error Code: HTTP 404 | Request ID: <?= uniqid() ?>
                </p>
            </div>

        </div>
    </div>
</section>

<style>
/* Animation Keyframes */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes scale {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Apply Animations */
.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
}

.animate-slide-down {
    animation: slideDown 0.6s ease-out forwards;
}

.animate-slide-up {
    animation: slideUp 0.6s ease-out forwards;
    opacity: 0;
}

.animate-scale {
    animation: scale 0.6s ease-out forwards;
    opacity: 0;
}

/* Utility Classes */
.w-5 { width: 1.25rem; }
.h-5 { height: 1.25rem; }
.w-6 { width: 1.5rem; }
.h-6 { height: 1.5rem; }
.flex-shrink-0 { flex-shrink: 0; }
.mt-1 { margin-top: 0.25rem; }
</style>

<script>
// Add a fun console message for developers
console.log(
    '%c🚨 404 Error Detected!',
    'font-size: 20px; color: #ef4444; font-weight: bold;'
);
console.log(
    '%cLooks like you found a broken link. Please report this to our team!',
    'font-size: 14px; color: #6b7280;'
);
console.log(
    '%cEmail: support@cloud27.co.za',
    'font-size: 14px; color: #6366f1; font-weight: bold;'
);
</script>