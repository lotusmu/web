/**
 * Default Theme JavaScript
 * Handles theme-specific functionality including favicon switching
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize favicon switching for default theme
    initFaviconSwitching();
});

/**
 * Initialize favicon switching based on dark mode toggle
 */
function initFaviconSwitching() {
    // Get the base URL from a meta tag or build it
    const baseUrl = document.querySelector('meta[name="app-url"]')?.content || window.location.origin;
    
    // Define favicon URLs for each variant (with fallbacks for default theme)
    const faviconUrls = {
        light: {
            'favicon-32': `${baseUrl}/images/themes/default/favicons/favicon-32x32.png`,
            'favicon-16': `${baseUrl}/images/themes/default/favicons/favicon-16x16.png`,
            'favicon-ico': `${baseUrl}/images/themes/default/favicons/favicon.ico`
        },
        dark: {
            // Default theme uses same favicons for both light and dark modes
            'favicon-32': `${baseUrl}/images/themes/default/favicons/favicon-32x32.png`,
            'favicon-16': `${baseUrl}/images/themes/default/favicons/favicon-16x16.png`,
            'favicon-ico': `${baseUrl}/images/themes/default/favicons/favicon.ico`
        }
    };

    /**
     * Update favicon links based on current dark mode state
     */
    function updateFavicons() {
        const isDark = document.documentElement.classList.contains('dark');
        const variant = isDark ? 'dark' : 'light';
        
        Object.keys(faviconUrls[variant]).forEach(id => {
            const link = document.getElementById(id);
            if (link) {
                link.href = faviconUrls[variant][id];
            }
        });
    }

    // Update favicons on page load
    updateFavicons();

    // Watch for dark mode changes
    const observer = new MutationObserver(() => updateFavicons());
    observer.observe(document.documentElement, { 
        attributes: true, 
        attributeFilter: ['class'] 
    });
}