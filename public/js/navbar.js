document.addEventListener('DOMContentLoaded', () => {
    const togglerButton = document.querySelector('.navbar-toggler-custom');
    const navigationMenu = document.querySelector('header > .nav');

    if (togglerButton && navigationMenu) {
        togglerButton.addEventListener('click', () => {
            // Menambah atau menghapus class 'nav-active' saat tombol di-klik
            navigationMenu.classList.toggle('nav-active');
        });
    }
});