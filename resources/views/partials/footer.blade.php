<footer>
    <div class="container">
        <div class="footer-top">
            <div class="footer-brand">
                <h3>{{ $cafeInfo['name'] ?? 'Brew & Bloom' }}</h3>
                <p>{{ $cafeInfo['tagline'] ?? 'Coffee • Food • Good Vibes' }}</p>
            </div>

            <div class="footer-links">
                <a href="https://instagram.com" target="_blank" rel="noopener noreferrer">Instagram</a>
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer">Facebook</a>
                <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer">TikTok</a>
                <a href="https://wa.me/{{ $cafeInfo['phone_raw'] ?? '6281234567890' }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} {{ $cafeInfo['name'] ?? 'Brew & Bloom' }}. All rights reserved.</p>
            <p>Crafted with Laravel</p>
        </div>
    </div>
</footer>
