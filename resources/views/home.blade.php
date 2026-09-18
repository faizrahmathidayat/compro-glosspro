@extends('layouts.app')

@section('title', 'GlossPro')
@section('meta_description', 'GlossPro - Car Coating (Nano Ceramic & Graphene), Detailing, Window Film, dan Paint Protection Film premium untuk kendaraan Anda. Garansi resmi hingga 10 tahun.')

@section('content')

    {{-- ============================= HERO SLIDER ============================= --}}
    <section class="hero hero-slider" id="heroSlider">

        @foreach($heroSlides as $i => $slide)
            <div class="hero-slide{{ $i === 0 ? ' is-active' : '' }}" data-slide="{{ $i }}">
                <div class="hero-media">
                    <img src="{{ asset($slide['image']) }}" alt="{{ $slide['alt'] }}" {{ $i === 0 ? '' : 'loading="lazy"' }}>
                </div>
                <div class="container hero-content">
                    <span class="eyebrow">{{ $slide['eyebrow'] }}</span>
                    <h1 class="hero-title">
                        {{ $slide['title'] }}<br><span class="highlight">{{ $slide['title_highlight'] }}</span>
                    </h1>
                    <p class="hero-subtext">{{ $slide['subtext'] }}</p>
                    <div class="hero-actions">
                        <a href="{{ $slide['cta_url'] }}" class="btn btn-gold">{{ $slide['cta_label'] }}</a>
                        <a href="{{ route('contact') }}" class="btn btn-outline">Booking / Konsultasi Gratis</a>
                    </div>

                    <div class="hero-stats">
                        @foreach($slide['stats'] as $stat)
                            <div class="hero-stat">
                                <b>{{ $stat['value'] }}</b>
                                <span>{{ $stat['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <button type="button" class="hero-arrow hero-arrow-prev" id="heroPrev" aria-label="Slide sebelumnya">&#10094;</button>
        <button type="button" class="hero-arrow hero-arrow-next" id="heroNext" aria-label="Slide berikutnya">&#10095;</button>

        <div class="hero-dots" id="heroDots">
            @foreach($heroSlides as $i => $slide)
                <button type="button" class="hero-dot{{ $i === 0 ? ' is-active' : '' }}" data-slide-to="{{ $i }}" aria-label="Ke slide {{ $i + 1 }}"></button>
            @endforeach
        </div>
    </section>

    {{-- ============================= STATS COUNTER ============================= --}}
    <section class="stats-band">
        <div class="container">
            @foreach($stats as $stat)
                <div class="stat-tile">
                    <b>{{ $stat['value'] }}</b>
                    <span>{{ $stat['label'] }}</span>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ============================= SERVICE SHOWCASE ============================= --}}
    <section id="layanan">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Layanan Kami</span>
                <h2 class="section-title">Empat Pilar Proteksi GlossPro</h2>
                <p class="section-subtitle">
                    Dari kilau cat hingga perlindungan bodi total &mdash; pilih layanan sesuai
                    kebutuhan kendaraan Anda.
                </p>
            </div>

            <div class="service-grid">
                @foreach($pillars as $p)
                    <div class="service-card">
                        <span class="service-badge">{{ $p['attributes'][0] }}</span>
                        <div class="service-visual" data-series="{{ $p['code'] }}">
                            <span class="service-icon">{!! $p['icon'] !!}</span>
                        </div>
                        <h3>{{ $p['name'] }}</h3>
                        <div class="service-tagline">{{ $p['tagline'] }}</div>
                        <p class="service-desc">{{ $p['description'] }}</p>

                        <div class="service-specs">
                            @foreach($p['metrics'] as $metric)
                                <div>
                                    <span>{{ $metric['label'] }}</span>
                                    <b>{{ $metric['value'] }}</b>
                                </div>
                            @endforeach
                        </div>

                        <a href="{{ route('services.show', $p['slug'] . '-01') }}" class="btn btn-outline btn-block">Lihat Detail</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================= BEFORE / AFTER COMPARISON ============================= --}}
    <section id="before-after" class="section-alt">
        <div class="container compare">
            <div class="compare-frame" id="compareFrame">
                <div class="compare-after">Sesudah GlossPro</div>
                <div class="compare-before" id="compareBefore">Sebelum</div>
                <div class="compare-handle" id="compareHandle">
                    <div class="compare-handle-grip">&harr;</div>
                </div>
            </div>

            <div class="compare-controls">
                <span class="eyebrow">Interactive Showcase</span>
                <h3 class="section-title" style="font-size: 1.9rem;">Lihat Perbedaannya Sendiri</h3>
                <p>Geser slider untuk membandingkan kondisi cat sebelum dan sesudah ditangani GlossPro.</p>
                <p class="compare-note">
                    Placeholder komparasi &mdash; ganti panel "Sebelum" / "Sesudah" dengan foto proyek asli
                    (mis. dari Portfolio) saat aset foto sudah tersedia.
                </p>
            </div>
        </div>
    </section>

    {{-- ============================= WHY GLOSSPRO ============================= --}}
    <section id="why-glosspro">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Kenapa GlossPro</span>
                <h2 class="section-title">Presisi di Setiap Detail</h2>
            </div>

            <div class="tech-grid">
                <div class="tech-card glass">
                    <div class="tech-icon">&#10022;</div>
                    <h4>Installer Bersertifikat</h4>
                    <p>Setiap teknisi terlatih langsung pada material yang dipasang, bukan sekadar tukang tempel film.</p>
                </div>
                <div class="tech-card glass">
                    <div class="tech-icon">&#9906;</div>
                    <h4>Ruang Kerja Bebas Debu</h4>
                    <p>Coating dan PPF dikerjakan di bay tertutup agar hasil akhir bebas partikel dan gelembung.</p>
                </div>
                <div class="tech-card glass">
                    <div class="tech-icon">&#128737;</div>
                    <h4>Garansi Resmi Tertulis</h4>
                    <p>Setiap pengerjaan tercatat dan dapat diverifikasi kapan saja lewat halaman Cek Garansi.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ============================= BRAND PARTNERS ============================= --}}
    <section class="section-alt">
        <div class="container">
            <div class="section-head" style="margin-bottom: var(--space-4);">
                <span class="eyebrow">Material &amp; Mitra</span>
                <h2 class="section-title" style="font-size: 1.8rem;">Menggunakan Material Bersertifikat</h2>
            </div>

            <div class="partners-strip">
                @foreach($partners as $partner)
                    <span class="partner-mark">{{ $partner }}</span>
                @endforeach
            </div>
            <p class="partners-note">Logo mitra resmi menyusul &mdash; kolom ini siap diisi begitu kerja sama dikonfirmasi.</p>
        </div>
    </section>

    {{-- ============================= TESTIMONIALS ============================= --}}
    <section id="testimonials">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Testimoni</span>
                <h2 class="section-title">Kata Mereka Tentang GlossPro</h2>
            </div>

            <div class="testimonial-grid">
                @foreach($testimonials as $t)
                    <div class="testimonial-card glass">
                        <div class="testimonial-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                        <p class="testimonial-quote">&ldquo;{{ $t['quote'] }}&rdquo;</p>
                        <div class="testimonial-author">
                            <div class="testimonial-avatar">{{ strtoupper(substr($t['author'], 0, 1)) }}</div>
                            <div>
                                <b>{{ $t['author'] }}</b>
                                <span>{{ $t['meta'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================= CTA WHATSAPP ============================= --}}
    <section class="cta-band">
        <div class="container">
            <div class="glass">
                <h2>Konsultasikan Kendaraan Anda Hari Ini</h2>
                <p>Chat langsung dengan tim GlossPro untuk rekomendasi layanan yang paling sesuai.</p>
                <a href="https://wa.me/6285888899558?text=Halo%20GlossPro%2C%20saya%20ingin%20konsultasi%20layanan." target="_blank" rel="noopener" class="btn btn-gold">Chat via WhatsApp</a>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        (function () {
            // Hero slider — autoplay + arrows + dots.
            var root = document.getElementById('heroSlider');
            if (!root) { return; }

            var slides = Array.prototype.slice.call(root.querySelectorAll('.hero-slide'));
            var dots = Array.prototype.slice.call(root.querySelectorAll('.hero-dot'));
            var current = 0;
            var timer = null;
            var AUTOPLAY_MS = 6000;

            slides.forEach(function (slide, i) {
                slide.inert = !slide.classList.contains('is-active');
            });

            // Guarantee the tallest slide's content (title + subtext + CTAs +
            // stats) always clears the dot pagination, even on short/zoomed
            // viewports where 100vh alone isn't enough room — grow the
            // section instead of letting content silently overlap the dots.
            var DOTS_CLEARANCE = 64;

            function syncMinHeight() {
                root.style.minHeight = '';
                var tallest = 0;
                slides.forEach(function (slide) {
                    var content = slide.querySelector('.hero-content');
                    if (content) { tallest = Math.max(tallest, content.scrollHeight); }
                });
                var needed = tallest + DOTS_CLEARANCE;
                if (needed > window.innerHeight) {
                    root.style.minHeight = needed + 'px';
                }
            }

            syncMinHeight();
            window.addEventListener('resize', syncMinHeight);

            function goTo(index) {
                index = (index + slides.length) % slides.length;
                slides[current].classList.remove('is-active');
                slides[current].inert = true;
                dots[current].classList.remove('is-active');
                current = index;
                slides[current].classList.add('is-active');
                slides[current].inert = false;
                dots[current].classList.add('is-active');
            }

            function next() { goTo(current + 1); }
            function prev() { goTo(current - 1); }

            function startAutoplay() {
                stopAutoplay();
                timer = setInterval(next, AUTOPLAY_MS);
            }

            function stopAutoplay() {
                if (timer) { clearInterval(timer); timer = null; }
            }

            document.getElementById('heroNext').addEventListener('click', function () {
                next();
                startAutoplay();
            });

            document.getElementById('heroPrev').addEventListener('click', function () {
                prev();
                startAutoplay();
            });

            dots.forEach(function (dot, i) {
                dot.addEventListener('click', function () {
                    goTo(i);
                    startAutoplay();
                });
            });

            root.addEventListener('mouseenter', stopAutoplay);
            root.addEventListener('mouseleave', startAutoplay);

            if (slides.length > 1) {
                startAutoplay();
            }
        })();

        (function () {
            // Before / after drag-reveal comparison slider.
            var frame = document.getElementById('compareFrame');
            var before = document.getElementById('compareBefore');
            var handle = document.getElementById('compareHandle');
            var dragging = false;

            function setSplit(percent) {
                percent = Math.max(0, Math.min(100, percent));
                before.style.width = percent + '%';
                handle.style.left = percent + '%';
            }

            function positionFromEvent(clientX) {
                var rect = frame.getBoundingClientRect();
                return ((clientX - rect.left) / rect.width) * 100;
            }

            handle.addEventListener('pointerdown', function (e) {
                dragging = true;
                handle.setPointerCapture(e.pointerId);
            });

            handle.addEventListener('pointermove', function (e) {
                if (!dragging) { return; }
                setSplit(positionFromEvent(e.clientX));
            });

            ['pointerup', 'pointercancel'].forEach(function (evt) {
                handle.addEventListener(evt, function () { dragging = false; });
            });

            frame.addEventListener('click', function (e) {
                setSplit(positionFromEvent(e.clientX));
            });

            setSplit(50);
        })();
    </script>
@endsection
