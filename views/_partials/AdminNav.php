<?php
// views/_partials/AdminNav.php

// Ensure session variables are set
$isAdmin = $_SESSION['is_admin'] ?? false;
$username = $_SESSION['username'] ?? 'User';

// Define navigation links based on role
if ($isAdmin) {
    $navLinks = [
        'Dashboard' => BASE_PATH . '/admin/dashboard',
        'Services' => BASE_PATH . '/admin/services',
        'Packages' => BASE_PATH . '/admin/packages',
        'Users' => BASE_PATH . '/admin/users',
        'About' => BASE_PATH . '/admin/about',
    ];
    $navTitle = 'Admin Panel';
    $navIcon = '🔐';
} else {
    $navLinks = [
        'Dashboard' => BASE_PATH . '/client/dashboard',
        'Projects' => '#',
        'Support' => '#',
    ];
    $navTitle = 'Client Portal';
    $navIcon = '👤';
}
?>

<!-- Admin/Client Navigation Bar -->
<nav class="admin-navbar">
    <div class="container">
        <div class="admin-navbar-content">
            <!-- Brand/Title -->
            <div class="admin-brand">
                <span class="admin-icon"><?= $navIcon ?></span>
                <span class="admin-title"><?= htmlspecialchars($navTitle) ?></span>
            </div>

            <!-- Desktop Navigation Links -->
            <ul class="admin-nav-links hidden-mobile">
                <?php foreach ($navLinks as $text => $path): ?>
                    <li>
                        <a href="<?= htmlspecialchars($path) ?>" 
                           class="admin-nav-link <?= (strpos($_SERVER['REQUEST_URI'], strtolower($text)) !== false) ? 'active' : '' ?>">
                            <?= htmlspecialchars($text) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- User Info & Actions -->
            <div class="admin-user-section hidden-mobile">
                <div class="admin-user-info">
                    <svg class="user-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="user-name"><?= htmlspecialchars($username) ?></span>
                </div>
                <a href="<?= BASE_PATH ?>/logout" class="btn btn-ghost btn-sm logout-btn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="admin-menu-toggle show-mobile" id="admin-menu-toggle" aria-label="Toggle menu">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div class="admin-mobile-menu menu-hidden" id="admin-mobile-menu">
            <div class="admin-mobile-links">
                <?php foreach ($navLinks as $text => $path): ?>
                    <a href="<?= htmlspecialchars($path) ?>" 
                       class="admin-mobile-link <?= (strpos($_SERVER['REQUEST_URI'], strtolower($text)) !== false) ? 'active' : '' ?>">
                        <?= htmlspecialchars($text) ?>
                    </a>
                <?php endforeach; ?>
            </div>
            
            <!-- Mobile User Section -->
            <div class="admin-mobile-user">
                <div class="flex items-center gap-2 mb-3 pb-3 border-b">
                    <svg class="w-5 h-5" style="color: var(--color-primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-semibold"><?= htmlspecialchars($username) ?></span>
                </div>
                <a href="<?= BASE_PATH ?>/logout" class="btn btn-error w-full">
                    Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
/* Admin Navbar Styles */
.admin-navbar {
    background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
    border-bottom: 3px solid var(--color-primary);
    box-shadow: var(--shadow-md);
    position: sticky;
    top: 0;
    z-index: var(--z-sticky);
    margin-bottom: var(--space-6);
}

.admin-navbar-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--space-3) 0;
    gap: var(--space-4);
}

/* Brand Section */
.admin-brand {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    color: white;
}

.admin-icon {
    font-size: 1.5rem;
}

.admin-title {
    font-size: var(--text-lg);
    font-weight: 700;
    letter-spacing: -0.01em;
}

/* Desktop Navigation Links */
.admin-nav-links {
    display: none;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: var(--space-2);
}

@media (min-width: 768px) {
    .admin-nav-links {
        display: flex;
    }
}

.admin-nav-link {
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
    padding: var(--space-2) var(--space-4);
    border-radius: var(--radius-md);
    font-weight: 500;
    font-size: var(--text-sm);
    transition: all var(--transition-fast);
    white-space: nowrap;
}

.admin-nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1);
    color: white;
}

.admin-nav-link.active {
    background-color: var(--color-primary);
    color: white;
}

/* User Section */
.admin-user-section {
    display: none;
    align-items: center;
    gap: var(--space-4);
}

@media (min-width: 768px) {
    .admin-user-section {
        display: flex;
    }
}

.admin-user-info {
    display: flex;
    align-items: center;
    gap: var(--space-2);
    color: rgba(255, 255, 255, 0.9);
    font-size: var(--text-sm);
}

.user-icon {
    width: 20px;
    height: 20px;
    color: var(--color-primary);
}

.logout-btn {
    color: #f39c12 !important;
    border-color: #f39c12 !important;
}

.logout-btn:hover {
    background-color: #f39c12 !important;
    color: #2c3e50 !important;
}

/* Mobile Menu Toggle */
.admin-menu-toggle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background-color: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: all var(--transition-fast);
}

@media (min-width: 768px) {
    .admin-menu-toggle {
        display: none;
    }
}

.admin-menu-toggle:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

.admin-menu-toggle .hamburger {
    position: relative;
    width: 20px;
    height: 14px;
}

.admin-menu-toggle .hamburger span {
    position: absolute;
    width: 100%;
    height: 2px;
    background-color: white;
    border-radius: 2px;
    transition: all var(--transition-base);
}

.admin-menu-toggle .hamburger span:nth-child(1) {
    top: 0;
}

.admin-menu-toggle .hamburger span:nth-child(2) {
    top: 6px;
}

.admin-menu-toggle .hamburger span:nth-child(3) {
    bottom: 0;
}

.admin-menu-toggle.active .hamburger span:nth-child(1) {
    top: 6px;
    transform: rotate(45deg);
}

.admin-menu-toggle.active .hamburger span:nth-child(2) {
    opacity: 0;
}

.admin-menu-toggle.active .hamburger span:nth-child(3) {
    bottom: 6px;
    transform: rotate(-45deg);
}

/* Mobile Menu */
.admin-mobile-menu {
    background-color: rgba(44, 62, 80, 0.98);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    padding: var(--space-4) 0;
}

@media (min-width: 768px) {
    .admin-mobile-menu {
        display: none !important;
    }
}

.admin-mobile-links {
    display: flex;
    flex-direction: column;
    gap: var(--space-1);
    margin-bottom: var(--space-4);
}

.admin-mobile-link {
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
    padding: var(--space-3) var(--space-4);
    border-radius: var(--radius-md);
    font-weight: 500;
    transition: all var(--transition-fast);
    display: block;
}

.admin-mobile-link:hover,
.admin-mobile-link.active {
    background-color: var(--color-primary);
    color: white;
}

.admin-mobile-user {
    padding: var(--space-4);
    padding-top: var(--space-4);
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

/* Utility Classes */
.hidden-mobile {
    display: none;
}

@media (min-width: 768px) {
    .hidden-mobile {
        display: flex;
    }
}

.show-mobile {
    display: flex;
}

@media (min-width: 768px) {
    .show-mobile {
        display: none;
    }
}

.w-4 { width: 1rem; }
.h-4 { height: 1rem; }
.w-5 { width: 1.25rem; }
.h-5 { height: 1.25rem; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('admin-menu-toggle');
    const menu = document.getElementById('admin-mobile-menu');
    
    if (toggle && menu) {
        toggle.addEventListener('click', function() {
            const isOpen = menu.classList.contains('menu-open');
            
            if (isOpen) {
                menu.classList.remove('menu-open');
                menu.classList.add('menu-hidden');
                toggle.classList.remove('active');
            } else {
                menu.classList.remove('menu-hidden');
                menu.classList.add('menu-open');
                toggle.classList.add('active');
            }
        });
    }
});
</script>