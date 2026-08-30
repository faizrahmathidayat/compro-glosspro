@extends('layouts.app')

@section('title', 'About')
@section('meta_description', 'Mengenal lebih dekat Glosspro - brand kaca film otomotif premium.')

@section('content')

    <section class="page-header">
        <div class="container">
            <span class="eyebrow">About Glosspro</span>
            <h1 class="section-title">Keunggulan yang Terlihat, Perlindungan yang Terasa</h1>
        </div>
    </section>

    <section>
        <div class="container about-grid">
            <div>
                <span class="eyebrow">Our Philosophy</span>
                <h2 class="section-title">Lebih dari Sekadar Kaca Film</h2>
                <p class="section-subtitle" style="margin-bottom: var(--space-3);">
                    Glosspro lahir dari keyakinan bahwa kendaraan adalah ruang privat yang layak
                    mendapatkan perlindungan terbaik. Kami memadukan riset material tingkat
                    lanjut dengan estetika metallic yang mewah, menghadirkan produk yang tidak
                    hanya melindungi, tetapi juga meningkatkan karakter kendaraan Anda.
                </p>
                <ul class="about-list">
                    <li><span class="check-dot">&#10003;</span> <span><b>Riset & Pengembangan</b> — material diuji pada iklim tropis ekstrem.</span></li>
                    <li><span class="check-dot">&#10003;</span> <span><b>Kontrol Kualitas Ketat</b> — setiap gulungan melalui inspeksi berlapis.</span></li>
                    <li><span class="check-dot">&#10003;</span> <span><b>Layanan Purna Jual</b> — garansi resmi di seluruh jaringan dealer.</span></li>
                </ul>
            </div>

            <div class="about-visual">
                <div class="about-visual-inner">GP</div>
            </div>
        </div>
    </section>

    <section class="cta-band">
        <div class="container">
            <div class="glass">
                <h2>Jelajahi Lini Produk Glosspro</h2>
                <p>Temukan varian yang paling sesuai dengan gaya berkendara Anda.</p>
                <a href="{{ route('products.index') }}" class="btn btn-gold">Lihat Produk</a>
            </div>
        </div>
    </section>

@endsection
