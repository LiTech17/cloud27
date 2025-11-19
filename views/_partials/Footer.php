</div> <!-- Closes the main content container started in AdminNav/Header -->
</main> <!-- Closes the <main> element started in AdminNav/Header -->

<!-- Footer Section -->
<footer class="bg-surface-200 border-t border-border mt-auto py-4 transition-colors duration-300">
    <div class="container flex flex-col md:flex-row justify-between items-center text-xs md:text-sm text-text-secondary">
        <!-- Copyright and Branding -->
        <div class="text-center md:text-left mb-2 md:mb-0">
            &copy; <?= date('Y') ?> Cloud27. All rights reserved. | Powered by DeltaComm
        </div>

        <!-- System Status / Version Info -->
        <div class="flex items-center space-x-4">
            <span class="hidden sm:inline">Version 1.0</span>
            <span class="text-primary-500 font-semibold">Status: Online</span>
        </div>
    </div>
</footer>

<!-- JavaScript for Theme Toggle and Mobile Menu (Must be placed before </body>) -->
<script>
// ICON SVG PATHS (using Tailwind's standard heroicon format)
const sunSVG = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 
16.95l-1.414 1.414m0-11.314L7.05 7.05m9.9 9.9l1.414 1.414M12 
8a4 4 0 100 8 4 4 0 000-8z"/>`;

const moonSVG = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
d="M21 12.79A9 9 0 1111.21 3a7 7 0 009.79 9.79z"/>`;

/**
 * Sets the correct SVG icon for the theme toggle button(s).
 * @param {NodeListOf<Element>} suns - Elements to show the sun icon.
 * @param {NodeListOf<Element>} moons - Elements to show the moon icon.
 * @param {boolean} showSun - True if the sun icon (light mode indicator/dark mode toggle) should be visible.
 */
function setIcons(suns, moons, showSun) {
    suns.forEach(el => {
        el.innerHTML = sunSVG;
        el.classList.toggle("hidden", !showSun);
        // Only add 'spin' for a brief animation during the switch
        if (showSun) el.classList.add("spin");
    });
    moons.forEach(el => {
        el.innerHTML = moonSVG;
        el.classList.toggle("hidden", showSun);
        if (!showSun) el.classList.add("spin");
    });
    // Remove the animation class shortly after adding it
    setTimeout(() => {
        [...suns, ...moons].forEach(el => el.classList.remove("spin"));
    }, 450);
}

document.addEventListener("DOMContentLoaded", () => {
    // --- Theme Toggle Elements ---
    const sun = document.getElementById("theme-sun");
    const moon = document.getElementById("theme-moon");
    const sunM = document.getElementById("theme-sun-mobile");
    const moonM = document.getElementById("theme-moon-mobile");

    const themeBtn = document.getElementById("theme-toggle");
    const themeBtnMobile = document.getElementById("theme-toggle-mobile");

    // Check if the required elements exist before proceeding
    if (sun && moon && themeBtn) {
        // Initial setup: is the document currently set to dark?
        const isDark = document.documentElement.classList.contains("dark");
        // Filter out mobile elements if they are null, in case they aren't used on all pages
        setIcons([sun, sunM].filter(el => el), [moon, moonM].filter(el => el), isDark);

        function toggleTheme() {
            const nowDark = document.documentElement.classList.toggle("dark");
            localStorage.setItem("theme", nowDark ? "dark" : "light");
            setIcons([sun, sunM].filter(el => el), [moon, moonM].filter(el => el), nowDark);
        }

        themeBtn.addEventListener("click", toggleTheme);
        // Check for mobile button existence before attaching listener
        if (themeBtnMobile) {
            themeBtnMobile.addEventListener("click", toggleTheme);
        }
    }


    // --- Mobile Menu Toggle ---
    const menuBtn = document.getElementById("menu-toggle");
    const mobileMenu = document.getElementById("mobile-nav");
    const iconOpen = document.getElementById("menu-icon-open");
    const iconClose = document.getElementById("menu-icon-close");

    if (menuBtn && mobileMenu && iconOpen && iconClose) {
        menuBtn.addEventListener("click", () => {
            // Check if 'menu-open' class is present
            const open = mobileMenu.classList.contains("menu-open");
            
            // Toggle classes for the menu itself
            mobileMenu.classList.toggle("menu-open", !open);
            mobileMenu.classList.toggle("menu-hidden", open);
            
            // Toggle visibility of the icons
            iconOpen.classList.toggle("hidden", !open);
            iconClose.classList.toggle("hidden", open);
        });
    }
});
</script>

</body>
</html>