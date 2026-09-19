<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-logo">
                    <img src="{{ asset('images/glosspro-logo.png') }}" alt="GlossPro">
                    <span class="footer-logo-text">GlossPro</span>
                </div>
                <p class="footer-about">
                    Car Coating &middot; Detailing &middot; Window Film &middot; PPF. GlossPro menghadirkan
                    proteksi dan estetika kendaraan premium dengan garansi resmi hingga 10 tahun.
                </p>
                <div class="footer-social">
                    <a href="#" aria-label="Instagram">IG</a>
                    <a href="#" aria-label="Facebook">FB</a>
                    <a href="#" aria-label="TikTok">TT</a>
                    <a href="https://wa.me/6285888899558" target="_blank" rel="noopener" aria-label="WhatsApp">WA</a>
                </div>
            </div>

            <div class="footer-col">
                <h5>Sitemap</h5>
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('about') }}">About Us</a>
                <a href="{{ route('portfolio') }}">Portfolio</a>
                <a href="{{ route('articles.index') }}">Artikel</a>
                <a href="{{ route('sorotan.index') }}">Sorotan Produk</a>
                <a href="{{ route('contact') }}">Contact Us</a>
                <a href="{{ route('cek-garansi') }}">Cek Garansi</a>
            </div>

            <div class="footer-col">
                <h5>Layanan</h5>
                <a href="{{ route('services.show', 'car-coating-01') }}">Car Coating</a>
                <a href="{{ route('services.show', 'detailing-01') }}">Detailing</a>
                <a href="{{ route('services.show', 'window-film-01') }}">Window Film</a>
                <a href="{{ route('services.show', 'ppf-01') }}">Paint Protection Film</a>
            </div>

            <div class="footer-col">
                <h5>Kontak</h5>
                <p>Ruko La Valle, Citra Garden Serpong No.66 Blk B17, Cisauk, Kota Tangerang Selatan, Banten 15341</p>
                <p>0858-8889-9558</p>
                <p>hello@glosspro.id</p>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} GlossPro. All rights reserved.</span>
            <span>Precision Protection &middot; Timeless Shine</span>
        </div>
    </div>
</footer>
