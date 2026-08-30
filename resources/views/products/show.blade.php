@extends('layouts.app')

@section('title', $product['name'])
@section('meta_description', $product['short_description'])

@section('content')

    <section style="padding-top: 160px;">
        <div class="container">
            <a href="{{ route('products.index') }}" class="back-link">&larr; Kembali ke Produk</a>

            <div class="product-detail-grid">
                <div class="product-detail-visual {{ $product['accent'] }}">{{ $product['vlt'] }}</div>

                <div>
                    <span class="product-badge" style="position: static; display: inline-block; margin-bottom: var(--space-2);">{{ $product['badge'] }}</span>
                    <h1 class="section-title">{{ $product['name'] }}</h1>
                    <p class="product-tagline" style="font-size: 1rem;">{{ $product['tagline'] }}</p>
                    <p class="section-subtitle" style="margin: var(--space-3) 0;">{{ $product['description'] }}</p>

                    <div class="product-detail-specs">
                        <div class="glass">
                            <b>{{ $product['vlt'] }}</b>
                            <span>VLT</span>
                        </div>
                        <div class="glass">
                            <b>{{ $product['heat_rejection'] }}</b>
                            <span>Heat Rejection</span>
                        </div>
                        <div class="glass">
                            <b>{{ $product['irr'] }}</b>
                            <span>IRR</span>
                        </div>
                    </div>

                    <ul class="about-list">
                        @foreach($product['features'] as $feature)
                            <li><span class="check-dot">&#10003;</span> <span>{{ $feature }}</span></li>
                        @endforeach
                    </ul>

                    <div class="hero-actions" style="margin-top: var(--space-4);">
                        <a href="{{ route('dealers') }}" class="btn btn-gold">Cari Dealer Terdekat</a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline">Bandingkan Varian Lain</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
