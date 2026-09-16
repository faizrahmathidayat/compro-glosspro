@extends('layouts.app')

@section('title', $variant['name'])
@section('meta_description', $variant['name'] . ' - ' . $variant['tagline'] . '. Layanan ' . $variant['pillar_label'] . ' GlossPro dengan spesifikasi dan garansi resmi.')

@section('content')

    <section style="padding-top: 150px;">
        <div class="container">
            <a href="{{ route('services.index') }}" class="back-link">&larr; Kembali ke Katalog</a>

            <div class="service-detail-grid">
                <div class="service-detail-visual" data-series="{{ $variant['pillar_label'] }}">
                    <span class="service-icon">{!! $variant['icon'] !!}</span>
                </div>

                <div>
                    <span class="series-tag">{{ $variant['pillar_label'] }}</span>
                    <h1 class="section-title">{{ $variant['name'] }}</h1>
                    <p class="service-tagline" style="font-size: 1rem;">{{ $variant['tagline'] }}</p>

                    <div class="attr-chips">
                        @foreach($variant['attributes'] as $attr)
                            <span class="attr-chip">{{ $attr }}</span>
                        @endforeach
                    </div>

                    <p class="section-subtitle" style="margin: var(--space-3) 0;">{{ $variant['description'] }}</p>

                    <div class="service-detail-specs">
                        @foreach($variant['specs'] as $spec)
                            <div class="spec-row"><span>{{ $spec['label'] }}</span> <b>{{ $spec['value'] }}</b></div>
                        @endforeach
                    </div>

                    <span class="eyebrow">Proses Pengerjaan</span>
                    <div class="process-steps">
                        @foreach($variant['process'] as $i => $step)
                            <div class="process-step">
                                <b class="process-num">{{ $i + 1 }}</b>
                                <p>{{ $step }}</p>
                            </div>
                        @endforeach
                    </div>

                    <span class="eyebrow">Pertanyaan Umum</span>
                    <div class="faq-list">
                        @foreach($variant['faq'] as $item)
                            <details class="faq-item">
                                <summary>{{ $item['q'] }}</summary>
                                <p>{{ $item['a'] }}</p>
                            </details>
                        @endforeach
                    </div>

                    <div class="hero-actions" style="margin-top: var(--space-4);">
                        <a href="{{ route('cek-garansi') }}" class="btn btn-gold">Cek Garansi</a>
                    </div>

                    @if(!empty($related))
                        <div style="margin-top: var(--space-5);">
                            <span class="eyebrow">Varian Lain di {{ $variant['pillar_label'] }}</span>
                            <div class="related-variants">
                                @foreach($related as $r)
                                    <a href="{{ route('services.show', $r['slug']) }}">{{ $r['name'] }}</a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
