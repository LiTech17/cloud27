<?php 
// views/login.php

/**
 * @var array $data Contains data passed from the controller, including 'error'
 */
$error = $data['error'] ?? '';
$username_value = $data['username_value'] ?? '';
?>

<section class="section flex items-center justify-center" style="min-height: 70vh;">
    <div class="container-sm mx-auto">
        <div class="max-w-md mx-auto">
            <!-- Login Card -->
            <div class="card shadow-xl animate-scale">
                <!-- Card Header -->
                <div class="card-header text-center pb-6">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-brand flex items-center justify-center">
                        <svg class="w-8 h-8" style="color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h2 class="heading-3">Admin Panel Login</h2>
                    <p class="text-secondary text-sm mt-2">Enter your credentials to access the dashboard</p>
                </div>

                <!-- Error Alert -->
                <?php if ($error): ?>
                    <div class="alert alert-error mb-6 animate-slide-down">
                        <svg class="alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="alert-content">
                            <p class="alert-message"><?= htmlspecialchars($error) ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" action="<?= BASE_PATH ?>/login" id="loginForm">
                    
                    <!-- Username Field -->
                    <div class="form-group">
                        <label for="username" class="form-label form-label-required">Username</label>
                        <div class="relative">
                            <input type="text" 
                                   id="username" 
                                   name="username" 
                                   class="form-input" 
                                   placeholder="Enter your username"
                                   value="<?= htmlspecialchars($username_value) ?>"
                                   required 
                                   autocomplete="username"
                                   autofocus>
                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-tertiary" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password" class="form-label form-label-required">Password</label>
                        <div class="relative">
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-input" 
                                   placeholder="Enter your password"
                                   required 
                                   autocomplete="current-password">
                            <button type="button" 
                                    id="togglePassword"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-tertiary hover:text-primary transition-colors"
                                    aria-label="Toggle password visibility">
                                <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                        <span class="form-helper">Use a strong password to protect your account</span>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="form-check cursor-pointer">
                            <input type="checkbox" name="remember_me" class="form-check-input" id="remember">
                            <span class="text-sm text-secondary">Remember me</span>
                        </label>
                        <a href="<?= BASE_PATH ?>/forgot-password" class="text-sm text-brand hover:text-primary-hover">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-lg w-full" id="submitBtn">
                        <span id="btnText">Sign In</span>
                        <span id="btnSpinner" class="spinner spinner-sm" style="display: none;"></span>
                    </button>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t"></div>
                        </div>
                        <div class="relative flex justify-center text-xs">
                            <span class="bg-primary px-2 text-tertiary">New to Cloud27?</span>
                        </div>
                    </div>

                    <!-- Sign Up Link -->
                    <div class="text-center">
                        <a href="<?= BASE_PATH ?>/contact" class="btn btn-outline w-full">
                            Request Access
                        </a>
                    </div>
                </form>
            </div>

            <!-- Security Notice -->
            <div class="text-center mt-6">
                <p class="text-xs text-tertiary">
                    🔒 Your connection is secure and encrypted
                </p>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle functionality
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            eyeOpen.classList.toggle('hidden');
            eyeClosed.classList.toggle('hidden');
        });
    }

    // Form submission loading state
    const form = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const btnSpinner = document.getElementById('btnSpinner');

    if (form) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            btnText.style.display = 'none';
            btnSpinner.style.display = 'inline-block';
        });
    }

    // Clear error message on input
    const inputs = form.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('input', function() {
            const errorAlert = document.querySelector('.alert-error');
            if (errorAlert) {
                errorAlert.style.opacity = '0';
                setTimeout(() => errorAlert.remove(), 300);
            }
        });
    });
});
</script>

<style>
/* Custom styles for login page icons */
.relative {
    position: relative;
}

.absolute {
    position: absolute;
}

.right-3 {
    right: 0.75rem;
}

.top-1\/2 {
    top: 50%;
}

.transform {
    transform: translateY(-50%);
}

.w-5 {
    width: 1.25rem;
}

.h-5 {
    height: 1.25rem;
}

.w-8 {
    width: 2rem;
}

.h-8 {
    height: 2rem;
}

.w-16 {
    width: 4rem;
}

.h-16 {
    height: 4rem;
}
</style>