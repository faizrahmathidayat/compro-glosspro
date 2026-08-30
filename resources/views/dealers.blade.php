@extends('layouts.app')

@section('title', 'Dealer')
@section('meta_description', 'Temukan gallery dan dealer resmi Glosspro di kota Anda.')

@section('content')

    <section class="page-header">
        <div class="container">
            <span class="eyebrow">Dealer Locator</span>
            <h1 class="section-title">Kunjungi Gallery Resmi Glosspro</h1>
        </div>
    </section>

    <section style="padding-top: 0;">
        <div class="container">
            <div class="dealer-grid">
                @foreach($dealers as $dealer)
                    <div class="dealer-card glass">
                        <div>
                            <h4>{{ $dealer['name'] }}</h4>
                            <p>{{ $dealer['address'] }}</p>
                            <p>{{ $dealer['phone'] }}</p>
                        </div>
                        <a href="{{ $dealer['maps_url'] }}" target="_blank" rel="noopener" class="btn btn-outline">Buka Peta</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection
