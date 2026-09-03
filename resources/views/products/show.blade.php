@extends('layouts.app')

@section('title', $product['name'])
@section('meta_description', $product['name'] . ' - ' . $product['tagline'] . '. VLT ' . $product['vlt'] . ', TSER ' . $product['tser'] . ', UV rejection ' . $product['uv'] . ', infrared rejection ' . $product['irr'] . '.')

@section('content')

    <section style="padding-top: 150px;">
        <div class="container">
            <a href="{{ route('products.index') }}" class="back-link">&larr; Kembali ke Katalog</a>

            <div class="product-detail-grid">
                <div class="product-detail-visual {{ $product['accent'] }}" data-series="{{ $product['series_label'] }}">{{ $product['number'] }}</div>

                <div>
                    <span class="series-tag">{{ $product['series_label'] }}</span>
                    <h1 class="section-title">{{ $product['name'] }}</h1>
                    <p class="product-tagline" style="font-size: 1rem;">{{ $product['tagline'] }} &mdash; {{ $product['darkness'] }}</p>

                    <div class="attr-chips">
                        @foreach($product['attributes'] as $attr)
                            <span class="attr-chip">{{ $attr }}</span>
                        @endforeach
                    </div>

                    <p class="section-subtitle" style="margin: var(--space-3) 0;">{{ $product['series_description'] }}</p>

                    <div class="product-detail-specs">
                        <div class="spec-row"><span>VLT (Visible Light Transmission)</span> <b>{{ $product['vlt'] }}</b></div>
                        <div class="spec-row"><span>VLR (Visible Light Reflection)</span> <b>{{ $product['vlr'] }}</b></div>
                        <div class="spec-row"><span>TSER (Total Solar Energy Rejected)</span> <b>{{ $product['tser'] }}</b></div>
                        <div class="spec-row"><span>UV Rejection</span> <b>{{ $product['uv'] }}</b></div>
                        <div class="spec-row"><span>IRR Rejection (Infrared)</span> <b>{{ $product['irr'] }}</b></div>
                        <div class="spec-row"><span>Thickness</span> <b>{{ $product['thickness'] }}</b></div>
                    </div>

                    <div class="hero-actions" style="margin-top: var(--space-4);">
                        <a href="{{ route('dealers') }}" class="btn btn-gold">Cari Dealer Terdekat</a>
                        <a href="{{ route('cek-garansi') }}" class="btn btn-outline">Cek Garansi</a>
                    </div>

                    @if(!empty($related))
                        <div style="margin-top: var(--space-5);">
                            <span class="eyebrow">Varian Lain di Seri {{ $product['series_label'] }}</span>
                            <div class="related-variants">
                                @foreach($related as $r)
                                    <a href="{{ route('products.show', $r['slug']) }}">{{ $r['series'] }} {{ $r['number'] }} &middot; VLT {{ $r['vlt'] }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
