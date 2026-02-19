document.addEventListener('DOMContentLoaded', () => {
    // flash message auto hide
    const alerts = document.querySelectorAll('.alert-auto-hide');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 400);
        }, 4000);
    });

    // simple carousel
    const carousel = document.querySelector('.carousel');
    if (carousel) {
        const track = carousel.querySelector('.carousel-track');
        const slides = Array.from(carousel.querySelectorAll('.carousel-slide'));
        const dotsWrapper = carousel.querySelector('.carousel-dots');
        const prevBtn = carousel.querySelector('.carousel-control-prev');
        const nextBtn = carousel.querySelector('.carousel-control-next');
        const intervalMs = Number(carousel.dataset.interval || 2000);
        let index = 0;
        let timer;
        let slideWidth = 0;
        const gap = parseFloat(getComputedStyle(track).gap || 0);

        slides.forEach((_, idx) => {
            const dot = document.createElement('button');
            dot.className = 'carousel-dot' + (idx === 0 ? ' active' : '');
            dot.setAttribute('aria-label', `Slide ${idx + 1}`);
            dot.addEventListener('click', () => {
                index = idx;
                update();
                restart();
            });
            dotsWrapper.appendChild(dot);
        });

        const dots = Array.from(dotsWrapper.querySelectorAll('.carousel-dot'));

        function update() {
            const shift = -index * (slideWidth + gap);
            track.style.transform = `translateX(${shift}px)`;
            dots.forEach((dot, idx) => {
                dot.classList.toggle('active', idx === index);
            });
        }

        function next() {
            index = (index + 1) % slides.length;
            update();
        }

        function prev() {
            index = (index - 1 + slides.length) % slides.length;
            update();
        }

        function restart() {
            clearInterval(timer);
            // Nonaktifkan auto-scroll (manual only)
        }

        function setDimensions() {
            if (!slides.length) return;
            slideWidth = slides[0].offsetWidth;
            track.style.width = `${(slideWidth + gap) * slides.length}px`;
            update();
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                prev();
                restart();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                next();
                restart();
            });
        }

        setDimensions();
        // Tidak auto-scroll, hanya manual via tombol/dot
        window.addEventListener('resize', () => {
            setDimensions();
        });
    }

    // hide/show navbar on scroll
    const nav = document.querySelector('.navbar');
    if (nav) {
        let lastY = window.scrollY;
        window.addEventListener('scroll', () => {
            const currentY = window.scrollY;
            if (currentY > lastY + 10) {
                nav.classList.add('navbar-hidden');
            } else if (currentY < lastY - 10) {
                nav.classList.remove('navbar-hidden');
            }
            lastY = currentY;
        });
    }
});
