@extends('layouts.app')

@section('title', 'About')
@section('meta_description', 'Mengenal LEXENT Building Window Film - kaca film gedung premium. Empat seri, penolakan UV 99%, efisiensi energi, garansi resmi hingga 8 tahun.')

@section('content')

    <section class="page-header">
        <div class="container">
            <span class="eyebrow">About LEXENT</span>
            <h1 class="section-title">Smart Film. Better Buildings.</h1>
        </div>
    </section>

    <section>
        <div class="container about-grid">
            <div>
                <span class="eyebrow">Our Philosophy</span>
                <h2 class="section-title">Kaca Gedung yang Bekerja untuk Anda</h2>
                <p class="section-subtitle" style="margin-bottom: var(--space-3);">
                    LEXENT fokus pada kaca film gedung dan menguasainya. Setiap seri &mdash;
                    Black Vision, Reflective, High Performance, dan Ultra Protect &mdash; punya
                    teknologi inti berbeda, namun standar yang sama: menolak hingga 99% sinar
                    UV, menahan panas dan inframatahari, serta menjaga kaca tetap jernih.
                </p>
                <ul class="about-list">
                    <li><span class="check-dot">&#10003;</span> <span><b>Nano Ceramic HD &amp; Sputter Magnetron</b> &mdash; proteksi panas &amp; UV maksimal tanpa mengorbankan kejernihan.</span></li>
                    <li><span class="check-dot">&#10003;</span> <span><b>Efisiensi Energi</b> &mdash; menekan beban pendingin ruangan dan biaya listrik gedung.</span></li>
                    <li><span class="check-dot">&#10003;</span> <span><b>Privasi &amp; Estetika</b> &mdash; tampilan fasad yang modern, elegan, dan konsisten.</span></li>
                </ul>
            </div>

            <div class="about-visual">
                <div class="about-visual-inner">LEX<span>ENT</span></div>
            </div>
        </div>
    </section>

    <section class="section-alt">
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Tiga Janji LEXENT</span>
                <h2 class="section-title">Comfort &middot; Protection &middot; Privacy</h2>
            </div>

            <div class="tech-grid">
                <div class="tech-card glass">
                    <div class="tech-icon">&#9788;</div>
                    <h4>Comfort</h4>
                    <p>Menahan panas dan silau sebelum menembus fasad, sehingga suhu ruang kerja lebih stabil dan nyaman.</p>
                </div>
                <div class="tech-card glass">
                    <div class="tech-icon">&#128737;</div>
                    <h4>Protection</h4>
                    <p>UV rejection hingga 99% melindungi penghuni dan mencegah furnitur serta interior cepat pudar.</p>
                </div>
                <div class="tech-card glass">
                    <div class="tech-icon">&#128274;</div>
                    <h4>Privacy</h4>
                    <p>Pilihan tingkat kegelapan dan karakter reflektif untuk privasi ruang tanpa menutup cahaya alami.</p>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="section-head">
                <span class="eyebrow">Garansi</span>
                <h2 class="section-title">Terlindungi Hingga 8 Tahun</h2>
                <p class="section-subtitle">
                    Film LEXENT yang dipasang di dealer resmi tercatat sejak hari pemasangan
                    dan dapat diverifikasi kapan saja lewat halaman Cek Garansi.
                </p>
            </div>
        </div>
    </section>

    <section class="cta-band">
        <div class="container">
            <div class="glass">
                <h2>Jelajahi Katalog LEXENT</h2>
                <p>14 varian VLT dari empat seri &mdash; temukan yang paling sesuai dengan gedung Anda.</p>
                <a href="{{ route('products.index') }}" class="btn btn-gold">Lihat Produk</a>
            </div>
        </div>
    </section>

@endsection
