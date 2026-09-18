@extends('layouts.app')

@section('title', 'Artikel')
@section('meta_description', 'Artikel seputar perawatan coating, detailing, window film, dan PPF dari GlossPro.')

@section('content')

    <section class="page-header">
        <div class="container">
            <span class="eyebrow">Insight</span>
            <h1 class="section-title">Artikel GlossPro</h1>
        </div>
    </section>

    <section style="padding-top: 0;">
        <div class="container">
            @if(empty($items))
                <p class="section-subtitle" style="text-align: center; margin: var(--space-4) auto;">Konten belum tersedia saat ini.</p>
            @else
                <div class="cms-grid">
                    @foreach($items as $item)
                        <a href="{{ route('articles.show', $item['slug']) }}" class="cms-card">
                            <div class="cms-card-media">
                                @if(!empty($item['cover']))
                                    <img src="{{ $item['cover']['thumbnail_url'] }}" alt="{{ $item['cover']['alt_text'] ?? $item['title'] }}" loading="lazy">
                                @else
                                    <div class="cms-card-media-placeholder"></div>
                                @endif
                            </div>
                            <div class="cms-card-body">
                                @if(!empty($item['category']))
                                    <span class="cms-card-tag">{{ $item['category'] }}</span>
                                @endif
                                <h4>{{ $item['title'] }}</h4>
                                <p>{{ $item['excerpt'] }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                @if($meta && $meta['last_page'] > 1)
                    <nav class="cms-pagination">
                        @for($p = 1; $p <= $meta['last_page']; $p++)
                            <a href="{{ request()->fullUrlWithQuery(['page' => $p]) }}" class="{{ $p === $meta['current_page'] ? 'is-active' : '' }}">{{ $p }}</a>
                        @endfor
                    </nav>
                @endif
            @endif
        </div>
    </section>

@endsection
