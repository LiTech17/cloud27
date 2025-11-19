<?php 
// views/admin/dashboard.php

/**
 * @var array $data Contains data passed from the controller
 */
include VIEW_PATH . '_partials/AdminNav.php'; 

$username = $_SESSION['username'] ?? 'Admin';
$role = $_SESSION['role'] ?? 'Guest';
$isAdmin = $_SESSION['is_admin'] ?? false;
?>

<section class="section">
    <div class="container">
        <!-- Dashboard Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8 pb-6 border-b">
            <div>
                <h1 class="heading-2 mb-2">Welcome Back, <?= htmlspecialchars($username) ?>! 👋</h1>
                <p class="text-secondary">
                    Role: <span class="badge badge-primary"><?= ucfirst($role) ?></span>
                </p>
            </div>
            <div class="text-sm text-tertiary">
                <?= date('l, F j, Y') ?>
            </div>
        </div>

        <!-- Quick Stats (Optional - can be populated with real data) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <div class="card bg-brand-light">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-secondary mb-1">Total Services</p>
                        <h3 class="text-2xl font-bold text-brand">12</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-brand flex items-center justify-center">
                        <svg class="w-6 h-6 text-inverse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="card bg-success-light">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-secondary mb-1">Active Packages</p>
                        <h3 class="text-2xl font-bold text-success">8</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-success flex items-center justify-center">
                        <svg class="w-6 h-6 text-inverse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="card bg-warning-light">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-secondary mb-1">Pending Inquiries</p>
                        <h3 class="text-2xl font-bold text-warning">5</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-warning flex items-center justify-center">
                        <svg class="w-6 h-6 text-inverse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <?php if ($isAdmin): ?>
            <div class="card bg-error-light">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-secondary mb-1">Total Users</p>
                        <h3 class="text-2xl font-bold text-error">24</h3>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-error flex items-center justify-center">
                        <svg class="w-6 h-6 text-inverse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Action Cards Grid -->
        <h2 class="heading-3 mb-6">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <!-- Service Management Card -->
            <div class="card hover-lift border-l-4" style="border-left-color: var(--color-primary);">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-12 h-12 rounded-lg bg-brand-light flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="card-title text-lg mb-2">Service Management ⚙️</h3>
                        <p class="text-sm text-secondary mb-4">
                            View, add, edit, and delete Cloud27 services displayed on your website.
                        </p>
                    </div>
                </div>
                <a href="<?= BASE_PATH ?>/admin/services" class="btn btn-primary w-full">
                    Manage Services →
                </a>
            </div>

            <!-- Package Management Card -->
            <div class="card hover-lift border-l-4" style="border-left-color: var(--color-success);">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-12 h-12 rounded-lg bg-success-light flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" style="color: var(--color-success);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="card-title text-lg mb-2">Pricing Packages 📦</h3>
                        <p class="text-sm text-secondary mb-4">
                            Manage pricing packages and hosting plans for your clients.
                        </p>
                    </div>
                </div>
                <a href="<?= BASE_PATH ?>/admin/packages" class="btn btn-outline w-full" style="border-color: var(--color-success); color: var(--color-success);">
                    Manage Packages →
                </a>
            </div>

            <!-- User Management Card (Admin Only) -->
            <?php if ($isAdmin): ?>
            <div class="card hover-lift border-l-4" style="border-left-color: var(--color-error);">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-12 h-12 rounded-lg bg-error-light flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" style="color: var(--color-error);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="card-title text-lg mb-2">User Management 👥</h3>
                        <p class="text-sm text-secondary mb-4">
                            Create, edit, and manage user accounts. Assign admin or client roles.
                        </p>
                    </div>
                </div>
                <a href="<?= BASE_PATH ?>/admin/users" class="btn btn-outline w-full" style="border-color: var(--color-error); color: var(--color-error);">
                    Manage Users →
                </a>
            </div>
            <?php else: ?>
            <!-- Client Project Reports (Placeholder) -->
            <div class="card bg-secondary border-l-4" style="border-left-color: var(--color-text-tertiary); opacity: 0.7;">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-12 h-12 rounded-lg bg-tertiary flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="card-title text-lg mb-2">Project Reports 📊</h3>
                        <p class="text-sm text-tertiary mb-4">
                            View analytics and reports (Admin-only access).
                        </p>
                    </div>
                </div>
                <button class="btn btn-ghost w-full" disabled>
                    Restricted Access
                </button>
            </div>
            <?php endif; ?>

            <!-- Contact Submissions (Coming Soon) -->
            <div class="card bg-secondary border-l-4" style="border-left-color: var(--color-info); opacity: 0.7;">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-12 h-12 rounded-lg bg-info-light flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" style="color: var(--color-info);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="card-title text-lg mb-2">Contact Submissions 📧</h3>
                        <p class="text-sm text-tertiary mb-4">
                            View inquiries sent through the contact form.
                        </p>
                    </div>
                </div>
                <button class="btn btn-ghost w-full" disabled>
                    Coming Soon...
                </button>
            </div>

        </div>
    </div>
</section>

<style>
/* Dashboard Specific Styles */
.hover-lift {
    transition: all var(--transition-base);
}

.hover-lift:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.w-6 { width: 1.5rem; }
.h-6 { height: 1.5rem; }
.w-12 { width: 3rem; }
.h-12 { height: 3rem; }
</style>

<?php 
include VIEW_PATH . '_partials/Footer.php';
?>