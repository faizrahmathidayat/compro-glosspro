@extends('layouts.app')

@section('title', 'LEXENT')
@section('meta_description', 'LEXENT Building Window Film - empat seri kaca film gedung: Black Vision, Reflective, High Performance, dan Ultra Protect. UV rejection 99%, infrared rejection hingga 99%, garansi resmi hingga 8 tahun.')

@section('content')

    {{-- ============================= HERO ============================= --}}
    <section class="hero">
        <div class="container hero-content">
            <span class="eyebrow">Building Window Film</span>
            <h1 class="hero-title">
                Smart Film.<br><span class="highlight">Better Buildings.</span>
            </h1>
            <p class="hero-subtext">
                LEXENT menghadirkan kaca film gedung yang menyatukan kontrol panas dan silau,
                proteksi sinar UV, privasi, dan efisiensi energi &mdash; untuk ruang kerja
                yang lebih nyaman dan hemat biaya.
            </p>
            <div class="hero-actions">
                <a href="{{ route('products.index') }}" class="btn btn-gold">Lihat Lini Produk</a>
                <a href="{{ route('dealers') }}" class="btn btn-outline">Cari Dealer Terdekat</a>
            </div>

            <div class="hero-stats">
                <div class="hero-stat">
                    <b>99%</b>
                    <span>UV Rejection</span>
                </div>
                <div class="hero-stat">
                    <b>99%</b>
                    <span>Infrared Rejection</span>
                </div>
                <div class="hero-stat">
                    <b>8 Thn</b>
                    <span>Garansi Resmi</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= BRAND STORY ============================= --}}
    <section id="about">
        <div class="container about-grid">
            <div>
                <span class="eyebrow">Comfort &middot; Protection &middot; Privacy</span>
                <h2 class="section-title">Advanced Technology. Premium Performance. Elevate Every Space.</h2>
                <p class="section-subtitle" style="margin-bottom: var(--space-3);">
                    Empat seri LEXENT &mdash; Black Vision, Reflective, High Performance, dan
                    Ultra Protect &mdash; menutup kebutuhan gedung dari privasi maksimal
                    hingga cahaya alami tertinggi, semuanya dengan standar penolakan panas
                    dan sinar UV kelas atas.
                </p>
                <ul class="about-list">
                    <li><span class="check-dot">&#10003;</span> <span><b>Heat &amp; Glare Control</b> &mdash; menahan panas dan silau sebelum menembus fasad kaca.</span></li>
                    <li><span class="check-dot">&#10003;</span> <span><b>UV Protection s/d 99%</b> &mdash; melindungi penghuni dan mencegah interior cepat pudar.</span></li>
                    <li><span class="check-dot">&#10003;</span> <span><b>Energy Efficiency</b> &mdash; beban pendingin ruangan turun, biaya energi lebih hemat.</span></li>
                </ul>
            </div>

            <div class="about-visual">
                <div class="about-visual-inner">LEX<span>ENT</span></div>
            </div>
        </div>
    </section>

    {{-- ============================= SERIES SHOWCASE ============================= --}}
    <section id="products" class="section-alt">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Product Lineup</span>
                <h2 class="section-title">Empat Seri untuk Setiap Gedung</h2>
                <p class="section-subtitle">
                    Pilih sesuai prioritas: privasi, tampilan reflektif, kejernihan HD, atau
                    proteksi panas maksimal.
                </p>
            </div>

            <div class="product-grid">
                @foreach($series as $s)
                    <div class="product-card">
                        <span class="product-badge">{{ count($s['attributes']) }} Keunggulan</span>
                        <div class="product-visual {{ $s['accent'] }}" data-series="{{ $s['label'] }}">{{ $s['code'] }}</div>
                        <h3>{{ $s['name'] }}</h3>
                        <div class="product-tagline">{{ $s['tagline'] }}</div>
                        <p class="product-desc">{{ $s['description'] }}</p>

                        <div class="product-specs">
                            @foreach($s['metrics'] as $metric)
                                <div>
                                    <span>{{ $metric['label'] }}</span>
                                    <b>{{ $metric['value'] }}</b>
                                </div>
                            @endforeach
                        </div>

                        <a href="{{ route('products.index') }}" class="btn btn-outline btn-block">Lihat Varian</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================= TECHNOLOGY ============================= --}}
    <section id="technology">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Technology</span>
                <h2 class="section-title">Di Balik Setiap Lembar LEXENT</h2>
                <p class="section-subtitle">
                    Tiga fondasi teknologi yang bekerja pada seluruh lini film gedung LEXENT.
                </p>
            </div>

            <div class="tech-grid">
                <div class="tech-card glass">
                    <div class="tech-icon">&#9788;</div>
                    <h4>Kontrol Panas &amp; Silau</h4>
                    <p>Menahan hingga 76% total energi matahari (TSER) dan mengurangi silau pada ruang kerja.</p>
                </div>
                <div class="tech-card glass">
                    <div class="tech-icon">&#128737;</div>
                    <h4>Proteksi UV 99%</h4>
                    <p>Nano Ceramic HD &amp; Sputter Magnetron menolak sinar UV yang merusak kulit dan interior.</p>
                </div>
                <div class="tech-card glass">
                    <div class="tech-icon">&#9889;</div>
                    <h4>Efisiensi Energi</h4>
                    <p>Beban pendingin ruangan turun sehingga konsumsi listrik gedung lebih hemat sepanjang tahun.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= TINT SIMULATOR ============================= --}}
    <section id="simulator" class="section-alt">
        <div class="container simulator">
            <div class="simulator-preview">
                <div class="simulator-window">
                    <div class="simulator-overlay" id="tintOverlay"></div>
                </div>
            </div>

            <div class="simulator-controls">
                <span class="eyebrow">Interactive Preview</span>
                <h3 class="section-title" style="font-size: 1.9rem;">Simulasi VLT pada Kaca Gedung</h3>
                <p>Pilih nilai VLT LEXENT dan lihat perkiraan tampilan kaca dari luar.</p>

                <div class="tint-options" id="tintOptions">
                    <button type="button" class="tint-option" data-tint="5">VLT 5%</button>
                    <button type="button" class="tint-option" data-tint="20">VLT 20%</button>
                    <button type="button" class="tint-option is-active" data-tint="35">VLT 35%</button>
                    <button type="button" class="tint-option" data-tint="50">VLT 50%</button>
                    <button type="button" class="tint-option" data-tint="70">VLT 70%</button>
                </div>

                <p class="simulator-readout">VLT terpilih: <b id="tintReadout">35%</b> &mdash; makin kecil, makin gelap &amp; privat.</p>
            </div>
        </div>
    </section>

    {{-- ============================= DEALER LOCATOR ============================= --}}
    <section id="dealers">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Dealer Locator</span>
                <h2 class="section-title">Kunjungi Gallery Resmi LEXENT</h2>
                <p class="section-subtitle">
                    Pemasangan hanya oleh installer resmi bersertifikat LEXENT agar garansi tetap berlaku.
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
                <h2>Rencanakan Kaca Film Gedung Anda</h2>
                <p>Konsultasikan kebutuhan fasad kaca gedung Anda dengan tim ahli LEXENT hari ini.</p>
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

            function applyTint(vlt) {
                // lower VLT = darker glass = heavier overlay
                overlay.style.opacity = Math.min(0.9, (100 - vlt) / 100 * 0.85 + 0.05);
                readout.textContent = vlt + '%';
            }

            options.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    options.forEach(function (b) { b.classList.remove('is-active'); });
                    btn.classList.add('is-active');
                    applyTint(parseInt(btn.getAttribute('data-tint'), 10));
                });
            });

            applyTint(35);
        })();
    </script>
@endsection
