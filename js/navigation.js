document.addEventListener('DOMContentLoaded', function() {
    var burgerMenu = document.querySelector('.burger-menu');
    var mobileNavigation = document.querySelector('.mobile-navigation');

    if (burgerMenu && mobileNavigation) {
        burgerMenu.addEventListener('click', function() {
            mobileNavigation.style.display = mobileNavigation.style.display === 'block' ? 'none' : 'block';
        });
    }
});