@extends('layouts.app')

@section('title', $item['title'])
@section('meta_description', $item['excerpt'] ?? $item['title'])

@section('content')

    <section class="page-header">
        <div class="container">
            <div class="cms-detail-header">
                <a href="{{ route('sorotan.index') }}" class="back-link">&larr; Kembali ke Sorotan Produk</a>
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

            @if(!empty($item['spec_highlights']))
                <div class="cms-spec-list">
                    @foreach($item['spec_highlights'] as $spec)
                        <div class="cms-spec-row">
                            <span class="cms-spec-label">{{ $spec['label'] }}</span>
                            <span class="cms-spec-value">{{ $spec['value'] }}</span>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="cms-article">{!! $item['body'] !!}</div>
        </div>
    </section>

@endsection
