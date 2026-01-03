/**
 * Mobile Navigation Logic
 */
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const siteNavigation = document.querySelector('.main-navigation');

    if (!menuToggle || !siteNavigation) {
        return;
    }

    menuToggle.addEventListener('click', function() {
        // 1. Accessibility Attribut umschalten (für Screenreader)
        const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
        menuToggle.setAttribute('aria-expanded', !isExpanded);

        // 2. Klasse togglen, die das Menü per CSS sichtbar macht
        siteNavigation.classList.toggle('toggled');
    });
});