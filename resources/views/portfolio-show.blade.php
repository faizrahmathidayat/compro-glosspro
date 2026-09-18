@extends('layouts.app')

@section('title', $item['title'])
@section('meta_description', $item['excerpt'] ?? $item['title'])

@section('content')

    <section class="page-header">
        <div class="container">
            <a href="{{ route('portfolio') }}" class="back-link">&larr; Kembali ke Portfolio</a>
            @if(!empty($item['category']))
                <span class="eyebrow">{{ $item['category'] }}</span>
            @endif
            <h1 class="section-title">{{ $item['title'] }}</h1>
            @if(!empty($item['location']))
                <p class="section-subtitle">{{ $item['location'] }}</p>
            @endif
        </div>
    </section>

    <section style="padding-top: 0;">
        <div class="container cms-detail">
            @if(!empty($item['media']))
                <div class="cms-gallery">
                    @foreach($item['media'] as $media)
                        <button type="button" class="cms-gallery-item" data-full="{{ $media['url'] }}" data-alt="{{ $media['alt_text'] ?? '' }}">
                            <img src="{{ $media['thumbnail_url'] }}" alt="{{ $media['alt_text'] ?? '' }}" loading="lazy">
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="cms-article">{!! $item['body'] !!}</div>
        </div>
    </section>

@endsection
