{{-- Shared by Artikel/Sorotan/Portofolio detail views. Expects $media: array of {url, alt_text}. --}}
@if(!empty($media))
    @if(count($media) === 1)
        <div class="cms-media-single">
            <img src="{{ $media[0]['url'] }}" alt="{{ $media[0]['alt_text'] ?? '' }}" class="cms-lightbox-trigger" data-full="{{ $media[0]['url'] }}" data-alt="{{ $media[0]['alt_text'] ?? '' }}" loading="lazy">
        </div>
    @else
        <div class="cms-carousel">
            <div class="cms-carousel-track">
                @foreach($media as $i => $photo)
                    <div class="cms-carousel-slide {{ $i === 0 ? 'is-active' : '' }}">
                        <img src="{{ $photo['url'] }}" alt="{{ $photo['alt_text'] ?? '' }}" class="cms-lightbox-trigger" data-full="{{ $photo['url'] }}" data-alt="{{ $photo['alt_text'] ?? '' }}" loading="lazy">
                    </div>
                @endforeach
            </div>
            <button type="button" class="cms-carousel-arrow cms-carousel-prev" aria-label="Sebelumnya">&larr;</button>
            <button type="button" class="cms-carousel-arrow cms-carousel-next" aria-label="Berikutnya">&rarr;</button>
            <div class="cms-carousel-dots">
                @foreach($media as $i => $photo)
                    <button type="button" class="cms-carousel-dot {{ $i === 0 ? 'is-active' : '' }}" aria-label="Gambar {{ $i + 1 }}"></button>
                @endforeach
            </div>
        </div>
    @endif
@endif
