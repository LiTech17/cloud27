<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advanced CSS Design System Showcase</title>
    <!-- Using Feather Icons for the showcase -->
    <script src="https://unpkg.com/feather-icons"></script>
    <style>
/* *** START OF USER-PROVIDED CSS ***
    NOTE: Added missing --color-bg-primary-rgb for backdrop-filter support in navbar.
*/

/* ========================================================================
    ENHANCED GLOBAL STYLES - Mobile First, Dark Mode, Fully Responsive
    ======================================================================== */

/* ------------------------------------------------------------------------
    CSS CUSTOM PROPERTIES - Theme System
    ------------------------------------------------------------------------ */
:root {
    /* Typography */
    --font-sans: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 
                'Helvetica Neue', Arial, sans-serif;
    --font-mono: 'Fira Code', 'Courier New', monospace;
    
    /* Light Mode Colors */
    --color-text-primary: #1a202c;
    --color-text-secondary: #4a5568;
    --color-text-tertiary: #718096;
    --color-text-inverse: #ffffff;
    --color-text-muted: #a0aec0;
    
    --color-bg-primary: #ffffff;
    --color-bg-secondary: #f7fafc;
    --color-bg-tertiary: #edf2f7;
    --color-bg-overlay: rgba(0, 0, 0, 0.5);
    
    /* ADDED FOR NAVBAR BLUR EFFECT */
    --color-bg-primary-rgb: 255, 255, 255; 
    
    --color-border: #e2e8f0;
    --color-border-light: #f1f5f9;
    --color-border-dark: #cbd5e0;
    
    /* Brand Colors */
    --color-primary: #6366f1;
    --color-primary-hover: #4f46e5;
    --color-primary-light: #e0e7ff;
    --color-secondary: #ec4899;
    --color-accent: #06b6d4;
    
    /* Semantic Colors */
    --color-success: #10b981;
    --color-success-light: #d1fae5;
    --color-warning: #f59e0b;
    --color-warning-light: #fef3c7;
    --color-error: #ef4444;
    --color-error-light: #fee2e2;
    --color-info: #3b82f6;
    --color-info-light: #dbeafe;
    
    /* Spacing Scale (Mobile First) */
    --space-1: 0.25rem;    /* 4px */
    --space-2: 0.5rem;      /* 8px */
    --space-3: 0.75rem;    /* 12px */
    --space-4: 1rem;        /* 16px */
    --space-5: 1.25rem;    /* 20px */
    --space-6: 1.5rem;      /* 24px */
    --space-8: 2rem;        /* 32px */
    --space-10: 2.5rem;    /* 40px */
    --space-12: 3rem;      /* 48px */
    --space-16: 4rem;      /* 64px */
    
    /* Typography Scale */
    --text-xs: 0.75rem;      /* 12px */
    --text-sm: 0.875rem;       /* 14px */
    --text-base: 1rem;       /* 16px */
    --text-lg: 1.125rem;     /* 18px */
    --text-xl: 1.25rem;       /* 20px */
    --text-2xl: 1.5rem;       /* 24px */
    --text-3xl: 1.875rem;      /* 30px */
    --text-4xl: 2.25rem;      /* 36px */
    --text-5xl: 3rem;         /* 48px */
    
    /* Border Radius */
    --radius-sm: 0.375rem;   /* 6px */
    --radius-md: 0.5rem;      /* 8px */
    --radius-lg: 0.75rem;      /* 12px */
    --radius-xl: 1rem;        /* 16px */
    --radius-2xl: 1.5rem;      /* 24px */
    --radius-full: 9999px;
    
    /* Shadows */
    --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    
    /* Transitions */
    --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
    --transition-base: 250ms cubic-bezier(0.4, 0, 0.2, 1);
    --transition-slow: 350ms cubic-bezier(0.4, 0, 0.2, 1);
    --transition-slower: 500ms cubic-bezier(0.4, 0, 0.2, 1);
    
    /* Z-Index Scale */
    --z-base: 1;
    --z-dropdown: 100;
    --z-sticky: 200;
    --z-fixed: 300;
    --z-modal-backdrop: 400;
    --z-modal: 500;
    --z-popover: 600;
    --z-tooltip: 700;
}

/* ------------------------------------------------------------------------
    DARK MODE - Automatic & Manual
    ------------------------------------------------------------------------ */
@media (prefers-color-scheme: dark) {
    :root:not([data-theme="light"]) {
        --color-text-primary: #f7fafc;
        --color-text-secondary: #e2e8f0;
        --color-text-tertiary: #cbd5e0;
        --color-text-inverse: #1a202c;
        --color-text-muted: #718096;
        
        --color-bg-primary: #1a202c;
        --color-bg-secondary: #2d3748;
        --color-bg-tertiary: #4a5568;
        --color-bg-overlay: rgba(0, 0, 0, 0.75);
        
        /* ADDED FOR NAVBAR BLUR EFFECT */
        --color-bg-primary-rgb: 26, 32, 44;
        
        --color-border: #4a5568;
        --color-border-light: #2d3748;
        --color-border-dark: #718096;
        
        --color-primary-light: #312e81;
        --color-success-light: #064e3b;
        --color-warning-light: #78350f;
        --color-error-light: #7f1d1d;
        --color-info-light: #1e3a8a;
        
        /* Adjust shadows for dark mode */
        --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
        --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.4), 0 1px 2px -1px rgba(0, 0, 0, 0.4);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -2px rgba(0, 0, 0, 0.4);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -4px rgba(0, 0, 0, 0.4);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 8px 10px -6px rgba(0, 0, 0, 0.4);
        --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
}

/* Manual dark mode override */
[data-theme="dark"] {
    --color-text-primary: #f7fafc;
    --color-text-secondary: #e2e8f0;
    --color-text-tertiary: #cbd5e0;
    --color-text-inverse: #1a202c;
    --color-text-muted: #718096;
    
    --color-bg-primary: #1a202c;
    --color-bg-secondary: #2d3748;
    --color-bg-tertiary: #4a5568;
    --color-bg-overlay: rgba(0, 0, 0, 0.75);

    /* ADDED FOR NAVBAR BLUR EFFECT */
    --color-bg-primary-rgb: 26, 32, 44;
    
    --color-border: #4a5568;
    --color-border-light: #2d3748;
    --color-border-dark: #718096;
    
    --color-primary-light: #312e81;
    --color-success-light: #064e3b;
    --color-warning-light: #78350f;
    --color-error-light: #7f1d1d;
    --color-info-light: #1e3a8a;
    
    --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
    --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.4), 0 1px 2px -1px rgba(0, 0, 0, 0.4);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.4), 0 2px 4px -2px rgba(0, 0, 0, 0.4);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.4), 0 4px 6px -4px rgba(0, 0, 0, 0.4);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 0 8px 10px -6px rgba(0, 0, 0, 0.4);
    --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

/* Manual light mode override */
[data-theme="light"] {
    --color-text-primary: #1a202c;
    --color-text-secondary: #4a5568;
    --color-text-tertiary: #718096;
    --color-text-inverse: #ffffff;
    --color-text-muted: #a0aec0;
    
    --color-bg-primary: #ffffff;
    --color-bg-secondary: #f7fafc;
    --color-bg-tertiary: #edf2f7;
    --color-bg-overlay: rgba(0, 0, 0, 0.5);

    /* ADDED FOR NAVBAR BLUR EFFECT */
    --color-bg-primary-rgb: 255, 255, 255; 
    
    --color-border: #e2e8f0;
    --color-border-light: #f1f5f9;
    --color-border-dark: #cbd5e0;
    
    --color-primary-light: #e0e7ff;
    --color-success-light: #d1fae5;
    --color-warning-light: #fef3c7;
    --color-error-light: #fee2e2;
    --color-info-light: #dbeafe;
}

/* ------------------------------------------------------------------------
    GLOBAL RESETS & BASE STYLES
    ------------------------------------------------------------------------ */
*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

html {
    font-size: 16px;
    -webkit-text-size-adjust: 100%;
    -webkit-tap-highlight-color: transparent;
    scroll-behavior: smooth;
}

body {
    font-family: var(--font-sans);
    font-size: var(--text-base);
    line-height: 1.6;
    color: var(--color-text-primary);
    background-color: var(--color-bg-primary);
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    min-height: 100vh;
    overflow-x: hidden;
    transition: background-color var(--transition-base), color var(--transition-base);
}

/* Responsive font scaling */
@media (min-width: 768px) {
    html {
        font-size: 17px;
    }
}

@media (min-width: 1024px) {
    html {
        font-size: 18px;
    }
}

/* ------------------------------------------------------------------------
    ENHANCED MOBILE MENU with proper transitions
    ------------------------------------------------------------------------ */
.mobile-menu {
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transform: translateY(-10px);
    transition: max-height var(--transition-slower) cubic-bezier(0.4, 0, 0.2, 1),
                opacity var(--transition-base) cubic-bezier(0.4, 0, 0.2, 1),
                transform var(--transition-base) cubic-bezier(0.4, 0, 0.2, 1);
}

.mobile-menu.menu-open {
    max-height: 100vh;
    opacity: 1;
    transform: translateY(0);
    transition: max-height var(--transition-slower) cubic-bezier(0.4, 0, 1, 1),
                opacity var(--transition-slow) cubic-bezier(0.4, 0, 1, 1),
                transform var(--transition-slow) cubic-bezier(0.4, 0, 1, 1);
}

/* Fallback for older approach */
.menu-hidden {
    max-height: 0;
    overflow: hidden;
    opacity: 0;
    transform: translateY(-10px);
    transition: max-height var(--transition-slower) ease-out,
                opacity var(--transition-base) ease-out,
                transform var(--transition-base) ease-out;
}

.menu-open {
    max-height: 1000px;
    opacity: 1;
    transform: translateY(0);
    transition: max-height var(--transition-slower) ease-in,
                opacity var(--transition-slow) ease-in,
                transform var(--transition-slow) ease-in;
}

/* Mobile menu items */
.mobile-menu-item {
    padding: var(--space-3) var(--space-4);
    color: var(--color-text-secondary);
    text-decoration: none;
    display: block;
    border-radius: var(--radius-md);
    transition: all var(--transition-fast);
    font-weight: 500;
}

.mobile-menu-item:hover,
.mobile-menu-item:focus {
    background-color: var(--color-bg-secondary);
    color: var(--color-text-primary);
    transform: translateX(4px);
}

.mobile-menu-item:active {
    transform: scale(0.98) translateX(4px);
}

.mobile-menu-item.active {
    color: var(--color-primary);
    background-color: var(--color-primary-light);
    font-weight: 600;
}

/* Mobile menu container */
.mobile-menu-container {
    padding: var(--space-4);
    background-color: var(--color-bg-primary);
    border-top: 1px solid var(--color-border);
}

@media (min-width: 768px) {
    .mobile-menu-container {
        padding: var(--space-6);
    }
}

/* ------------------------------------------------------------------------
    TEXT COLOR UTILITIES - Dark Mode Aware
    ------------------------------------------------------------------------ */
.text-primary {
    color: var(--color-text-primary);
}

.text-secondary {
    color: var(--color-text-secondary);
}

.text-tertiary {
    color: var(--color-text-tertiary);
}

.text-inverse {
    color: var(--color-text-inverse);
}

.text-muted {
    color: var(--color-text-muted);
}

.text-brand {
    color: var(--color-primary);
}

.text-success {
    color: var(--color-success);
}

.text-warning {
    color: var(--color-warning);
}

.text-error {
    color: var(--color-error);
}

.text-info {
    color: var(--color-info);
}

/* ------------------------------------------------------------------------
    BACKGROUND COLOR UTILITIES - Dark Mode Aware
    ------------------------------------------------------------------------ */
.bg-primary {
    background-color: var(--color-bg-primary);
}

.bg-secondary {
    background-color: var(--color-bg-secondary);
}

.bg-tertiary {
    background-color: var(--color-bg-tertiary);
}

.bg-brand {
    background-color: var(--color-primary);
    color: var(--color-text-inverse);
}

.bg-brand-light {
    background-color: var(--color-primary-light);
    color: var(--color-primary);
}

.bg-success {
    background-color: var(--color-success);
    color: var(--color-text-inverse);
}

.bg-success-light {
    background-color: var(--color-success-light);
    color: var(--color-success);
}

.bg-warning {
    background-color: var(--color-warning);
    color: var(--color-text-inverse);
}

.bg-warning-light {
    background-color: var(--color-warning-light);
    color: var(--color-warning);
}

.bg-error {
    background-color: var(--color-error);
    color: var(--color-text-inverse);
}

.bg-error-light {
    background-color: var(--color-error-light);
    color: var(--color-error);
}

.bg-info {
    background-color: var(--color-info);
    color: var(--color-text-inverse);
}

.bg-info-light {
    background-color: var(--color-info-light);
    color: var(--color-info);
}

/* ------------------------------------------------------------------------
    BORDER UTILITIES - Dark Mode Aware
    ------------------------------------------------------------------------ */
.border {
    border: 1px solid var(--color-border);
}

.border-light {
    border: 1px solid var(--color-border-light);
}

.border-dark {
    border: 1px solid var(--color-border-dark);
}

.border-brand {
    border: 1px solid var(--color-primary);
}

.border-t {
    border-top: 1px solid var(--color-border);
}

.border-b {
    border-bottom: 1px solid var(--color-border);
}

.border-l {
    border-left: 1px solid var(--color-border);
}

.border-r {
    border-right: 1px solid var(--color-border);
}

/* ------------------------------------------------------------------------
    RESPONSIVE TYPOGRAPHY
    ------------------------------------------------------------------------ */
.text-xs { font-size: var(--text-xs); }
.text-sm { font-size: var(--text-sm); }
.text-base { font-size: var(--text-base); }
.text-lg { font-size: var(--text-lg); }
.text-xl { font-size: var(--text-xl); }
.text-2xl { font-size: var(--text-2xl); }
.text-3xl { font-size: var(--text-3xl); }
.text-4xl { font-size: var(--text-4xl); }
.text-5xl { font-size: var(--text-5xl); }

/* Responsive heading scales */
.heading-1 {
    font-size: var(--text-3xl);
    font-weight: 700;
    line-height: 1.2;
    color: var(--color-text-primary);
}

@media (min-width: 768px) {
    .heading-1 { font-size: var(--text-4xl); }
}

@media (min-width: 1024px) {
    .heading-1 { font-size: var(--text-5xl); }
}

.heading-2 {
    font-size: var(--text-2xl);
    font-weight: 700;
    line-height: 1.3;
    color: var(--color-text-primary);
}

@media (min-width: 768px) {
    .heading-2 { font-size: var(--text-3xl); }
}

@media (min-width: 1024px) {
    .heading-2 { font-size: var(--text-4xl); }
}

.heading-3 {
    font-size: var(--text-xl);
    font-weight: 600;
    line-height: 1.4;
    color: var(--color-text-primary);
}

@media (min-width: 768px) {
    .heading-3 { font-size: var(--text-2xl); }
}

@media (min-width: 1024px) {
    .heading-3 { font-size: var(--text-3xl); }
}

/* ------------------------------------------------------------------------
    RESPONSIVE SPACING UTILITIES
    ------------------------------------------------------------------------ */
/* Margin */
.m-0 { margin: 0; }
.m-1 { margin: var(--space-1); }
.m-2 { margin: var(--space-2); }
.m-3 { margin: var(--space-3); }
.m-4 { margin: var(--space-4); }
.m-5 { margin: var(--space-5); }
.m-6 { margin: var(--space-6); }
.m-8 { margin: var(--space-8); }

.mt-0 { margin-top: 0; }
.mt-2 { margin-top: var(--space-2); }
.mt-3 { margin-top: var(--space-3); }
.mt-4 { margin-top: var(--space-4); }
.mt-6 { margin-top: var(--space-6); }
.mt-8 { margin-top: var(--space-8); }

.mb-0 { margin-bottom: 0; }
.mb-2 { margin-bottom: var(--space-2); }
.mb-3 { margin-bottom: var(--space-3); }
.mb-4 { margin-bottom: var(--space-4); }
.mb-6 { margin-bottom: var(--space-6); }
.mb-8 { margin-bottom: var(--space-8); }

.mx-auto { margin-left: auto; margin-right: auto; }
.my-4 { margin-top: var(--space-4); margin-bottom: var(--space-4); }
.my-6 { margin-top: var(--space-6); margin-bottom: var(--space-6); }

/* Padding */
.p-0 { padding: 0; }
.p-2 { padding: var(--space-2); }
.p-3 { padding: var(--space-3); }
.p-4 { padding: var(--space-4); }
.p-5 { padding: var(--space-5); }
.p-6 { padding: var(--space-6); }
.p-8 { padding: var(--space-8); }

.px-2 { padding-left: var(--space-2); padding-right: var(--space-2); }
.px-3 { padding-left: var(--space-3); padding-right: var(--space-3); }
.px-4 { padding-left: var(--space-4); padding-right: var(--space-4); }
.px-6 { padding-left: var(--space-6); padding-right: var(--space-6); }

.py-2 { padding-top: var(--space-2); padding-bottom: var(--space-2); }
.py-3 { padding-top: var(--space-3); padding-bottom: var(--space-3); }
.py-4 { padding-top: var(--space-4); padding-bottom: var(--space-4); }
.py-6 { padding-top: var(--space-6); padding-bottom: var(--space-6); }

/* Responsive spacing modifiers */
@media (min-width: 768px) {
    .md\:p-6 { padding: var(--space-6); }
    .md\:p-8 { padding: var(--space-8); }
    .md\:px-8 { padding-left: var(--space-8); padding-right: var(--space-8); }
    .md\:py-8 { padding-top: var(--space-8); padding-bottom: var(--space-8); }
    .md\:mt-8 { margin-top: var(--space-8); }
    .md\:mb-8 { margin-bottom: var(--space-8); }
}

@media (min-width: 1024px) {
    .lg\:p-12 { padding: var(--space-12); }
    .lg\:px-12 { padding-left: var(--space-12); padding-right: var(--space-12); }
    .lg\:py-12 { padding-top: var(--space-12); padding-bottom: var(--space-12); }
}

/* ------------------------------------------------------------------------
    RESPONSIVE CONTAINER
    ------------------------------------------------------------------------ */
.container {
    width: 100%;
    margin-left: auto;
    margin-right: auto;
    padding-left: var(--space-4);
    padding-right: var(--space-4);
}

@media (min-width: 640px) {
    .container {
        max-width: 640px;
        padding-left: var(--space-6);
        padding-right: var(--space-6);
    }
}

@media (min-width: 768px) {
    .container {
        max-width: 768px;
    }
}

@media (min-width: 1024px) {
    .container {
        max-width: 1024px;
        padding-left: var(--space-8);
        padding-right: var(--space-8);
    }
}

@media (min-width: 1280px) {
    .container {
        max-width: 1280px;
    }
}

/* ------------------------------------------------------------------------
    RESPONSIVE DISPLAY UTILITIES
    ------------------------------------------------------------------------ */
.hidden { display: none; }
.block { display: block; }
.inline-block { display: inline-block; }
.flex { display: flex; }
.inline-flex { display: inline-flex; }
.grid { display: grid; }

/* Hide on mobile, show on tablet+ */
.hidden-mobile {
    display: none;
}

@media (min-width: 768px) {
    .hidden-mobile {
        display: block;
    }
}

/* Show on mobile, hide on tablet+ */
.show-mobile {
    display: block;
}

@media (min-width: 768px) {
    .show-mobile {
        display: none;
    }
}

/* Responsive display modifiers */
@media (min-width: 768px) {
    .md\:block { display: block; }
    .md\:flex { display: flex; }
    .md\:grid { display: grid; }
    .md\:hidden { display: none; }
}

@media (min-width: 1024px) {
    .lg\:block { display: block; }
    .lg\:flex { display: flex; }
    .lg\:grid { display: grid; }
    .lg\:hidden { display: none; }
}

/* ------------------------------------------------------------------------
    DARK MODE TOGGLE COMPONENT
    ------------------------------------------------------------------------ */
.theme-toggle {
    position: relative;
    display: inline-flex;
    align-items: center;
    gap: var(--space-2);
    padding: var(--space-2);
    background-color: var(--color-bg-secondary);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-full);
    cursor: pointer;
    transition: all var(--transition-fast);
}

.theme-toggle:hover {
    background-color: var(--color-bg-tertiary);
    transform: scale(1.05);
}

.theme-toggle:active {
    transform: scale(0.95);
}

.theme-icon {
    width: 20px;
    height: 20px;
    color: var(--color-text-secondary);
    transition: color var(--transition-fast);
}

.theme-toggle:hover .theme-icon {
    color: var(--color-text-primary);
}

/* ------------------------------------------------------------------------
    ACCESSIBILITY & FOCUS STATES
    ------------------------------------------------------------------------ */
:focus-visible {
    outline: 2px solid var(--color-primary);
    outline-offset: 2px;
}

.focus-ring:focus {
    outline: 2px solid var(--color-primary);
    outline-offset: 2px;
    border-radius: var(--radius-sm);
}

/* Screen reader only */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
}

/* ------------------------------------------------------------------------
    SMOOTH TRANSITIONS FOR THEME CHANGES
    ------------------------------------------------------------------------ */
* {
    transition-property: background-color, border-color, color, fill, stroke;
    transition-duration: var(--transition-base);
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
}

/* Exclude certain properties from theme transitions */
*:is(
    [class*="transition-"],
    [class*="animate-"],
    [class*="menu-"]
) {
    transition-property: none;
}

/* Re-enable specific transitions */
.transition-all {
    transition: all var(--transition-base);
}

.transition-colors {
    transition: background-color var(--transition-base),
                border-color var(--transition-base),
                color var(--transition-base);
}

.transition-transform {
    transition: transform var(--transition-base);
}

/* ------------------------------------------------------------------------
    PRINT STYLES
    ------------------------------------------------------------------------ */
@media print {
    body {
        background: white;
        color: black;
    }
    
    .no-print,
    .mobile-menu,
    .theme-toggle {
        display: none !important;
    }
    
    .container {
        max-width: none;
    }
}

/* ------------------------------------------------------------------------
    ENHANCED CARD COMPONENT - Dark Mode Aware
    ------------------------------------------------------------------------ */
.card {
    background-color: var(--color-bg-primary);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-lg);
    padding: var(--space-4);
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-base);
}

.card:hover {
    box-shadow: var(--shadow-md);
    transform: translateY(-2px);
}

@media (min-width: 768px) {
    .card {
        padding: var(--space-6);
    }
}

.card-header {
    margin-bottom: var(--space-4);
    padding-bottom: var(--space-3);
    border-bottom: 1px solid var(--color-border);
}

.card-title {
    font-size: var(--text-xl);
    font-weight: 600;
    color: var(--color-text-primary);
    margin-bottom: var(--space-2);
}

.card-subtitle {
    font-size: var(--text-sm);
    color: var(--color-text-secondary);
}

.card-body {
    color: var(--color-text-secondary);
    line-height: 1.6;
}

.card-footer {
    margin-top: var(--space-4);
    padding-top: var(--space-3);
    border-top: 1px solid var(--color-border);
}

/* ------------------------------------------------------------------------
    BUTTON COMPONENT - Dark Mode Aware
    ------------------------------------------------------------------------ */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: var(--space-2);
    padding: var(--space-3) var(--space-5);
    font-size: var(--text-base);
    font-weight: 600;
    line-height: 1;
    border: none;
    border-radius: var(--radius-md);
    cursor: pointer;
    transition: all var(--transition-fast);
    text-decoration: none;
    white-space: nowrap;
    -webkit-tap-highlight-color: transparent;
}

.btn:active {
    transform: scale(0.97);
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

/* Button Sizes */
.btn-sm {
    padding: var(--space-2) var(--space-4);
    font-size: var(--text-sm);
}

.btn-lg {
    padding: var(--space-4) var(--space-6);
    font-size: var(--text-lg);
}

/* Button Variants */
.btn-primary {
    background-color: var(--color-primary);
    color: var(--color-text-inverse);
}

.btn-primary:hover {
    background-color: var(--color-primary-hover);
    box-shadow: var(--shadow-md);
}

.btn-secondary {
    background-color: var(--color-bg-secondary);
    color: var(--color-text-primary);
    border: 1px solid var(--color-border);
}

.btn-secondary:hover {
    background-color: var(--color-bg-tertiary);
}

.btn-outline {
    background-color: transparent;
    color: var(--color-primary);
    border: 2px solid var(--color-primary);
}

.btn-outline:hover {
    background-color: var(--color-primary);
    color: var(--color-text-inverse);
}

.btn-ghost {
    background-color: transparent;
    color: var(--color-text-secondary);
}

.btn-ghost:hover {
    background-color: var(--color-bg-secondary);
    color: var(--color-text-primary);
}

/* ------------------------------------------------------------------------
    FORM COMPONENTS - Dark Mode Aware
    ------------------------------------------------------------------------ */
.form-group {
    margin-bottom: var(--space-5);
}

.form-label {
    display: block;
    margin-bottom: var(--space-2);
    font-size: var(--text-sm);
    font-weight: 500;
    color: var(--color-text-primary);
}

.form-input,
.form-select,
.form-textarea {
    width: 100%;
    padding: var(--space-3) var(--space-4);
    font-size: var(--text-base);
    color: var(--color-text-primary);
    background-color: var(--color-bg-primary);
    border: 1px solid var(--color-border);
    border-radius: var(--radius-md);
    transition: all var(--transition-fast);
    outline: none;
}

.form-input:hover,
.form-select:hover,
.form-textarea:hover {
    border-color: var(--color-border-dark);
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px var(--color-primary-light);
}

.form-input::placeholder {
    color: var(--color-text-muted);
}

.form-textarea {
    min-height: 120px;
    resize: vertical;
}

.form-helper {
    display: block;
    margin-top: var(--space-2);
    font-size: var(--text-xs);
    color: var(--color-text-tertiary);
}

.form-error {
    display: block;
    margin-top: var(--space-2);
    font-size: var(--text-xs);
    color: var(--color-error);
}

.form-input.error,
.form-select.error,
.form-textarea.error {
    border-color: var(--color-error);
}

.form-input.error:focus,
.form-select.error:focus,
.form-textarea.error:focus {
    box-shadow: 0 0 0 3px var(--color-error-light);
}

/* ------------------------------------------------------------------------
    NAVIGATION COMPONENT - Dark Mode Aware
    ------------------------------------------------------------------------ */
.navbar {
    background-color: var(--color-bg-primary);
    border-bottom: 1px solid var(--color-border);
    padding: var(--space-4) 0;
    position: sticky;
    top: 0;
    z-index: var(--z-sticky);
    backdrop-filter: blur(10px);
    /* Use the RGB variable for the transparent background to allow backdrop-filter */
    background-color: rgba(var(--color-bg-primary-rgb), 0.95); 
}

.navbar-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 var(--space-4);
}

@media (min-width: 768px) {
    .navbar-container {
        padding: 0 var(--space-6);
    }
}

.navbar-brand {
    font-size: var(--text-xl);
    font-weight: 700;
    color: var(--color-text-primary);
    text-decoration: none;
}

.navbar-menu {
    display: none;
}

@media (min-width: 768px) {
    .navbar-menu {
        display: flex;
        align-items: center;
        gap: var(--space-6);
    }
}

.navbar-link {
    color: var(--color-text-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: color var(--transition-fast);
    position: relative;
}

.navbar-link:hover {
    color: var(--color-text-primary);
}

.navbar-link.active {
    color: var(--color-primary);
}

.navbar-link.active::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 0;
    right: 0;
    height: 2px;
    background-color: var(--color-primary);
    border-radius: var(--radius-full);
}

.navbar-toggle {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background-color: transparent;
    border: none;
    cursor: pointer;
    padding: 0;
}

@media (min-width: 768px) {
    .navbar-toggle {
        display: none;
    }
}

.hamburger {
    position: relative;
    width: 24px;
    height: 18px;
}

.hamburger span {
    position: absolute;
    left: 0;
    width: 100%;
    height: 2px;
    background-color: var(--color-text-primary);
    border-radius: var(--radius-full);
    transition: all var(--transition-base);
}

.hamburger span:nth-child(1) {
    top: 0;
}

.hamburger span:nth-child(2) {
    top: 50%;
    transform: translateY(-50%);
}

.hamburger span:nth-child(3) {
    bottom: 0;
}

.navbar-toggle.active .hamburger span:nth-child(1) {
    top: 50%;
    transform: translateY(-50%) rotate(45deg);
}

.navbar-toggle.active .hamburger span:nth-child(2) {
    opacity: 0;
}

.navbar-toggle.active .hamburger span:nth-child(3) {
    bottom: 50%;
    transform: translateY(50%) rotate(-45deg);
}

/* ------------------------------------------------------------------------
    ALERT COMPONENT - Dark Mode Aware
    ------------------------------------------------------------------------ */
.alert {
    padding: var(--space-4);
    border-radius: var(--radius-md);
    border-left: 4px solid;
    margin-bottom: var(--space-4);
    display: flex;
    align-items: flex-start;
    gap: var(--space-3);
}

.alert-success {
    background-color: var(--color-success-light);
    border-color: var(--color-success);
    color: var(--color-success);
}

.alert-warning {
    background-color: var(--color-warning-light);
    border-color: var(--color-warning);
    color: var(--color-warning);
}

.alert-error {
    background-color: var(--color-error-light);
    border-color: var(--color-error);
    color: var(--color-error);
}

.alert-info {
    background-color: var(--color-info-light);
    border-color: var(--color-info);
    color: var(--color-info);
}

.alert-icon {
    flex-shrink: 0;
    width: 20px;
    height: 20px;
}

.alert-content {
    flex: 1;
}

.alert-title {
    font-weight: 600;
    margin-bottom: var(--space-1);
}

.alert-message {
    font-size: var(--text-sm);
    opacity: 0.9;
}

/* ------------------------------------------------------------------------
    BADGE COMPONENT - Dark Mode Aware
    ------------------------------------------------------------------------ */
.badge {
    display: inline-flex;
    align-items: center;
    padding: var(--space-1) var(--space-3);
    font-size: var(--text-xs);
    font-weight: 600;
    border-radius: var(--radius-full);
    white-space: nowrap;
}

.badge-primary {
    background-color: var(--color-primary);
    color: var(--color-text-inverse);
}

.badge-success {
    background-color: var(--color-success);
    color: var(--color-text-inverse);
}

.badge-warning {
    background-color: var(--color-warning);
    color: var(--color-text-inverse);
}

.badge-error {
    background-color: var(--color-error);
    color: var(--color-text-inverse);
}

.badge-info {
    background-color: var(--color-info);
    color: var(--color-text-inverse);
}

.badge-outline {
    background-color: transparent;
    border: 1px solid currentColor;
}

/* ------------------------------------------------------------------------
    TABLE COMPONENT - Dark Mode Aware
    ------------------------------------------------------------------------ */
.table-container {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: var(--radius-lg);
    border: 1px solid var(--color-border);
}

.table {
    width: 100%;
    border-collapse: collapse;
    font-size: var(--text-sm);
}

.table thead {
    background-color: var(--color-bg-secondary);
}

.table th {
    padding: var(--space-3) var(--space-4);
    text-align: left;
    font-weight: 600;
    color: var(--color-text-primary);
    border-bottom: 1px solid var(--color-border);
    white-space: nowrap;
}

.table td {
    padding: var(--space-3) var(--space-4);
    color: var(--color-text-secondary);
    border-bottom: 1px solid var(--color-border-light);
}

.table tbody tr {
    transition: background-color var(--transition-fast);
}

.table tbody tr:hover {
    background-color: var(--color-bg-secondary);
}

.table tbody tr:last-child td {
    border-bottom: none;
}

/* Responsive table for mobile */
@media (max-width: 767px) {
    .table-responsive thead {
        display: none;
    }
    
    .table-responsive tbody,
    .table-responsive tr,
    .table-responsive td {
        display: block;
    }
    
    .table-responsive tr {
        margin-bottom: var(--space-4);
        border: 1px solid var(--color-border);
        border-radius: var(--radius-md);
        padding: var(--space-3);
    }
    
    .table-responsive td {
        padding: var(--space-2) 0;
        border: none;
        text-align: right;
        position: relative;
        padding-left: 50%;
    }
    
    .table-responsive td::before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 45%;
        padding-right: var(--space-3);
        text-align: left;
        font-weight: 600;
        color: var(--color-text-primary);
    }
}

/* ------------------------------------------------------------------------
    CUSTOM UTILITIES FOR THIS DEMO
    ------------------------------------------------------------------------ */
.grid-cols-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-4);
}

@media (min-width: 768px) {
    .md\:grid-cols-2 {
        grid-template-columns: 1fr 1fr;
    }
    .md\:grid-cols-3 {
        grid-template-columns: repeat(3, 1fr);
    }
}

/* Ensure the root element doesn't have a specific theme set initially, 
   allowing prefers-color-scheme to take effect. */
html {
    color-scheme: light dark;
}

/* *** END OF USER-PROVIDED CSS ***
*/
    </style>
</head>
<body>

    <!-- Primary Navigation Bar (Sticky and Blurred) -->
    <nav class="navbar no-print">
        <div class="navbar-container container">
            <a href="#" class="navbar-brand">DesignSystem<span class="text-brand">.io</span></a>
            
            <div class="flex items-center gap-4">
                <!-- Desktop Menu -->
                <div class="navbar-menu hidden-mobile">
                    <a href="#components" class="navbar-link active">Components</a>
                    <a href="#utilities" class="navbar-link">Utilities</a>
                    <a href="#contact" class="navbar-link">Contact</a>
                </div>
                
                <!-- Theme Toggle Button -->
                <button id="theme-toggle" class="theme-toggle">
                    <span id="light-icon" class="theme-icon" data-feather="sun" style="display:none;"></span>
                    <span id="dark-icon" class="theme-icon" data-feather="moon" style="display:none;"></span>
                    <span class="sr-only">Toggle Theme</span>
                </button>

                <!-- Mobile Menu Toggle Button -->
                <button class="navbar-toggle show-mobile" id="mobile-menu-toggle">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <span class="sr-only">Toggle Mobile Menu</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Dropdown -->
    <div class="mobile-menu no-print" id="mobile-menu">
        <div class="mobile-menu-container">
            <a href="#components" class="mobile-menu-item active">Components</a>
            <a href="#utilities" class="mobile-menu-item">Utilities</a>
            <a href="#contact" class="mobile-menu-item">Contact</a>
        </div>
    </div>

    <main class="container py-8 md:py-12">
        
        <header class="mb-8 md:mb-12">
            <h1 class="heading-1">
                <span class="text-brand">Responsive</span> & Dark Mode Ready Showcase
            </h1>
            <p class="text-lg text-secondary mt-3">
                A demonstration of the custom mobile-first CSS design system provided.
            </p>
        </header>

        <!-- Component Section -->
        <section id="components" class="mb-12 md:mb-16">
            <h2 class="heading-2 mb-6">1. Core Components</h2>
            
            <!-- Cards -->
            <div class="grid-cols-2 md:grid-cols-3 mb-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">User Card</h3>
                        <p class="card-subtitle">Enhanced interaction style</p>
                    </div>
                    <div class="card-body">
                        This card showcases the hover transition and shadows defined by the `.card` class.
                    </div>
                    <div class="card-footer">
                        <span class="badge badge-success">Active</span>
                    </div>
                </div>
                <div class="card bg-secondary">
                    <div class="card-header">
                        <h3 class="card-title">Secondary Background</h3>
                        <p class="card-subtitle">Utilizing `.bg-secondary`</p>
                    </div>
                    <div class="card-body">
                        The colors adjust automatically when switching between light and dark themes.
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">A Responsive Title</h3>
                        <p class="card-subtitle">Using heading-3 style</p>
                    </div>
                    <p class="heading-3 mb-4">Hello World</p>
                    <p class="text-sm text-tertiary">See how the font size scales on large screens.</p>
                </div>
            </div>

            <!-- Alerts -->
            <h3 class="heading-3 mt-8 mb-4">Alerts</h3>
            <div class="alert alert-info">
                <i class="alert-icon" data-feather="info"></i>
                <div class="alert-content">
                    <p class="alert-title">System Information</p>
                    <p class="alert-message">This is an informational alert styled with the `.alert-info` class.</p>
                </div>
            </div>
            <div class="alert alert-success">
                <i class="alert-icon" data-feather="check-circle"></i>
                <div class="alert-content">
                    <p class="alert-title">Success!</p>
                    <p class="alert-message">Your changes have been saved successfully.</p>
                </div>
            </div>
            <div class="alert alert-error">
                <i class="alert-icon" data-feather="alert-triangle"></i>
                <div class="alert-content">
                    <p class="alert-title">Error Encountered</p>
                    <p class="alert-message">The process failed due to insufficient permissions.</p>
                </div>
            </div>
            
            <!-- Buttons -->
            <h3 class="heading-3 mt-8 mb-4">Buttons & Badges</h3>
            <div class="flex flex-wrap gap-3 mb-6">
                <a href="#" class="btn btn-primary">Primary Button</a>
                <button class="btn btn-secondary">Secondary</button>
                <button class="btn btn-outline">Outline</button>
                <button class="btn btn-ghost">Ghost</button>
                <button class="btn btn-primary btn-sm">Small Button</button>
                <button class="btn btn-primary btn-lg">Large Button</button>
            </div>

            <div class="flex flex-wrap gap-3">
                <span class="badge badge-primary">Primary Badge</span>
                <span class="badge badge-success">Success</span>
                <span class="badge badge-warning">Warning</span>
                <span class="badge badge-error">Error</span>
                <span class="badge badge-outline">Outline</span>
            </div>
        </section>

        <!-- Form Section -->
        <section id="utilities" class="mb-12 md:mb-16">
            <h2 class="heading-2 mb-6">2. Forms & Inputs</h2>
            
            <form class="card">
                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" class="form-input" placeholder="Enter your full name">
                    <p class="form-helper">This field is fully responsive and dark-mode ready.</p>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" class="form-input error" value="invalid@email">
                    <p class="form-error">Please enter a valid email address.</p>
                </div>

                <div class="form-group">
                    <label for="role" class="form-label">Role Selection</label>
                    <select id="role" class="form-select">
                        <option>Developer</option>
                        <option>Designer</option>
                        <option>Manager</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message" class="form-label">Message</label>
                    <textarea id="message" class="form-textarea" placeholder="Your thoughts..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit Form</button>
            </form>
        </section>

        <!-- Responsive Table Section -->
        <section id="contact" class="mb-12 md:mb-16">
            <h2 class="heading-2 mb-6">3. Responsive Data Table</h2>
            <p class="text-secondary mb-4">Try resizing the screen below 768px (tablet breakpoint) to see the table switch to a stack/card layout using the CSS provided.</p>

            <div class="table-container">
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Status</th>
                            <th>Progress</th>
                            <th>Due Date</th>
                            <th>Lead</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-label="Project Name">Design System V2</td>
                            <td data-label="Status"><span class="badge badge-success">Complete</span></td>
                            <td data-label="Progress">100%</td>
                            <td data-label="Due Date">2023-11-20</td>
                            <td data-label="Lead">Jane Doe</td>
                        </tr>
                        <tr>
                            <td data-label="Project Name">Marketing Campaign</td>
                            <td data-label="Status"><span class="badge badge-warning">In Progress</span></td>
                            <td data-label="Progress">65%</td>
                            <td data-label="Due Date">2024-01-15</td>
                            <td data-label="Lead">John Smith</td>
                        </tr>
                        <tr>
                            <td data-label="Project Name">Server Migration</td>
                            <td data-label="Status"><span class="badge badge-error">Delayed</span></td>
                            <td data-label="Progress">30%</td>
                            <td data-label="Due Date">2023-12-01</td>
                            <td data-label="Lead">Alice Johnson</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="border-t py-6 mt-8">
        <div class="container text-center text-sm text-tertiary">
            &copy; 2023 DesignSystem. All components built with custom CSS variables.
        </div>
    </footer>

    <script>
        // Initialize Feather Icons
        feather.replace();

        // --- Theme Toggle Logic ---
        const themeToggle = document.getElementById('theme-toggle');
        const lightIcon = document.getElementById('light-icon');
        const darkIcon = document.getElementById('dark-icon');

        /**
         * Reads the system preference and applies the theme on initial load.
         * The default preference is auto, then manually overridden by localStorage if set.
         */
        function initializeTheme() {
            const savedTheme = localStorage.getItem('theme');
            let initialTheme = 'auto'; // Default to auto/system preference

            if (savedTheme) {
                // Manual override found
                initialTheme = savedTheme;
            } else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                // No manual override, use system dark preference
                initialTheme = 'dark';
            } else {
                // No manual override, use system light preference
                initialTheme = 'light';
            }
            
            setTheme(initialTheme);
        }

        /**
         * Sets the theme, updates localStorage, and switches the icon.
         * @param {string} theme - 'light', 'dark', or 'auto'
         */
        function setTheme(theme) {
            let actualTheme;
            
            if (theme === 'auto') {
                // 'auto' theme is determined by current system preference
                actualTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                localStorage.removeItem('theme');
            } else {
                actualTheme = theme;
                localStorage.setItem('theme', theme);
            }
            
            // Set data-theme on HTML element to trigger manual overrides
            if (actualTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                lightIcon.style.display = 'block';
                darkIcon.style.display = 'none';
            } else {
                document.documentElement.setAttribute('data-theme', 'light');
                lightIcon.style.display = 'none';
                darkIcon.style.display = 'block';
            }
        }

        /**
         * Toggles the theme between light and dark (ignoring 'auto' for simplicity in this toggle)
         */
        function toggleTheme() {
            const currentAttr = document.documentElement.getAttribute('data-theme');
            const newTheme = currentAttr === 'dark' ? 'light' : 'dark';
            setTheme(newTheme);
        }

        themeToggle.addEventListener('click', toggleTheme);

        // Listen for system changes if theme is not manually overridden
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            if (!localStorage.getItem('theme')) {
                setTheme('auto');
            }
        });
        
        // Initial theme load
        initializeTheme();


        // --- Mobile Menu Logic ---
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuToggle.addEventListener('click', () => {
            const isOpen = mobileMenu.classList.contains('menu-open');
            if (isOpen) {
                mobileMenu.classList.remove('menu-open');
                mobileMenuToggle.classList.remove('active');
            } else {
                mobileMenu.classList.add('menu-open');
                mobileMenuToggle.classList.add('active');
            }
        });

        // Close menu when a link is clicked
        document.querySelectorAll('.mobile-menu-item').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('menu-open');
                mobileMenuToggle.classList.remove('active');
            });
        });
    </script>

</body>
</html>