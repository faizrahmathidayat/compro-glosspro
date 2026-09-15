@extends('layouts.app')

@section('title', 'Portfolio')
@section('meta_description', 'Portfolio pengerjaan GlossPro - Car Coating, Detailing, Window Film, dan PPF pada berbagai tipe kendaraan.')

@section('content')

    <section class="page-header">
        <div class="container">
            <span class="eyebrow">Our Work</span>
            <h1 class="section-title">Portfolio Pengerjaan GlossPro</h1>
        </div>
    </section>

    <section style="padding-top: 0;">
        <div class="container">
            <div class="filter-bar" id="portfolioFilter">
                <button type="button" class="is-active" data-pillar="all">Semua</button>
                @foreach($pillars as $p)
                    <button type="button" data-pillar="{{ $p['slug'] }}">{{ $p['name'] }}</button>
                @endforeach
            </div>

            <div class="portfolio-grid" id="portfolioGrid">
                @foreach($items as $item)
                    <div class="portfolio-card" data-pillar="{{ $item['pillar'] }}">
                        <div class="portfolio-visual">Foto Menyusul</div>
                        <div class="portfolio-body">
                            <span class="portfolio-tag">{{ $item['pillar_label'] }} &middot; {{ $item['car_type_label'] }}</span>
                            <h4>{{ $item['title'] }}</h4>
                            <p>Dikerjakan tim GlossPro di workshop bersertifikat.</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <p id="portfolioEmpty" class="section-subtitle" style="display: none; text-align: center; margin: var(--space-4) auto 0;">
                Belum ada proyek untuk kategori ini.
            </p>

            <p class="partners-note" style="margin-top: var(--space-4);">
                Panel "Foto Menyusul" adalah placeholder &mdash; ganti dengan foto before/after proyek asli per item.
            </p>
        </div>
    </section>

@endsection

@section('scripts')
    <script>
        (function () {
            var buttons = document.querySelectorAll('#portfolioFilter button');
            var cards = document.querySelectorAll('#portfolioGrid .portfolio-card');
            var empty = document.getElementById('portfolioEmpty');

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
