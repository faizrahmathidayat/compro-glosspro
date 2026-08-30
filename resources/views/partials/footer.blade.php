<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-logo">GLOSS<span>PRO</span></div>
                <p class="footer-about">
                    Glosspro menghadirkan kaca film otomotif premium dengan teknologi
                    penolak panas terkini, dirancang untuk kenyamanan, privasi, dan
                    gaya hidup eksklusif Anda.
                </p>
                <div class="footer-social">
                    <a href="#" aria-label="Instagram">IG</a>
                    <a href="#" aria-label="Facebook">FB</a>
                    <a href="#" aria-label="TikTok">TT</a>
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
                <h5>Produk</h5>
                <a href="{{ route('products.show', 'ultimate') }}">Glosspro Ultimate</a>
                <a href="{{ route('products.show', 'signature') }}">Glosspro Signature</a>
                <a href="{{ route('products.show', 'eco-shield') }}">Glosspro Eco Shield</a>
            </div>

            <div class="footer-col">
                <h5>Kontak</h5>
                <p>Jl. Jenderal Sudirman No. 45, Jakarta Pusat</p>
                <p>(021) 555-0142</p>
                <p>hello@glosspro.id</p>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} Glosspro. All rights reserved.</span>
            <span>Crafted for the ones who value privacy &amp; performance.</span>
        </div>
    </div>
</footer>
