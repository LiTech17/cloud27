<?php 
// views/contact.php

/**
 * @var array $data Contains data passed from the controller
 */
$initialMessage = $data['initialMessage'] ?? ''; 
$statusMessage = $data['statusMessage'] ?? ''; 
$statusType = $data['statusType'] ?? 'alert-info';
$prefilledName = $data['prefilledName'] ?? '';
$prefilledEmail = $data['prefilledEmail'] ?? '';
?>

<!-- Hero Section -->
<section class="hero-section" style="min-height: 40vh;">
    <div class="container hero-content text-center">
        <h1 class="mb-3 animate-on-scroll">Get in Touch</h1>
        <p class="lead animate-on-scroll" style="animation-delay: 0.2s;">
            We'd love to hear about your project. Fill out the form below and we'll get back to you shortly.
        </p>
    </div>
</section>

<section class="section section-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                
                <!-- Status Message Block (Server-side) -->
                <?php if (!empty($statusMessage)): ?>
                    <div class="alert alert-<?= $statusType === 'alert-success' ? 'success' : 'danger' ?> mb-4 animate-slide-down d-flex align-items-center shadow-sm">
                        <i class="bi bi-<?= $statusType === 'alert-success' ? 'check-circle' : 'exclamation-circle' ?> fs-4 me-3"></i>
                        <div>
                            <?= htmlspecialchars($statusMessage) ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Form Status Message (AJAX) -->
                <div id="form-status-message" style="display: none;" class="mb-4 animate-slide-down shadow-sm"></div>

                <!-- Contact Form Card -->
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden animate-fade-in">
                    <div class="card-body p-4 p-md-5">
                        <form id="contactForm" action="<?= BASE_PATH ?>/contact" method="POST"> 
                            
                            <div class="row g-4">
                                <!-- Name Field -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-semibold">Your Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                        <input type="text" 
                                               id="name" 
                                               name="name" 
                                               class="form-control bg-light border-start-0 ps-0" 
                                               placeholder="John Doe"
                                               value="<?= htmlspecialchars($prefilledName) ?>"
                                               required>
                                    </div>
                                </div>
                                
                                <!-- Email Field -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold">Your Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                        <input type="email" 
                                               id="email" 
                                               name="email" 
                                               class="form-control bg-light border-start-0 ps-0" 
                                               placeholder="john@example.com"
                                               value="<?= htmlspecialchars($prefilledEmail) ?>"
                                               required>
                                    </div>
                                </div>
                                
                                <!-- Message Field -->
                                <div class="col-12">
                                    <label for="message" class="form-label fw-semibold">Project Details</label>
                                    <textarea id="message" 
                                              name="message" 
                                              rows="6" 
                                              class="form-control bg-light" 
                                              placeholder="Tell us about your project..."
                                              required><?= htmlspecialchars($initialMessage) ?></textarea>
                                    <div class="form-text">Please provide as much detail as possible about your requirements.</div>
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm" id="submit-btn">
                                        <span id="btn-text"><i class="bi bi-send me-2"></i> Send Inquiry</span>
                                        <span id="btn-spinner" style="display: none;" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Contact Info Cards -->
                <div class="row g-4 mt-4">
                    <div class="col-md-6 animate-on-scroll">
                        <div class="card border-0 shadow-sm h-100 bg-white rounded-4">
                            <div class="card-body text-center p-4">
                                <div class="d-inline-flex align-items-center justify-content-center width-50 height-50 rounded-circle bg-primary-subtle text-primary mb-3" style="width: 50px; height: 50px;">
                                    <i class="bi bi-envelope fs-4"></i>
                                </div>
                                <h5 class="fw-bold mb-1">Email Us</h5>
                                <p class="text-muted mb-0">info@cloud27.co.za</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 animate-on-scroll" style="animation-delay: 0.1s;">
                        <div class="card border-0 shadow-sm h-100 bg-white rounded-4">
                            <div class="card-body text-center p-4">
                                <div class="d-inline-flex align-items-center justify-content-center width-50 height-50 rounded-circle bg-info-subtle text-info mb-3" style="width: 50px; height: 50px;">
                                    <i class="bi bi-clock-history fs-4"></i>
                                </div>
                                <h5 class="fw-bold mb-1">Response Time</h5>
                                <p class="text-muted mb-0">Within 24 hours</p>
                            </div>
                        </div>
                    </div>
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
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const icon = type === 'success' ? 'bi-check-circle' : 'bi-exclamation-circle';

        statusDiv.className = `alert ${alertClass} animate-slide-down d-flex align-items-center shadow-sm`;
        statusDiv.innerHTML = `
            <i class="bi ${icon} fs-4 me-3"></i>
            <div>${message}</div>
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

<style>
.form-control:focus {
    box-shadow: none;
    border-color: var(--bs-primary);
    background-color: #fff;
}
.input-group-text {
    border-color: var(--bs-border-color);
}
.form-control {
    border-color: var(--bs-border-color);
    padding: 0.75rem 1rem;
}
</style>