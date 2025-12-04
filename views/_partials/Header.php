<?php 
// views/partials/header.php

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

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome for additional icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Load Enhanced Oceanic Styles -->
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/styles.css">

    <style>
        /* Ensure logo sizing is consistent */
        .site-logo {
            height: 40px;
            width: auto;
            display: block;
            transition: transform 0.3s ease;
        }

        @media (min-width: 768px) {
            .site-logo {
                height: 48px;
            }
        }
        
        /* Enhanced Navbar with Oceanic Theme */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        #main-navbar {
            background: rgba(10, 36, 99, 0.95);
            backdrop-filter: blur(15px);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            padding: 0.75rem 0;
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.15);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        #main-navbar.scrolled {
            background: rgba(10, 36, 99, 0.98);
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
            padding: 0.5rem 0;
        }
        
        [data-bs-theme="dark"] #main-navbar,
        [data-theme="dark"] #main-navbar {
            background: rgba(10, 25, 47, 0.95);
        }
        
        [data-bs-theme="dark"] #main-navbar.scrolled,
        [data-theme="dark"] #main-navbar.scrolled {
            background: rgba(10, 25, 47, 0.98);
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.6rem;
            color: white !important;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }
        
        .navbar-brand::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--wave-light), transparent);
            transition: left 0.6s;
        }
        
        .navbar-brand:hover::after {
            left: 100%;
        }
        
        .navbar-brand:hover {
            transform: translateY(-2px);
        }
        
        .brand-text {
            background: linear-gradient(135deg, #ffffff 0%, var(--wave-light) 50%, var(--ocean-light) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 900;
            letter-spacing: -0.5px;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 600;
            padding: 0.5rem 1rem !important;
            transition: all 0.3s ease;
            position: relative;
            margin: 0 0.25rem;
            border-radius: 8px;
        }
        
        .nav-link:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            color: white !important;
            font-weight: 700;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--ocean-teal) 0%, var(--ocean-blue) 100%);
            border: none;
            padding: 0.7rem 1.8rem;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 8px 25px rgba(10, 36, 99, 0.4);
            color: white !important;
            position: relative;
            overflow: hidden;
        }
        
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s;
        }
        
        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 30px rgba(10, 36, 99, 0.6);
        }
        
        .theme-toggler {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            backdrop-filter: blur(5px);
        }
        
        .theme-toggler i {
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }
        
        .theme-toggler:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: rotate(15deg) scale(1.1);
            border-color: rgba(255, 255, 255, 0.3);
        }
        
        /* Mobile menu styling */
        .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .navbar-toggler:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(90deg);
        }
        
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.9)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            transition: all 0.3s ease;
        }
        
        /* User dropdown styling */
        .user-dropdown {
            position: relative;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--ocean-teal) 0%, var(--ocean-blue) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .user-avatar:hover {
            transform: scale(1.1);
            border-color: var(--wave-light);
            box-shadow: 0 0 15px rgba(139, 92, 246, 0.4);
        }
        
        .dropdown-menu {
            background: rgba(10, 36, 99, 0.95);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 0.5rem;
            animation: dropdownFade 0.3s ease;
            margin-top: 10px;
        }
        
        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .dropdown-item {
            color: rgba(255, 255, 255, 0.9) !important;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white !important;
            transform: translateX(5px);
        }
        
        /* Mobile menu enhancements */
        @media (max-width: 991.98px) {
            #navbarNav {
                background: rgba(10, 36, 99, 0.98);
                backdrop-filter: blur(20px);
                border-radius: 15px;
                padding: 1rem;
                margin-top: 1rem;
                border: 1px solid rgba(255, 255, 255, 0.1);
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            }
            
            .nav-item {
                margin: 0.25rem 0;
            }
            
            .nav-link {
                padding: 0.75rem 1rem !important;
                border-radius: 10px;
            }
            
            .theme-toggler {
                margin-top: 1rem;
            }
        }
        
        /* Floating animation for logo */
        @keyframes logoFloat {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
            }
            50% {
                transform: translateY(-5px) rotate(3deg);
            }
        }
        
        .site-logo {
            animation: logoFloat 6s ease-in-out infinite;
        }
    </style>
</head>
<body>
    <!-- Loading Animation -->
    <div class="loading-wave" id="loadingWave">
        <div class="wave-dot"></div>
        <div class="wave-dot"></div>
        <div class="wave-dot"></div>
    </div>
    
    <!-- Scroll Progress Indicator -->
    <div class="scroll-progress" id="scrollProgress"></div>
    
    <!-- Ocean Waves Background -->
    <div class="ocean-waves">
        <div class="wave-layer wave-layer-1"></div>
        <div class="wave-layer wave-layer-2"></div>
        <div class="wave-layer wave-layer-3"></div>
    </div>
    
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="main-navbar">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_PATH ?>/">
                <img src="<?= BASE_PATH ?>/assets/img/logo.png" alt="Cloud27 Logo" class="site-logo">
                <span class="brand-text">Cloud27</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link <?= ($_SERVER['REQUEST_URI'] == BASE_PATH . '/about' ? 'active' : '') ?>" 
                           href="<?= BASE_PATH ?>/about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($_SERVER['REQUEST_URI'] == BASE_PATH . '/services' ? 'active' : '') ?>" 
                           href="<?= BASE_PATH ?>/services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= ($_SERVER['REQUEST_URI'] == BASE_PATH . '/contact' ? 'active' : '') ?>" 
                           href="<?= BASE_PATH ?>/contact">Contact</a>
                    </li>
                    
                    <?php if ($isLoggedIn): ?>
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown ms-2 user-dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" 
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar me-2">
                                    <?= substr($_SESSION['user_name'] ?? 'U', 0, 1) ?>
                                </div>
                                <span><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="<?= $dashboardPath ?>">
                                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_PATH ?>/profile">
                                        <i class="bi bi-person-circle me-2"></i> Profile
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item" href="<?= BASE_PATH ?>/logout">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-2">
                            <a href="<?= BASE_PATH ?>/login" class="btn btn-login">
                                <i class="bi bi-box-arrow-in-right me-2"></i> Login
                            </a>
                        </li>
                        
                    <?php endif; ?>

                    <!-- Dark Mode Toggle -->
                    <li class="nav-item ms-2">
                        <button class="theme-toggler" onclick="toggleTheme()" aria-label="Toggle dark mode">
                            <i class="bi bi-moon-stars"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Add spacing for fixed navbar -->
    <div style="height: 70px;" id="navbarSpacer"></div>

    <!-- Main Content -->
    <main>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Navbar scroll effect with enhanced oceanic theme
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('main-navbar');
            const navbarSpacer = document.getElementById('navbarSpacer');
            const scrolled = window.scrollY;
            
            if (scrolled > 50) {
                navbar.classList.add('scrolled');
                navbarSpacer.style.height = '60px';
            } else {
                navbar.classList.remove('scrolled');
                navbarSpacer.style.height = '70px';
            }
            
            // Parallax effect for ocean waves
            const waves = document.querySelectorAll('.wave-layer');
            waves.forEach((wave, index) => {
                const speed = 0.3 + (index * 0.15);
                wave.style.transform = `translateX(${scrolled * speed * 0.05}px)`;
            });
        });

        // Dark Mode Toggle - Enhanced with oceanic transitions
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme') || html.getAttribute('data-bs-theme') || 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            // Smooth transition
            html.style.transition = 'all 0.5s ease';
            
            // Set both attributes for compatibility
            html.setAttribute('data-theme', newTheme);
            html.setAttribute('data-bs-theme', newTheme);
            
            // Update icon with animation
            const icons = document.querySelectorAll('.theme-toggler i');
            icons.forEach(icon => {
                icon.style.transition = 'all 0.5s ease';
                if (newTheme === 'dark') {
                    icon.classList.remove('bi-moon-stars');
                    icon.classList.add('bi-sun');
                    icon.style.transform = 'rotate(180deg)';
                    setTimeout(() => {
                        icon.style.transform = 'rotate(0deg)';
                    }, 100);
                } else {
                    icon.classList.remove('bi-sun');
                    icon.classList.add('bi-moon-stars');
                    icon.style.transform = 'rotate(-180deg)';
                    setTimeout(() => {
                        icon.style.transform = 'rotate(0deg)';
                    }, 100);
                }
            });
            
            // Update navbar background for theme
            const navbar = document.getElementById('main-navbar');
            if (newTheme === 'dark') {
                navbar.style.background = 'rgba(10, 25, 47, 0.95)';
            } else {
                navbar.style.background = 'rgba(10, 36, 99, 0.95)';
            }
            
            // Save preference to localStorage
            localStorage.setItem('theme', newTheme);
            
            // Reset transition after complete
            setTimeout(() => {
                html.style.transition = '';
            }, 500);
        }

        // Initialize theme from localStorage or system preference
        (function initTheme() {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            let theme = 'light';
            if (savedTheme) {
                theme = savedTheme;
            } else if (prefersDark) {
                theme = 'dark';
            }
            
            // Set both attributes for compatibility
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.setAttribute('data-bs-theme', theme);
            
            // Update navbar background based on theme
            const navbar = document.getElementById('main-navbar');
            if (theme === 'dark') {
                navbar.style.background = 'rgba(10, 25, 47, 0.95)';
            }
            
            // Update icon based on theme
            const icons = document.querySelectorAll('.theme-toggler i');
            icons.forEach(icon => {
                if (theme === 'dark') {
                    icon.classList.remove('bi-moon-stars');
                    icon.classList.add('bi-sun');
                } else {
                    icon.classList.remove('bi-sun');
                    icon.classList.add('bi-moon-stars');
                }
            });
        })();

        // Loading animation
        const loadingWave = document.getElementById('loadingWave');
        setTimeout(() => {
            loadingWave.style.opacity = '0';
            setTimeout(() => {
                loadingWave.style.display = 'none';
            }, 500);
        }, 800);
        
        // Scroll progress indicator
        const scrollProgress = document.getElementById('scrollProgress');
        window.addEventListener('scroll', () => {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            scrollProgress.style.width = scrolled + '%';
            
            // Add wave animation to progress bar
            if (scrolled > 0 && scrolled < 100) {
                scrollProgress.style.animation = 'waveFlow 2s linear infinite';
            } else {
                scrollProgress.style.animation = 'none';
            }
        });
        
        // Mobile menu close on click
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                const navbarToggler = document.querySelector('.navbar-toggler');
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse.classList.contains('show')) {
                    navbarToggler.click();
                }
            });
        });
        
        // Add wave effect to active navigation items
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('mouseenter', function() {
                if (!this.classList.contains('active')) {
                    this.style.background = 'linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent)';
                }
            });
            
            link.addEventListener('mouseleave', function() {
                if (!this.classList.contains('active')) {
                    this.style.background = '';
                }
            });
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Initialize wave animation on page load
        window.addEventListener('load', () => {
            const waves = document.querySelectorAll('.wave-layer');
            waves.forEach((wave, index) => {
                wave.style.animationDelay = `${index * -5}s`;
            });
        });
    </script>