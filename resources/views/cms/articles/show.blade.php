@extends('layouts.app')

@section('title', $item['title'])
@section('meta_description', $item['excerpt'] ?? $item['title'])

@section('content')

    <section class="page-header">
        <div class="container">
            <div class="cms-detail-header">
                <a href="{{ route('articles.index') }}" class="back-link">&larr; Kembali ke Artikel</a>
                @if(!empty($item['category']))
                    <span class="eyebrow">{{ $item['category'] }}</span>
                @endif
                <h1 class="section-title">{{ $item['title'] }}</h1>
            </div>
        </div>
    </section>

    <section style="padding-top: 0;">
        <div class="container cms-detail">
            @include('partials.cms-media', ['media' => $item['media'] ?? []])

            <div class="cms-article">{!! $item['body'] !!}</div>
        </div>
    </section>

@endsection
