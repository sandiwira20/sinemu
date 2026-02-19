<footer class="footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="footer-logo">
                <div class="footer-logo-badge">Si</div>
                <span>SiNemu</span>
            </div>
            <p class="footer-desc">
                Bantu pengguna menemukan kembali barang berharganya dengan cepat dan praktis.
            </p>
            <div class="footer-badges">
                <span class="tag">Free</span>
                <span class="tag subtle">24/7 Online</span>
                <span class="tag subtle">Admin Verifikasi</span>
            </div>
        </div>

        <div class="footer-contact">
            <div class="footer-title">CONTACT US</div>
            <ul>
                <li>sinemu25@gmail.com</li>
                <li>Politeknik Negeri Indramayu</li>
                <li>Jl. Raya Lohbener Lama No. 08, Indramayu</li>
            </ul>
        </div>

        <div class="footer-follow">
            <div class="footer-title">FOLLOW US</div>
            <div class="footer-social">
                <a href="#" aria-label="Instagram" title="Instagram">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M7 3h10a4 4 0 0 1 4 4v10a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4V7a4 4 0 0 1 4-4Zm0 2a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H7Zm5 3a4 4 0 1 1 0 8 4 4 0 0 1 0-8Zm0 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm4.6-2.8a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z" />
                    </svg>
                </a>
                <a href="#" aria-label="WhatsApp" title="WhatsApp">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M12 3a9 9 0 0 0-7.7 13.6L3 21l4.5-1.2A9 9 0 1 0 12 3Zm0 2a7 7 0 0 1 0 14 7 7 0 0 1-3.5-.9l-.4-.2-2.6.7.7-2.5-.2-.4A7 7 0 0 1 12 5Zm-3 3.5c.2-.4.3-.5.6-.5h.5c.2 0 .4 0 .5.3l.8 1.6c.1.2.1.4 0 .6l-.4.5c-.1.2-.2.3 0 .5.2.4.9 1.5 2 2.3.7.5 1.4.8 1.8.9.2.1.4 0 .5-.1l.7-.8c.1-.2.3-.2.5-.1l1.6.8c.2.1.3.3.3.5 0 .2-.1.6-.4.9-.3.3-.9.9-1.9.9-1 0-2.3-.3-3.8-1.3-1.4-.9-2.6-2.2-3.2-3.3-.6-1.1-.8-2-.8-2.6 0-.6.2-1.2.5-1.7Z" />
                    </svg>
                </a>
                <a href="#" aria-label="Facebook" title="Facebook">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M13.5 8.5V7c0-.8.5-1 1-1h1.5V4H14c-2.2 0-3.5 1.4-3.5 3.6v1.9H9v2.5h1.5V20h3v-8h2.2l.3-2.5h-2.5Z" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="footer-divider"></div>
        <div class="footer-bottom-text">
            <span>Copyright {{ date('Y') }} SiNemu. All rights reserved.</span>
            <span class="footer-links">
                <a href="{{ route('landing') }}#kategori">Katalog</a>
                <a href="{{ route('landing') }}">Beranda</a>
                <a href="{{ route('login') }}">Login</a>
            </span>
        </div>
    </div>
</footer>
