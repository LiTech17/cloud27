<?php 
// Note: This file uses server-side PHP variables ($pageTitle, BASE_PATH, $_SESSION) 

// Prepare PHP variables for the navigation logic
$isLoggedIn = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === true;
$isAdmin = $_SESSION['is_admin'] ?? false;
$dashboardPath = $isAdmin ? BASE_PATH . '/admin/dashboard' : BASE_PATH . '/client/dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Cloud27.co.za | Modern Cloud Solutions' ?></title>

    <!-- Load Custom Global Styles (Mobile First + Dark Mode) -->
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/styles.css">

    <style>
        .site-logo {
    height: 40px;
    width: auto;
    display: block;
}

@media (min-width: 768px) {
    .site-logo {
        height: 48px;
    }
}

    </style>
    
    <!-- Optional: Keep Tailwind for utility classes if needed -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
</head>
<body>
    <!-- Navbar Component -->
    <header class="navbar">
        <div class="container">
            <div class="flex items-center justify-between py-4">
                <!-- Logo -->
                <a href="<?= BASE_PATH ?>/" class="navbar-brand flex items-center gap-2">
                    <img src="<?= BASE_PATH ?>/assets/img/logo.png" alt="Cloud27 Logo" class="site-logo">
                    <span class="text-gradient font-bold text-xl">Cloud27</span>
                </a>


                <!-- Desktop Navigation -->
                <nav class="navbar-menu">
                    <a href="<?= BASE_PATH ?>/about" class="navbar-link">About</a>
                    <a href="<?= BASE_PATH ?>/services" class="navbar-link">Services</a>
                    <a href="<?= BASE_PATH ?>/contact" class="navbar-link">Contact</a>
                    
                    <?php if ($isLoggedIn): ?>
                        <a href="<?= $dashboardPath ?>" class="navbar-link active">
                            Dashboard
                        </a>
                        <a href="<?= BASE_PATH ?>/logout" class="btn btn-ghost btn-sm">
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="<?= BASE_PATH ?>/login" class="btn btn-primary btn-sm">
                            Login
                        </a>
                    <?php endif; ?>

                    <!-- Dark Mode Toggle -->
                    <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle dark mode">
                        <svg class="theme-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </nav>

                <!-- Mobile Menu Toggle -->
                <button class="navbar-toggle" id="menu-toggle" aria-label="Toggle menu" aria-expanded="false">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div class="mobile-menu menu-hidden" id="mobile-nav">
            <div class="mobile-menu-container">
                <a href="<?= BASE_PATH ?>/about" class="mobile-menu-item">About</a>
                <a href="<?= BASE_PATH ?>/services" class="mobile-menu-item">Services</a>
                <a href="<?= BASE_PATH ?>/contact" class="mobile-menu-item">Contact</a>

                <?php if ($isLoggedIn): ?>
                    <a href="<?= $dashboardPath ?>" class="mobile-menu-item active">
                        Dashboard
                    </a>
                    <a href="<?= BASE_PATH ?>/logout" class="mobile-menu-item text-error">
                        Logout
                    </a>
                <?php else: ?>
                    <div class="mt-4 pt-4 border-t">
                        <a href="<?= BASE_PATH ?>/login" class="btn btn-primary w-full">
                            Login
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Mobile Dark Mode Toggle -->
                <div class="mt-4 pt-4 border-t flex items-center justify-between">
                    <span class="text-secondary">Dark Mode</span>
                    <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle dark mode">
                        <svg class="theme-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main class="content">
        <div class="container py-6 md:py-8">

    <script>
        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', () => {
            const toggleButton = document.getElementById('menu-toggle');
            const mobileMenu = document.getElementById('mobile-nav');

            if (toggleButton && mobileMenu) {
                toggleButton.addEventListener('click', () => {
                    const isMenuOpen = mobileMenu.classList.contains('menu-open');
                    
                    if (isMenuOpen) {
                        // Close the menu
                        mobileMenu.classList.remove('menu-open');
                        mobileMenu.classList.add('menu-hidden');
                        toggleButton.classList.remove('active');
                        toggleButton.setAttribute('aria-expanded', 'false');
                    } else {
                        // Open the menu
                        mobileMenu.classList.remove('menu-hidden');
                        mobileMenu.classList.add('menu-open');
                        toggleButton.classList.add('active');
                        toggleButton.setAttribute('aria-expanded', 'true');
                    }
                });
            }
        });

        // Dark Mode Toggle
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            
            // Save preference to localStorage
            localStorage.setItem('theme', newTheme);
        }

        // Initialize theme from localStorage or system preference
        (function initTheme() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (savedTheme) {
                document.documentElement.setAttribute('data-theme', savedTheme);
            } else if (prefersDark) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>