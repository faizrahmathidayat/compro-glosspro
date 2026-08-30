@extends('layouts.app')

@section('title', 'Glosspro')
@section('meta_description', 'Glosspro - kaca film otomotif premium dengan perlindungan panas, privasi, dan gaya eksklusif.')

@section('content')

    {{-- ============================= HERO ============================= --}}
    <section class="hero">
        <div class="container hero-content">
            <span class="eyebrow">Premium Automotive Window Film</span>
            <h1 class="hero-title">
                Lindungi Setiap Perjalanan dengan <span class="highlight">Kegelapan yang Elegan</span>
            </h1>
            <p class="hero-subtext">
                Glosspro menghadirkan kaca film kelas atas yang menyatukan proteksi panas
                maksimal, privasi penuh, dan estetika metallic yang tak lekang oleh waktu.
            </p>
            <div class="hero-actions">
                <a href="{{ route('products.index') }}" class="btn btn-gold">Lihat Lini Produk</a>
                <a href="{{ route('dealers') }}" class="btn btn-outline">Cari Dealer Terdekat</a>
            </div>

            <div class="hero-stats">
                <div class="hero-stat">
                    <b>99%</b>
                    <span>Infrared Rejection</span>
                </div>
                <div class="hero-stat">
                    <b>150+</b>
                    <span>Dealer Resmi</span>
                </div>
                <div class="hero-stat">
                    <b>10 Thn</b>
                    <span>Garansi Resmi</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= ABOUT / BRAND STORY ============================= --}}
    <section id="about">
        <div class="container about-grid">
            <div>
                <span class="eyebrow">Brand Story</span>
                <h2 class="section-title">Presisi Jerman, Diracik untuk Jalanan Indonesia</h2>
                <p class="section-subtitle" style="margin-bottom: var(--space-3);">
                    Sejak awal berdiri, Glosspro berkomitmen menghadirkan kaca film dengan
                    standar optik dan termal tertinggi. Setiap lapisan diproduksi melalui
                    proses sputtering multi-layer untuk memastikan konsistensi warna,
                    ketahanan warna jangka panjang, dan performa penolakan panas yang
                    terukur secara laboratorium.
                </p>
                <ul class="about-list">
                    <li><span class="check-dot">&#10003;</span> <span><b>Teknologi Nano-Ceramic</b> — bebas gangguan sinyal GPS &amp; telepon.</span></li>
                    <li><span class="check-dot">&#10003;</span> <span><b>Anti Fading</b> — warna tetap konsisten hingga bertahun-tahun.</span></li>
                    <li><span class="check-dot">&#10003;</span> <span><b>Jaringan Resmi</b> — dipasang oleh installer bersertifikat Glosspro.</span></li>
                </ul>
            </div>

            <div class="about-visual">
                <div class="about-visual-inner">GP</div>
            </div>
        </div>
    </section>

    {{-- ============================= PRODUCT LINEUP ============================= --}}
    <section id="products">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Product Lineup</span>
                <h2 class="section-title">Pilih Level Perlindungan Anda</h2>
                <p class="section-subtitle" style="margin: 0 auto;">
                    Tiga varian Glosspro dirancang untuk kebutuhan berkendara yang berbeda —
                    dari privasi maksimal hingga visibilitas tinggi.
                </p>
            </div>

            <div class="product-grid">
                @foreach($products as $product)
                    <div class="product-card glass">
                        <span class="product-badge">{{ $product['badge'] }}</span>
                        <div class="product-visual {{ $product['accent'] }}">{{ $product['vlt'] }}</div>
                        <h3>{{ $product['name'] }}</h3>
                        <div class="product-tagline">{{ $product['tagline'] }}</div>
                        <p class="product-desc">{{ $product['short_description'] }}</p>

                        <div class="product-specs">
                            <div>
                                <b>{{ $product['vlt'] }}</b>
                                <span>VLT</span>
                            </div>
                            <div>
                                <b>{{ $product['heat_rejection'] }}</b>
                                <span>Heat Reject</span>
                            </div>
                            <div>
                                <b>{{ $product['irr'] }}</b>
                                <span>IRR</span>
                            </div>
                        </div>

                        <a href="{{ route('products.show', $product['slug']) }}" class="btn btn-outline btn-block">Lihat Detail</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================= TECHNOLOGY SHOWCASE ============================= --}}
    <section id="technology">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Technology</span>
                <h2 class="section-title">Heat Reflection Technology</h2>
                <p class="section-subtitle" style="margin: 0 auto;">
                    Inti dari setiap lembar Glosspro — memantulkan panas sebelum menembus kabin.
                </p>
            </div>

            <div class="tech-grid">
                <div class="tech-card glass">
                    <div class="tech-icon">&#9728;</div>
                    <h4>Solar Reflection</h4>
                    <p>Memantulkan radiasi matahari sebelum diserap oleh kaca kendaraan.</p>
                </div>
                <div class="tech-card glass">
                    <div class="tech-icon">&#10052;</div>
                    <h4>Ceramic Multi-Layer</h4>
                    <p>Lapisan keramik non-metal, aman untuk sinyal digital dan elektronik mobil.</p>
                </div>
                <div class="tech-card glass">
                    <div class="tech-icon">&#128737;</div>
                    <h4>UV Shield 99%</h4>
                    <p>Melindungi kulit dan interior kendaraan dari radiasi ultraviolet berbahaya.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= TINT SIMULATOR ============================= --}}
    <section id="simulator">
        <div class="container simulator">
            <div class="simulator-preview">
                <div class="simulator-window">
                    <div class="simulator-overlay" id="tintOverlay"></div>
                </div>
            </div>

            <div class="simulator-controls">
                <span class="eyebrow">Interactive Preview</span>
                <h3 class="section-title" style="font-size: 1.8rem;">Simulasi Kegelapan Kaca Film</h3>
                <p>Pilih persentase kegelapan Glosspro dan lihat pratinjaunya secara langsung.</p>

                <div class="tint-options" id="tintOptions">
                    <button type="button" class="tint-option" data-tint="20">20%</button>
                    <button type="button" class="tint-option is-active" data-tint="40">40%</button>
                    <button type="button" class="tint-option" data-tint="60">60%</button>
                    <button type="button" class="tint-option" data-tint="80">80%</button>
                </div>

                <p class="simulator-readout">Tingkat kegelapan terpilih: <b id="tintReadout">40%</b></p>
            </div>
        </div>
    </section>

    {{-- ============================= DEALER LOCATOR ============================= --}}
    <section id="dealers">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Dealer Locator</span>
                <h2 class="section-title">Kunjungi Gallery Resmi Glosspro</h2>
                <p class="section-subtitle" style="margin: 0 auto;">
                    Pemasangan hanya dilakukan oleh installer resmi bersertifikat Glosspro.
                </p>
            </div>

            <div class="dealer-grid">
                @foreach($dealers as $dealer)
                    <div class="dealer-card glass">
                        <div>
                            <h4>{{ $dealer['name'] }}</h4>
                            <p>{{ $dealer['address'] }}</p>
                            <p>{{ $dealer['phone'] }}</p>
                        </div>
                        <a href="{{ $dealer['maps_url'] }}" target="_blank" rel="noopener" class="btn btn-outline">Buka Peta</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================= CTA BAND ============================= --}}
    <section class="cta-band">
        <div class="container">
            <div class="glass">
                <h2>Siap Meningkatkan Perlindungan Kendaraan Anda?</h2>
                <p>Konsultasikan kebutuhan kaca film Anda dengan tim ahli Glosspro hari ini.</p>
                <a href="{{ route('dealers') }}" class="btn btn-gold">Hubungi Kami Sekarang</a>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        (function () {
            var overlay = document.getElementById('tintOverlay');
            var readout = document.getElementById('tintReadout');
            var options = document.querySelectorAll('#tintOptions .tint-option');

            function applyTint(percent) {
                overlay.style.opacity = percent / 100;
                readout.textContent = percent + '%';
            }

            options.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    options.forEach(function (b) { b.classList.remove('is-active'); });
                    btn.classList.add('is-active');
                    applyTint(parseInt(btn.getAttribute('data-tint'), 10));
                });
            });

            applyTint(40);
        })();
    </script>
@endsection
