document.addEventListener('DOMContentLoaded', () => {
    const badgeScroll = document.querySelector('.badge-scroll');
    const badgeScrollContainer = document.querySelector('.badge-scroll-container');

    badgeScrollContainer.addEventListener('mouseenter', () => {
        badgeScroll.style.animationPlayState = 'paused';
    });

    badgeScrollContainer.addEventListener('mouseleave', () => {
        badgeScroll.style.animationPlayState = 'running';
    });
});

