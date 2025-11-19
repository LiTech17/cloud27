<?php 
// views/admin/edit_user.php

/**
 * @var array $data Contains data passed from the controller, including 'user', 'title', and 'error'
 */
include VIEW_PATH . '_partials/AdminNav.php';

$user = $data['user'] ?? null;
$id = $user['id'] ?? null;
$title = $data['title'] ?? ($id ? 'Edit User Account' : 'Add New User');
$error = $data['error'] ?? null;

// Determine form action and initial values
$action = BASE_PATH . '/admin/users/save';

// Form default values
$username = htmlspecialchars($user['username'] ?? '');
$email = htmlspecialchars($user['email'] ?? '');
// Using empty string if not set, 0 is falsey in PHP, but safer to treat 1 as true
$isAdmin = $user['is_admin'] ?? 0;
?>

<!-- Main Content Container with spacing -->
<div class="container py-6 md:py-8">
    <!-- Page Title -->
    <h2 class="heading-2 mb-6 text-primary"><?= htmlspecialchars($title) ?></h2>
    
    <!-- Card component wrapper for the form -->
    <div class="card max-w-lg mx-auto">
        <div class="card-body">
        
            <!-- Error Alert -->
            <?php if ($error): ?>
                <div class="alert alert-error mb-6">
                    <svg class="alert-icon" fill="currentColor" viewBox="0 0 20 20">
                        <!-- Icon for error alert (Exclamation triangle) -->
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div class="alert-content">
                        <p class="alert-message"><?= htmlspecialchars($error) ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= $action ?>">
                
                <?php if ($id): ?>
                    <input type="hidden" name="id" value="<?= $id ?>">
                <?php endif; ?>
                
                <!-- Username Field -->
                <div class="form-group">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" id="username" name="username" value="<?= $username ?>" class="form-input" required>
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" value="<?= $email ?>" class="form-input" required>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-input" 
                           placeholder="<?= $id ? '••••••••' : 'Required' ?>"
                           <?= $id ? '' : 'required' ?>>
                    
                    <?php if ($id): ?>
                        <span class="form-helper">Leave blank to keep the current password.</span>
                    <?php else: ?>
                        <span class="form-helper">Required for a new user account.</span>
                    <?php endif; ?>
                </div>
                
                <!-- Admin Role Checkbox -->
                <div class="form-group flex items-center gap-3">
                    <input type="checkbox" id="is_admin" name="is_admin" value="1" 
                           class="w-4 h-4 text-brand bg-primary border-border rounded focus:ring-brand focus:ring-2" 
                           <?= $isAdmin ? 'checked' : '' ?>>
                    <label for="is_admin" class="form-label mb-0 cursor-pointer">
                        Admin Role (Check for Admin, uncheck for Client)
                    </label>
                </div>

                <!-- Form Actions (Buttons) -->
                <div class="flex gap-4 mt-6">
                    <button type="submit" class="btn btn-primary">
                        <?= $id ? 'Update User' : 'Add User' ?>
                    </button>
                    <a href="<?= BASE_PATH ?>/admin/users" class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
// Ensure the page is properly closed with the Footer partial
include VIEW_PATH . '_partials/Footer.php';
?>