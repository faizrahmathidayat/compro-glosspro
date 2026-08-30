@extends('layouts.app')

@section('title', 'Produk')
@section('meta_description', 'Lini produk kaca film Glosspro - Ultimate, Signature, dan Eco Shield.')

@section('content')

    <section class="page-header">
        <div class="container">
            <span class="eyebrow">Product Lineup</span>
            <h1 class="section-title">Pilih Level Perlindungan Anda</h1>
        </div>
    </section>

    <section style="padding-top: 0;">
        <div class="container">
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

@endsection
