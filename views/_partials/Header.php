<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Cloud27.co.za | Modern Cloud Solutions' ?></title>

    <style>
        /* CSS Variables */
        :root {
            --primary-color: #007bff; /* Blue */
            --secondary-color: #6c757d; /* Gray */
            --background-color: #f8f9fa;
            --text-color: #333;
            --font-family: 'Arial', sans-serif;
        }

        /* Base & Reset */
        body {
            font-family: var(--font-family);
            margin: 0;
            padding: 0;
            color: var(--text-color);
            background-color: #fff;
            line-height: 1.6;
        }

        /* Layout Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Header & Navigation */
        header {
            background: #343a40;
            color: white;
            padding: 10px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header h1 a {
            color: white;
            text-decoration: none;
            font-size: 1.5em;
        }
        .nav-links {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: block;
            transition: background-color 0.2s;
        }
        .nav-links a:hover {
            background-color: #495057;
        }

        /* Main Content Area */
        .content {
            padding: 40px 0;
            min-height: 70vh; /* Ensures the footer stays down */
        }

        /* Utility/Button Styling */
        .btn-primary {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: opacity 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            opacity: 0.9;
        }

        /* Mobile Specifics (Hidden on Desktop) */
        .mobile-menu-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 1.5em;
            cursor: pointer;
            display: block; /* Default visible on small screens */
        }
        /* Mobile Navigation Overlay/Menu (Hidden by default, shown by JS in Phase 2) */
        @media (max-width: 767px) {
            .nav-links {
                display: none; /* Hidden on mobile until JS toggles */
                flex-direction: column;
                position: absolute;
                top: 60px; /* Below header */
                left: 0;
                right: 0;
                background-color: #343a40;
                z-index: 1000;
                padding: 10px 0;
            }
            .nav-links.active {
                display: flex;
            }
            .nav-links a {
                padding: 15px 25px;
                border-top: 1px solid #495057;
            }
        }

        /* Desktop Specifics (Navigation visible) */
        @media (min-width: 768px) {
            .nav-links {
                display: flex !important; /* Always show navigation on desktop */
            }
            .mobile-menu-toggle {
                display: none; /* Hide toggle button on desktop */
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <h1><a href="/">Cloud27</a></h1>
            <nav>
                <ul class="nav-links" id="main-nav">
                    <li><a href="/about">About</a></li>
                    <li><a href="/services">Services</a></li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </nav>
            <button class="mobile-menu-toggle" id="menu-toggle" aria-label="Toggle navigation">☰</button>
        </div>
    </header>
    <main class="content">
        <div class="container">