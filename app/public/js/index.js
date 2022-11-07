window.addEventListener('DOMContentLoaded', () => {
    const burgerBtn = document.querySelector('.header-mobile-icon');
    const menu = document.querySelector('.header-mobile-list');

    burgerBtn.addEventListener('click', () => {
        menu.classList.toggle('show');
    })
})