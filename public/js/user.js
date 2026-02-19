document.addEventListener('DOMContentLoaded', () => {
    // Script khusus dashboard user (misal scroll ke section klaim)
    const hash = window.location.hash;
    if (hash === '#klaim') {
        const el = document.querySelector('#section-klaim');
        if (el) el.scrollIntoView({ behavior: 'smooth' });
    }
});
