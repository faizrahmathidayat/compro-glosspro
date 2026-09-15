@extends('layouts.app')

@section('title', 'Layanan')
@section('meta_description', 'Katalog lengkap layanan GlossPro - Car Coating, Detailing, Window Film, dan Paint Protection Film beserta spesifikasi teknis dan garansinya.')

@section('content')

    <section class="page-header">
        <div class="container">
            <span class="eyebrow">Service Catalog</span>
            <h1 class="section-title">Katalog Layanan GlossPro</h1>
        </div>
    </section>

    <section style="padding-top: 0;">
        <div class="container">
            <div class="filter-bar" id="pillarFilter">
                <button type="button" class="is-active" data-pillar="all">Semua</button>
                @foreach($pillars as $p)
                    <button type="button" data-pillar="{{ $p['slug'] }}">{{ $p['name'] }}</button>
                @endforeach
            </div>

            <div class="service-grid is-variants" id="variantGrid">
                @foreach($variants as $v)
                    <div class="service-card" data-pillar="{{ $v['pillar'] }}">
                        <span class="service-badge">{{ $v['badge'] }}</span>
                        <div class="service-visual" data-series="{{ $v['pillar_label'] }}">
                            <span class="service-icon">{!! $v['icon'] !!}</span>
                        </div>
                        <h3>{{ $v['name'] }}</h3>
                        <div class="service-tagline">{{ $v['tagline'] }}</div>
                        <p class="service-desc">{{ $v['description'] }}</p>

                        <div class="service-specs">
                            @foreach(array_slice($v['specs'], 0, 4) as $spec)
                                <div>
                                    <span>{{ $spec['label'] }}</span>
                                    <b>{{ $spec['value'] }}</b>
                                </div>
                            @endforeach
                        </div>

                        <a href="{{ route('services.show', $v['slug']) }}" class="btn btn-outline btn-block">Lihat Detail</a>
                    </div>
                @endforeach
            </div>

            <p id="variantEmpty" class="section-subtitle" style="display: none; text-align: center; margin: var(--space-4) auto 0;">
                Tidak ada layanan untuk kategori ini.
            </p>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        (function () {
            var buttons = document.querySelectorAll('#pillarFilter button');
            var cards = document.querySelectorAll('#variantGrid .service-card');
            var empty = document.getElementById('variantEmpty');

            buttons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var pillar = btn.getAttribute('data-pillar');
                    var visible = 0;

                    buttons.forEach(function (b) { b.classList.remove('is-active'); });
                    btn.classList.add('is-active');

                    cards.forEach(function (card) {
                        var match = pillar === 'all' || card.getAttribute('data-pillar') === pillar;
                        card.style.display = match ? '' : 'none';
                        if (match) { visible++; }
                    });

                    empty.style.display = visible === 0 ? 'block' : 'none';
                });
            });
        })();
    </script>
@endsection
