<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-logo">LEX<span>ENT</span></div>
                <p class="footer-about">
                    Building Window Film. LEXENT menghadirkan kaca film gedung dengan
                    kontrol panas &amp; silau, penolakan UV hingga 99%, efisiensi energi,
                    dan garansi resmi hingga 8 tahun.
                </p>
                <div class="footer-social">
                    <a href="#" aria-label="Instagram">IG</a>
                    <a href="#" aria-label="Facebook">FB</a>
                    <a href="#" aria-label="LinkedIn">IN</a>
                    <a href="#" aria-label="WhatsApp">WA</a>
                </div>
            </div>

            <div class="footer-col">
                <h5>Sitemap</h5>
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('about') }}">About</a>
                <a href="{{ route('products.index') }}">Produk</a>
                <a href="{{ route('dealers') }}">Dealer</a>
                <a href="{{ route('cek-garansi') }}">Cek Garansi</a>
            </div>

            <div class="footer-col">
                <h5>Seri Film</h5>
                <a href="{{ route('products.show', 'bv-05') }}">Black Vision</a>
                <a href="{{ route('products.show', 'rf-05') }}">Reflective Series</a>
                <a href="{{ route('products.show', 'hp-08') }}">High Performance</a>
                <a href="{{ route('products.show', 'up-08') }}">Ultra Protect</a>
            </div>

            <div class="footer-col">
                <h5>Kontak</h5>
                <p>Jl. Jenderal Sudirman No. 45, Jakarta Pusat</p>
                <p>(021) 555-0142</p>
                <p>hello@lexent.id</p>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} LEXENT. All rights reserved.</span>
            <span>Smart Film &middot; Better Buildings</span>
        </div>
    </div>
</footer>
