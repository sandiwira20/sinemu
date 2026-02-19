document.addEventListener('DOMContentLoaded', () => {
    const footer = document.querySelector('.footer');
    if (!footer) return;

    const onScroll = () => {
        const rect = footer.getBoundingClientRect();
        if (rect.top < window.innerHeight) {
            footer.classList.add('footer-visible');
            window.removeEventListener('scroll', onScroll);
        }
    };

    window.addEventListener('scroll', onScroll);
    onScroll();
});
