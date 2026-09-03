@extends('layouts.app')

@section('title', 'Produk')
@section('meta_description', 'Katalog lengkap kaca film gedung LEXENT - 14 varian VLT dari seri Black Vision, Reflective, High Performance, dan Ultra Protect beserta spesifikasi resminya.')

@section('content')

    <section class="page-header">
        <div class="container">
            <span class="eyebrow">Product Lineup</span>
            <h1 class="section-title">Katalog Film Gedung LEXENT</h1>
        </div>
    </section>

    <section style="padding-top: 0;">
        <div class="container">
            <div class="series-filter" id="seriesFilter">
                <button type="button" class="is-active" data-series="all">Semua</button>
                @foreach($series as $s)
                    <button type="button" data-series="{{ $s['code'] }}">{{ $s['label'] }}</button>
                @endforeach
            </div>

            <div class="product-grid" id="productGrid">
                @foreach($products as $product)
                    <div class="product-card" data-series="{{ $product['series'] }}">
                        <span class="product-badge">{{ $product['badge'] }}</span>
                        <div class="product-visual {{ $product['accent'] }}" data-series="{{ $product['series_label'] }}">{{ $product['number'] }}</div>
                        <h3>{{ $product['name'] }}</h3>
                        <div class="product-tagline">{{ $product['tagline'] }}</div>
                        <p class="product-desc">{{ $product['darkness'] }}</p>

                        <div class="product-specs">
                            <div><span>VLT</span> <b>{{ $product['vlt'] }}</b></div>
                            <div><span>TSER</span> <b>{{ $product['tser'] }}</b></div>
                            <div><span>UV Rejection</span> <b>{{ $product['uv'] }}</b></div>
                            <div><span>IRR Rejection</span> <b>{{ $product['irr'] }}</b></div>
                        </div>

                        <a href="{{ route('products.show', $product['slug']) }}" class="btn btn-outline btn-block">Lihat Detail</a>
                    </div>
                @endforeach
            </div>

            <p id="productEmpty" class="section-subtitle" style="display: none; text-align: center; margin: var(--space-4) auto 0;">
                Tidak ada varian untuk seri ini.
            </p>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        (function () {
            var buttons = document.querySelectorAll('#seriesFilter button');
            var cards = document.querySelectorAll('#productGrid .product-card');
            var empty = document.getElementById('productEmpty');

            buttons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var series = btn.getAttribute('data-series');
                    var visible = 0;

                    buttons.forEach(function (b) { b.classList.remove('is-active'); });
                    btn.classList.add('is-active');

                    cards.forEach(function (card) {
                        var match = series === 'all' || card.getAttribute('data-series') === series;
                        card.style.display = match ? '' : 'none';
                        if (match) { visible++; }
                    });

                    empty.style.display = visible === 0 ? 'block' : 'none';
                });
            });
        })();
    </script>
@endsection
