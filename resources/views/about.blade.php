@extends('layouts.app')

@section('title', 'About')
@section('meta_description', 'Mengenal GlossPro - Car Coating, Detailing, Window Film, dan PPF premium. Installer bersertifikat, workshop bebas debu, garansi resmi hingga 10 tahun.')

@section('content')

    <section class="page-header">
        <div class="container">
            <span class="eyebrow">About GlossPro</span>
            <h1 class="section-title">Precision Protection. Timeless Shine.</h1>
        </div>
    </section>

    <section>
        <div class="container about-grid">
            <div>
                <span class="eyebrow">Profil Kami</span>
                <h2 class="section-title">Bukan Sekadar Detailing</h2>
                <p class="section-subtitle" style="margin-bottom: var(--space-3);">
                    GlossPro berawal dari kecintaan pada mobil yang terawat sempurna. Kini kami
                    menaungi empat layanan inti &mdash; Car Coating, Detailing, Window Film, dan
                    Paint Protection Film &mdash; dengan satu standar yang sama: dikerjakan presisi,
                    material bersertifikat, dan hasil yang bisa dipertanggungjawabkan lewat garansi
                    resmi tertulis.
                </p>
                <ul class="about-list">
                    <li><span class="check-dot">&#10003;</span> <span><b>Installer Bersertifikat</b> &mdash; setiap teknisi dilatih langsung pada material yang mereka pasang.</span></li>
                    <li><span class="check-dot">&#10003;</span> <span><b>Workshop Bebas Debu</b> &mdash; bay tertutup khusus untuk coating &amp; PPF agar hasil akhir maksimal.</span></li>
                    <li><span class="check-dot">&#10003;</span> <span><b>Garansi Tertulis</b> &mdash; setiap pengerjaan tercatat dan dapat diverifikasi lewat Cek Garansi.</span></li>
                </ul>
            </div>

            <div class="about-visual">
                <div class="about-visual-inner">
                    <img src="{{ asset('images/glosspro-logo.png') }}" alt="GlossPro">
                </div>
            </div>
        </div>
    </section>

    <section class="section-alt">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Visi &amp; Misi</span>
                <h2 class="section-title">Rujukan Utama Proteksi Kendaraan</h2>
            </div>

            <div class="tech-grid">
                <div class="tech-card glass">
                    <div class="tech-icon">&#9906;</div>
                    <h4>Visi</h4>
                    <p>Menjadi rujukan utama proteksi dan estetika kendaraan premium di Indonesia, dipercaya karena konsistensi hasil kerja.</p>
                </div>
                <div class="tech-card glass">
                    <div class="tech-icon">&#10022;</div>
                    <h4>Misi</h4>
                    <p>Menghadirkan teknologi coating, film, dan detailing terkini dengan standar pengerjaan yang terukur dan transparan.</p>
                </div>
                <div class="tech-card glass">
                    <div class="tech-icon">&#128737;</div>
                    <h4>Komitmen</h4>
                    <p>Setiap klien mendapat konsultasi jujur sesuai kebutuhan &mdash; bukan sekadar menjual paket termahal.</p>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Standar Kerja</span>
                <h2 class="section-title">Fasilitas Workshop</h2>
                <p class="section-subtitle">
                    Empat elemen yang sama di setiap cabang GlossPro, di mana pun Anda berkunjung.
                </p>
            </div>

            <div class="tech-grid">
                <div class="tech-card glass">
                    <div class="tech-icon">&#9635;</div>
                    <h4>Bay Tertutup &amp; Bertekanan Positif</h4>
                    <p>Meminimalkan partikel debu yang bisa terjebak di bawah lapisan coating atau PPF.</p>
                </div>
                <div class="tech-card glass">
                    <div class="tech-icon">&#10038;</div>
                    <h4>Pencahayaan Inspeksi Khusus</h4>
                    <p>Lampu LED sudut rendah untuk memeriksa swirl mark dan hasil akhir sebelum serah terima.</p>
                </div>
                <div class="tech-card glass">
                    <div class="tech-icon">&#128737;</div>
                    <h4>Area Cuci Terpisah</h4>
                    <p>Proses decontamination dilakukan terpisah dari area aplikasi coating/PPF yang bebas debu.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cta-band">
        <div class="container">
            <div class="glass">
                <h2>Jelajahi Layanan GlossPro</h2>
                <p>Empat pilar proteksi, belasan varian &mdash; temukan yang paling sesuai untuk kendaraan Anda.</p>
                <a href="{{ route('services.index') }}" class="btn btn-gold">Lihat Layanan</a>
            </div>
        </div>
    </section>

@endsection
