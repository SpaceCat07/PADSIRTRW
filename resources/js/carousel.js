document.addEventListener('DOMContentLoaded', () => {
    let currentIndex = 0;
    const items = document.querySelectorAll('.carousel-item');
    const prevButton = document.querySelector('.prev');
    const nextButton = document.querySelector('.next');

    function showSlide(index) {
        items.forEach((item, i) => {
            item.classList.toggle('active', i === index);
        });
    }

    function changeSlide(direction) {
        currentIndex = (currentIndex + direction + items.length) % items.length;
        showSlide(currentIndex);
    }

    // Add event listeners to buttons
    prevButton.addEventListener('click', () => changeSlide(-1));
    nextButton.addEventListener('click', () => changeSlide(1));

    // Initialize the first slide
    showSlide(currentIndex);
});
