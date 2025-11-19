<?php 
// views/contact.php

/**
 * @var array $data Contains data passed from the controller
 */
$initialMessage = $data['initialMessage'] ?? ''; 
$statusMessage = $data['statusMessage'] ?? ''; 
$statusType = $data['statusType'] ?? 'alert-info';
?>

<section class="section">
    <div class="container-md mx-auto">
        <!-- Page Header -->
        <div class="text-center mb-8">
            <h2 class="heading-2 mb-3">Get in Touch</h2>
            <p class="text-lg text-secondary max-w-2xl mx-auto">
                We'd love to hear about your project. Fill out the form below and we'll get back to you shortly.
            </p>
        </div>

        <div class="max-w-xl mx-auto">
            <!-- Status Message Block (Server-side) -->
            <?php if (!empty($statusMessage)): ?>
                <div class="alert alert-<?= $statusType === 'alert-success' ? 'success' : 'error' ?> mb-6 animate-slide-down">
                    <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="alert-content">
                        <p class="alert-message"><?= htmlspecialchars($statusMessage) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form Status Message (AJAX) -->
            <div id="form-status-message" style="display: none;" class="mb-6 animate-slide-down"></div>

            <!-- Contact Form Card -->
            <div class="card shadow-lg">
                <form id="contactForm" action="<?= BASE_PATH ?>/contact" method="POST" class="animate-fade-in"> 
                    
                    <!-- Name Field -->
                    <div class="form-group">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               class="form-input" 
                               placeholder="John Doe"
                               required>
                    </div>
                    
                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               class="form-input" 
                               placeholder="john@example.com"
                               required>
                        <span class="form-helper">We'll never share your email with anyone else.</span>
                    </div>
                    
                    <!-- Message Field -->
                    <div class="form-group">
                        <label for="message" class="form-label">Project Details</label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="6" 
                                  class="form-textarea" 
                                  placeholder="Tell us about your project..."
                                  required><?= htmlspecialchars($initialMessage) ?></textarea>
                        <span class="form-helper">Please provide as much detail as possible about your requirements.</span>
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-lg w-full" id="submit-btn">
                        <span id="btn-text">Send Inquiry</span>
                        <span id="btn-spinner" style="display: none;" class="spinner spinner-sm"></span>
                    </button>
                </form>
            </div>

            <!-- Contact Info Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                <div class="card text-center bg-brand-light">
                    <svg class="mx-auto mb-3" style="width: 32px; height: 32px; color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <h4 class="text-base font-semibold mb-1">Email Us</h4>
                    <p class="text-sm text-secondary">info@cloud27.co.za</p>
                </div>

                <div class="card text-center bg-brand-light">
                    <svg class="mx-auto mb-3" style="width: 32px; height: 32px; color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h4 class="text-base font-semibold mb-1">Response Time</h4>
                    <p class="text-sm text-secondary">Within 24 hours</p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const statusDiv = document.getElementById('form-status-message');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');

    // Helper function to show status messages with modern styling
    function showStatus(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 'alert-error';
        const icon = type === 'success' 
            ? `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
               </svg>`
            : `<svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
               </svg>`;

        statusDiv.className = `alert ${alertClass} animate-slide-down`;
        statusDiv.innerHTML = `
            ${icon}
            <div class="alert-content">
                <p class="alert-message">${message}</p>
            </div>
        `;
        statusDiv.style.display = 'flex';

        // Scroll to message
        statusDiv.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        submitBtn.disabled = true;
        btnText.style.display = 'none';
        btnSpinner.style.display = 'inline-block';
        statusDiv.style.display = 'none';

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(response => {
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                return response.json();
            } else {
                throw new Error("Server response was not JSON.");
            }
        })
        .then(data => {
            if (data.success) {
                showStatus(data.message, 'success');
                form.reset();
                
                // Clear query string from URL
                history.pushState(null, '', location.pathname);
            } else {
                showStatus(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Submission Error:', error);
            showStatus('A critical error occurred. Please try again or contact us directly.', 'error');
        })
        .finally(() => {
            // Restore button state
            submitBtn.disabled = false;
            btnText.style.display = 'inline';
            btnSpinner.style.display = 'none';
        });
    });
});
</script>