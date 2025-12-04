<?php 
// views/client/profile.php

/**
 * @var array $data Contains 'user' data
 */

include VIEW_PATH . '_partials/AdminNav.php'; 

$user = $data['user'] ?? [];
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white p-4 border-0">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center me-3 shadow-sm" 
                             style="width: 64px; height: 64px; font-size: 1.5rem; font-weight: bold;">
                            <?= substr($user['username'] ?? 'U', 0, 1) ?>
                        </div>
                        <div>
                            <h2 class="h4 mb-1 fw-bold">My Profile</h2>
                            <p class="mb-0 opacity-75">Manage your account settings</p>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">Username</label>
                                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user['username'] ?? '') ?>" readonly>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">Email Address</label>
                                <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($user['email'] ?? '') ?>" readonly>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">Role</label>
                                <input type="text" class="form-control bg-light" value="<?= ucfirst(htmlspecialchars($user['role'] ?? 'Client')) ?>" readonly>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">Member Since</label>
                                <input type="text" class="form-control bg-light" value="<?= isset($user['created_at']) ? date('F j, Y', strtotime($user['created_at'])) : 'N/A' ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="mt-5 pt-4 border-top">
                            <h5 class="fw-bold mb-4">Change Password</h5>
                            
                            <?php if (isset($_GET['success'])): ?>
                                <div class="alert alert-success d-flex align-items-center mb-4">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <div>Password updated successfully.</div>
                                </div>
                            <?php endif; ?>

                            <?php if (isset($_GET['error'])): ?>
                                <div class="alert alert-danger d-flex align-items-center mb-4">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div><?= htmlspecialchars($_GET['error']) ?></div>
                                </div>
                            <?php endif; ?>

                            <form action="<?= BASE_PATH ?>/client/profile/update-password" method="POST">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label for="current_password" class="form-label fw-semibold text-muted">Current Password</label>
                                        <input type="password" class="form-control bg-light" id="current_password" name="current_password" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="new_password" class="form-label fw-semibold text-muted">New Password</label>
                                        <input type="password" class="form-control bg-light" id="new_password" name="new_password" required minlength="8">
                                        <div class="form-text">Min 8 characters</div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="confirm_password" class="form-label fw-semibold text-muted">Confirm New Password</label>
                                        <input type="password" class="form-control bg-light" id="confirm_password" name="confirm_password" required minlength="8">
                                    </div>
                                    <div class="col-12 mt-3">
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                            <i class="bi bi-key me-2"></i> Update Password
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="mt-5 pt-4 border-top">
                            <h5 class="fw-bold mb-3 text-danger">Danger Zone</h5>
                            <p class="text-muted small mb-3">If you need to change your password or delete your account, please contact support.</p>
                            <a href="<?= BASE_PATH ?>/contact?subject=Account%20Support" class="btn btn-outline-danger rounded-pill px-4">
                                <i class="bi bi-shield-lock me-2"></i> Contact Support
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
