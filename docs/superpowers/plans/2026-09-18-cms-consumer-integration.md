# CMS Consumer Integration — compro-1 (GlossPro) Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make `compro-1` (GlossPro) consume the CMS built in `dashboard` (Artikel, Katalog/"Sorotan Produk", Portofolio) over its public JSON API, rendering three new public sections with list + detail pages, a shared lightbox/gallery component, and graceful degradation when the CMS API is slow or unreachable.

**Architecture:** A single new `App\Services\CmsClient` wraps every call to the `dashboard` API (`Http` facade — already available via the existing `guzzlehttp/guzzle` dependency, first use of `Http` in this codebase), authenticated with the `X-API-Key` header, scoped to `site=glosspro`, cached 5 minutes per unique request, and never throws — any failure (timeout, non-200, malformed JSON) returns `null` so callers can render an empty-state instead of crashing. A new `App\Http\Controllers\CmsController` serves the brand-new Artikel (`/artikel`) and Sorotan Produk (`/sorotan`) sections. The existing `PageController::portfolio()` is modified in place (same route, same name — `URL & posisi di nav tidak berubah` per the spec) to pull from the CMS instead of the hardcoded `portfolioItems()` array, and gains a sibling `portfolioShow()` for a new `/portfolio/{slug}` detail page. A single shared lightbox (markup + vanilla JS, no new dependency) lives in the layout and is reused by all three detail pages.

**Scope decision (confirmed with user 2026-09-18):** This is the **compro-1 half** of consumer integration — `compro-2` (LEXENT) gets its own separate plan afterward, reusing the same design decisions once they're proven here. This plan covers all three content types for compro-1 in one pass (not split further per content type), since they share one `CmsClient` and one lightbox component that only need building once.

**Tech Stack:** Laravel 8.75 / PHP `^7.3|^8.0` (PHP-7.4-syntax target, matching the sibling `dashboard`/`compro-2` repos — see Global Constraints), `guzzlehttp/guzzle` ^7.0 (already installed, backs the `Http` facade), no new Composer packages, no new frontend dependencies (plain CSS + vanilla JS, matching this repo's existing hero-slider/compare-slider/mega-menu style).

**Consumer-side design source:** `dashboard`'s `docs/superpowers/specs/2026-09-18-cms-design.md`, section "Sisi Consumer (compro-1 dan compro-2, masing-masing independen)" — this plan is the first concrete execution of that section, applied to compro-1's actual codebase conventions (researched directly: `app/Http/Controllers/PageController.php`, `routes/web.php`, `resources/views/`, `public/css/style.css`).

**Dashboard API being consumed (already live, built and tested in the `dashboard` repo — see its Phase 1/2/3 plans):**
```
GET {base_url}/api/cms/articles?site=glosspro&page=N   → {data: [{slug,title,excerpt,category,published_at,cover}], meta: {current_page,last_page,per_page,total}}
GET {base_url}/api/cms/articles/{slug}?site=glosspro    → {data: {...above, body, media: [{url,thumbnail_url,width,height,alt_text}]}}
GET {base_url}/api/cms/catalog?site=glosspro&page=N     → same list shape
GET {base_url}/api/cms/catalog/{slug}?site=glosspro     → same detail shape + spec_highlights: [{label,value}]
GET {base_url}/api/cms/portfolio?site=glosspro&page=N   → same list shape
GET {base_url}/api/cms/portfolio/{slug}?site=glosspro   → same detail shape + location: string|null
```
All require header `X-API-Key: <the dashboard's CMS_API_KEY>`. 401 on missing/wrong key, 400 on missing/invalid `site`, 404 on draft/wrong-site/missing slug.

## Global Constraints

- **PHP 7.4 syntax only** — no constructor property promotion, no `match()`, no nullsafe `?->`, no native `enum`. Typed properties, arrow functions, and `??=` are fine.
- No `env()` calls outside `config/services.php` — `CMS_BASE_URL`/`CMS_API_KEY` are read only via `config('services.cms.*')`, following the exact precedent already set by this repo's own `DASHBOARD_BASE_URL` → `config('services.dashboard.base_url')` (used by `cekGaransi()`).
- **Never let a CMS outage break the page.** Every `CmsClient` call is wrapped so a timeout/500/exception returns `null`, and every controller/view treats `null`/empty gracefully (empty-state copy, not an exception page) — this is the single most important behavior in this plan and gets its own tests.
- Follow existing conventions exactly: `@extends('layouts.app')`, `page-header`/`section-title`/`eyebrow`/`container` classes for headers (see `portfolio.blade.php`), routes as flat `Route::get(...)->name(...)` lines in `routes/web.php` (no grouping — matches the existing 7 routes), CSS added to the single `public/css/style.css` using existing `:root` tokens (no new file, no preprocessor), JS as inline `@section('scripts')`/layout `<script>` blocks (no `public/js/` directory exists in this repo and none should be introduced).
- Tests use the existing PHPUnit scaffold (`tests/Feature`, `tests/TestCase`) with `Http::fake()` to intercept the `Http` facade — no real network calls in tests, no database needed for any test in this plan (nothing here touches a DB).
- Do not modify `servicePillars()`, `heroSlides()`, `serviceVariantLineup()`, `testimonials()`, `brandPartners()`, `stats()`, `branchList()`, or any of the four service pillar pages — those stay exactly as they are; this plan only touches `portfolioItems()`/`portfolio()` and adds new code alongside them.
- The portfolio list's current client-side pillar filter (`data-pillar` buttons) is **dropped**, not adapted — CMS Portfolio items have a freeform `category` string, not the fixed four-pillar taxonomy the old hardcoded array used, and there's no CMS equivalent of `car_type`. The new portfolio list uses the same paginated grid + empty-state pattern as Artikel/Sorotan for consistency. This is a deliberate simplification, not an oversight — flagged here so it isn't "fixed" back later without re-checking this reasoning.

---

### Task 1: `CmsClient` service — config, env, HTTP calls, caching, resilience

**Files:**
- Modify: `config/services.php`
- Modify: `.env`, `.env.example`
- Create: `app/Services/CmsClient.php`
- Test: `tests/Unit/CmsClientTest.php`

**Interfaces:**
- Produces: `CmsClient::articles(int $page = 1): ?array`, `CmsClient::article(string $slug): ?array`, `CmsClient::catalog(int $page = 1): ?array`, `CmsClient::catalogItem(string $slug): ?array`, `CmsClient::portfolio(int $page = 1): ?array`, `CmsClient::portfolioItem(string $slug): ?array`. Every method returns the CMS API's raw decoded JSON body (`['data' => ..., 'meta' => ...]` for list calls, `['data' => ...]` for detail calls) on success, or `null` on any failure — callers never need to catch exceptions.

- [ ] **Step 1: Add the `cms` config block**

Edit `config/services.php`, add after the existing `'dashboard'` block:
```php
    'dashboard' => [
        'base_url' => rtrim(env('DASHBOARD_BASE_URL', 'http://localhost:8000'), '/'),
    ],

    'cms' => [
        'base_url' => rtrim(env('CMS_BASE_URL', 'http://localhost:8000'), '/'),
        'api_key' => env('CMS_API_KEY'),
        'site' => 'glosspro',
    ],
```
(The dashboard's CMS API lives on the same app as the existing warranty dashboard, so the default matches `DASHBOARD_BASE_URL` — in production these may point to the same or different hosts, hence two separate config keys rather than reusing one.)

- [ ] **Step 2: Add the env vars**

Append to `.env`:
```
CMS_BASE_URL=http://localhost:8000
CMS_API_KEY=22f5a97b54c4c7328d7208810f29f23f403d46d0ab4143c201aac43824b6fa55
```
(That key must match the `dashboard` repo's own `CMS_API_KEY` in its `.env` exactly — they're the same secret shared between the two apps. Confirm by checking `dashboard/.env`'s `CMS_API_KEY` value before pasting.)

Append to `.env.example` (placeholder, no real secret):
```
CMS_BASE_URL=http://localhost:8000
CMS_API_KEY=
```

- [ ] **Step 3: Write the failing test**

```php
<?php

namespace Tests\Unit;

use App\Services\CmsClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CmsClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_articles_returns_the_decoded_response_on_success(): void
    {
        Http::fake([
            '*/api/cms/articles*' => Http::response([
                'data' => [['slug' => 'a', 'title' => 'Judul A']],
                'meta' => ['current_page' => 1, 'last_page' => 1, 'per_page' => 9, 'total' => 1],
            ], 200),
        ]);

        $result = (new CmsClient())->articles(1);

        $this->assertSame('a', $result['data'][0]['slug']);
        $this->assertSame(1, $result['meta']['total']);
    }

    public function test_sends_the_api_key_header_and_site_parameter(): void
    {
        Http::fake(['*/api/cms/articles*' => Http::response(['data' => [], 'meta' => []], 200)]);

        (new CmsClient())->articles(1);

        Http::assertSent(function ($request) {
            return $request->hasHeader('X-API-Key', config('services.cms.api_key'))
                && strpos($request->url(), 'site=glosspro') !== false;
        });
    }

    public function test_returns_null_on_a_non_success_response(): void
    {
        Http::fake(['*/api/cms/articles*' => Http::response(['message' => 'error'], 500)]);

        $result = (new CmsClient())->articles(1);

        $this->assertNull($result);
    }

    public function test_returns_null_instead_of_throwing_on_a_connection_failure(): void
    {
        Http::fake(function () {
            throw new ConnectionException('Connection timed out');
        });

        $result = (new CmsClient())->articles(1);

        $this->assertNull($result);
    }

    public function test_article_show_hits_the_detail_endpoint(): void
    {
        Http::fake(['*/api/cms/articles/kaca-film*' => Http::response(['data' => ['slug' => 'kaca-film']], 200)]);

        $result = (new CmsClient())->article('kaca-film');

        $this->assertSame('kaca-film', $result['data']['slug']);
    }

    public function test_catalog_and_portfolio_hit_their_own_endpoints(): void
    {
        Http::fake([
            '*/api/cms/catalog*' => Http::response(['data' => ['slug' => 'katalog-x']], 200),
            '*/api/cms/portfolio*' => Http::response(['data' => ['slug' => 'proyek-x']], 200),
        ]);

        $client = new CmsClient();

        $this->assertSame('katalog-x', $client->catalogItem('katalog-x')['data']['slug']);
        $this->assertSame('proyek-x', $client->portfolioItem('proyek-x')['data']['slug']);
    }

    public function test_a_successful_response_is_cached_and_the_http_call_is_not_repeated(): void
    {
        Http::fake(['*/api/cms/articles*' => Http::response(['data' => [], 'meta' => []], 200)]);

        $client = new CmsClient();
        $client->articles(1);
        $client->articles(1);

        Http::assertSentCount(1);
    }

    public function test_a_failed_response_is_not_cached_so_the_next_call_retries(): void
    {
        Http::fakeSequence()
            ->push(['message' => 'error'], 500)
            ->push(['data' => [], 'meta' => []], 200);

        $client = new CmsClient();
        $first = $client->articles(1);
        $second = $client->articles(1);

        $this->assertNull($first);
        $this->assertNotNull($second);
    }
}
```

- [ ] **Step 4: Run the test to verify it fails**

Run: `php artisan test --filter=CmsClientTest`
Expected: FAIL — `Class "App\Services\CmsClient" not found`.

- [ ] **Step 5: Write `app/Services/CmsClient.php`**

```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CmsClient
{
    private const CACHE_TTL_SECONDS = 300;

    public function articles(int $page = 1): ?array
    {
        return $this->request('/api/cms/articles', ['page' => $page]);
    }

    public function article(string $slug): ?array
    {
        return $this->request('/api/cms/articles/' . $slug, []);
    }

    public function catalog(int $page = 1): ?array
    {
        return $this->request('/api/cms/catalog', ['page' => $page]);
    }

    public function catalogItem(string $slug): ?array
    {
        return $this->request('/api/cms/catalog/' . $slug, []);
    }

    public function portfolio(int $page = 1): ?array
    {
        return $this->request('/api/cms/portfolio', ['page' => $page]);
    }

    public function portfolioItem(string $slug): ?array
    {
        return $this->request('/api/cms/portfolio/' . $slug, []);
    }

    private function request(string $path, array $query): ?array
    {
        $query['site'] = config('services.cms.site');
        $cacheKey = 'cms:' . $path . ':' . http_build_query($query);

        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        try {
            $response = Http::withHeaders([
                'X-API-Key' => config('services.cms.api_key'),
            ])->timeout(5)->get(config('services.cms.base_url') . $path, $query);

            if (!$response->successful()) {
                return null;
            }

            $decoded = $response->json();
            Cache::put($cacheKey, $decoded, self::CACHE_TTL_SECONDS);

            return $decoded;
        } catch (\Throwable $e) {
            Log::warning('CMS API request failed: ' . $e->getMessage());

            return null;
        }
    }
}
```

- [ ] **Step 6: Run the test to verify it passes**

Run: `php artisan test --filter=CmsClientTest`
Expected: PASS (8 tests).

- [ ] **Step 7: Commit**

```bash
git add config/services.php .env.example app/Services/CmsClient.php tests/Unit/CmsClientTest.php
git commit -m "Add CmsClient service for calling the dashboard CMS API"
```
(`.env` itself is gitignored — nothing to add there.)

---

### Task 2: Artikel — `/artikel` list + `/artikel/{slug}` detail + shared lightbox

**Files:**
- Create: `app/Http/Controllers/CmsController.php`
- Modify: `routes/web.php`
- Create: `resources/views/cms/articles/index.blade.php`
- Create: `resources/views/cms/articles/show.blade.php`
- Create: `resources/views/partials/lightbox.blade.php`
- Modify: `resources/views/layouts/app.blade.php` (include the lightbox partial + its JS)
- Modify: `resources/views/partials/navbar.blade.php`, `resources/views/partials/footer.blade.php`
- Modify: `public/css/style.css`
- Test: `tests/Feature/ArtikelPageTest.php`

**Interfaces:**
- Consumes: `CmsClient::articles()`/`::article()` (Task 1).
- Produces: routes `articles.index` (`GET /artikel`), `articles.show` (`GET /artikel/{slug}`). The lightbox partial/JS/CSS built here (`.cms-gallery`, `.cms-lightbox`) is shared verbatim by Tasks 3 and 4 — nothing further needs to change in the layout after this task.

- [ ] **Step 1: Write the failing tests**

```php
<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ArtikelPageTest extends TestCase
{
    public function test_index_renders_the_list_from_the_cms(): void
    {
        Http::fake(['*/api/cms/articles*' => Http::response([
            'data' => [['slug' => 'tips-coating', 'title' => 'Tips Merawat Coating', 'excerpt' => 'Ringkasan', 'category' => 'Tips', 'published_at' => '2026-09-18T00:00:00+00:00', 'cover' => null]],
            'meta' => ['current_page' => 1, 'last_page' => 1, 'per_page' => 9, 'total' => 1],
        ], 200)]);

        $response = $this->get('/artikel');

        $response->assertOk();
        $response->assertSee('Tips Merawat Coating');
    }

    public function test_index_shows_an_empty_state_when_the_cms_is_unreachable(): void
    {
        Http::fake(['*/api/cms/articles*' => Http::response([], 500)]);

        $response = $this->get('/artikel');

        $response->assertOk();
        $response->assertSee('belum tersedia', false);
    }

    public function test_show_renders_the_body_and_media(): void
    {
        Http::fake(['*/api/cms/articles/tips-coating*' => Http::response(['data' => [
            'slug' => 'tips-coating', 'title' => 'Tips Merawat Coating', 'body' => '<p>Isi lengkap artikel</p>',
            'published_at' => '2026-09-18T00:00:00+00:00', 'category' => 'Tips',
            'media' => [['url' => 'https://example.test/a.webp', 'thumbnail_url' => 'https://example.test/a-thumb.webp', 'width' => 800, 'height' => 600, 'alt_text' => null]],
        ]], 200)]);

        $response = $this->get('/artikel/tips-coating');

        $response->assertOk();
        $response->assertSee('Isi lengkap artikel', false);
        $response->assertSee('cms-gallery-item', false);
    }

    public function test_show_returns_404_when_the_cms_has_no_matching_article(): void
    {
        Http::fake(['*/api/cms/articles/tidak-ada*' => Http::response(['message' => 'Artikel tidak ditemukan.'], 404)]);

        $this->get('/artikel/tidak-ada')->assertStatus(404);
    }
}
```

- [ ] **Step 2: Run the tests to verify they fail**

Run: `php artisan test --filter=ArtikelPageTest`
Expected: FAIL — routes don't exist yet (404s from Laravel's default handler, not the intended ones).

- [ ] **Step 3: Write `app/Http/Controllers/CmsController.php`**

```php
<?php

namespace App\Http\Controllers;

use App\Services\CmsClient;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    private CmsClient $cms;

    public function __construct(CmsClient $cms)
    {
        $this->cms = $cms;
    }

    public function articles(Request $request)
    {
        $page = max(1, (int) $request->query('page', 1));
        $response = $this->cms->articles($page);

        return view('cms.articles.index', [
            'items' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? null,
        ]);
    }

    public function articleShow(string $slug)
    {
        $response = $this->cms->article($slug);

        abort_if(!isset($response['data']), 404);

        return view('cms.articles.show', ['item' => $response['data']]);
    }

    public function catalog(Request $request)
    {
        $page = max(1, (int) $request->query('page', 1));
        $response = $this->cms->catalog($page);

        return view('cms.catalog.index', [
            'items' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? null,
        ]);
    }

    public function catalogShow(string $slug)
    {
        $response = $this->cms->catalogItem($slug);

        abort_if(!isset($response['data']), 404);

        return view('cms.catalog.show', ['item' => $response['data']]);
    }
}
```
(`catalog()`/`catalogShow()` are included now since they're trivial once `articles()`/`articleShow()` exist — Task 3 only needs to add their routes/views, not touch this controller again.)

- [ ] **Step 4: Add the routes**

In `routes/web.php`, add the import alongside the existing `use App\Http\Controllers\PageController;` line, and two new routes right after the existing `/portfolio` line:
```php
use App\Http\Controllers\CmsController;
```
```php
Route::get('/artikel', [CmsController::class, 'articles'])->name('articles.index');
Route::get('/artikel/{slug}', [CmsController::class, 'articleShow'])->name('articles.show');
```

- [ ] **Step 5: Add the shared lightbox partial**

`resources/views/partials/lightbox.blade.php`:
```blade
<div class="cms-lightbox" id="cmsLightbox" aria-hidden="true">
    <div class="cms-lightbox-backdrop" id="cmsLightboxBackdrop"></div>
    <button type="button" class="cms-lightbox-close" id="cmsLightboxClose" aria-label="Tutup">&times;</button>
    <button type="button" class="cms-lightbox-nav cms-lightbox-prev" id="cmsLightboxPrev" aria-label="Sebelumnya">&larr;</button>
    <div class="cms-lightbox-frame">
        <img src="" alt="" id="cmsLightboxImage">
    </div>
    <button type="button" class="cms-lightbox-nav cms-lightbox-next" id="cmsLightboxNext" aria-label="Berikutnya">&rarr;</button>
</div>
```

- [ ] **Step 6: Include the partial and add the lightbox JS to the layout**

In `resources/views/layouts/app.blade.php`, add the include right after `@include('partials.footer')`:
```php
    @include('partials.footer')

    @include('partials.lightbox')
```
Then, inside the existing inline `<script>` block, right before the closing `})();` (i.e. after the `window.addEventListener('resize', ...)` block, still inside the same IIFE), add:
```javascript
            // Lightbox — opened by any .cms-gallery-item on the page (Artikel/Sorotan/Portofolio detail views).
            var lightbox = document.getElementById('cmsLightbox');
            var lightboxImage = document.getElementById('cmsLightboxImage');
            var lightboxItems = [];
            var lightboxIndex = 0;

            function openLightboxAt(index) {
                if (!lightboxItems[index]) { return; }
                lightboxIndex = index;
                lightboxImage.setAttribute('src', lightboxItems[index].getAttribute('data-full'));
                lightboxImage.setAttribute('alt', lightboxItems[index].getAttribute('data-alt') || '');
                lightbox.classList.add('is-open');
                lightbox.setAttribute('aria-hidden', 'false');
            }

            function closeLightbox() {
                lightbox.classList.remove('is-open');
                lightbox.setAttribute('aria-hidden', 'true');
            }

            if (lightbox && lightboxImage) {
                lightboxItems = Array.prototype.slice.call(document.querySelectorAll('.cms-gallery-item'));

                lightboxItems.forEach(function (item, index) {
                    item.addEventListener('click', function () { openLightboxAt(index); });
                });

                var lightboxClose = document.getElementById('cmsLightboxClose');
                var lightboxBackdrop = document.getElementById('cmsLightboxBackdrop');
                var lightboxPrev = document.getElementById('cmsLightboxPrev');
                var lightboxNext = document.getElementById('cmsLightboxNext');

                if (lightboxClose) { lightboxClose.addEventListener('click', closeLightbox); }
                if (lightboxBackdrop) { lightboxBackdrop.addEventListener('click', closeLightbox); }
                if (lightboxPrev) { lightboxPrev.addEventListener('click', function () { openLightboxAt((lightboxIndex - 1 + lightboxItems.length) % lightboxItems.length); }); }
                if (lightboxNext) { lightboxNext.addEventListener('click', function () { openLightboxAt((lightboxIndex + 1) % lightboxItems.length); }); }

                document.addEventListener('keydown', function (e) {
                    if (!lightbox.classList.contains('is-open')) { return; }
                    if (e.key === 'Escape') { closeLightbox(); }
                    if (e.key === 'ArrowLeft' && lightboxPrev) { lightboxPrev.click(); }
                    if (e.key === 'ArrowRight' && lightboxNext) { lightboxNext.click(); }
                });
            }
```

- [ ] **Step 7: Write `resources/views/cms/articles/index.blade.php`**

```blade
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
```

- [ ] **Step 8: Write `resources/views/cms/articles/show.blade.php`**

```blade
@extends('layouts.app')

@section('title', $item['title'])
@section('meta_description', $item['excerpt'] ?? $item['title'])

@section('content')

    <section class="page-header">
        <div class="container">
            <a href="{{ route('articles.index') }}" class="back-link">&larr; Kembali ke Artikel</a>
            @if(!empty($item['category']))
                <span class="eyebrow">{{ $item['category'] }}</span>
            @endif
            <h1 class="section-title">{{ $item['title'] }}</h1>
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
```

- [ ] **Step 9: Add CSS for the CMS grid, cards, pagination, article body, and lightbox**

Append to `public/css/style.css`:
```css
/* ---------- CMS Content (Artikel / Sorotan Produk / Portofolio) ---------- */
.cms-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: var(--space-3);
}

.cms-card {
    display: block;
    background: var(--bg-alt);
    border: 1px solid var(--line);
    border-radius: var(--radius-md);
    overflow: hidden;
    text-decoration: none;
    color: inherit;
    transition: border-color var(--transition-fast), transform var(--transition-fast);
}

.cms-card:hover {
    border-color: var(--line-bright);
    transform: translateY(-2px);
}

.cms-card-media {
    aspect-ratio: 16 / 10;
    background: var(--bg-raised);
    overflow: hidden;
}

.cms-card-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.cms-card-media-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, var(--glow-1), var(--glow-2));
}

.cms-card-body {
    padding: var(--space-2);
}

.cms-card-tag {
    display: inline-block;
    font-size: 0.75rem;
    color: var(--silver);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: var(--space-1);
}

.cms-card-body h4 {
    margin: 0 0 var(--space-1);
    font-family: var(--font-display);
}

.cms-card-body p {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin: 0;
}

.cms-pagination {
    display: flex;
    justify-content: center;
    gap: var(--space-1);
    margin-top: var(--space-4);
}

.cms-pagination a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 40px;
    height: 40px;
    border-radius: var(--radius-pill);
    border: 1px solid var(--line);
    color: var(--text);
    text-decoration: none;
    font-size: 0.9rem;
}

.cms-pagination a.is-active {
    background: var(--silver);
    border-color: var(--silver);
    color: #121316;
    font-weight: 600;
}

.cms-detail {
    max-width: 780px;
}

.cms-article {
    color: var(--text-muted);
    line-height: 1.75;
}

.cms-article h1, .cms-article h2, .cms-article h3 {
    color: var(--text);
    font-family: var(--font-display);
    margin-top: var(--space-3);
}

.cms-article p {
    margin: 0 0 var(--space-2);
}

.cms-article img {
    max-width: 100%;
    border-radius: var(--radius-sm);
}

.cms-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: var(--space-1);
    margin-bottom: var(--space-3);
}

.cms-gallery-item {
    width: 96px;
    height: 72px;
    padding: 0;
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    overflow: hidden;
    cursor: pointer;
    background: none;
}

.cms-gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* ---------- CMS Lightbox ---------- */
.cms-lightbox {
    position: fixed;
    inset: 0;
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 200;
}

.cms-lightbox.is-open {
    display: flex;
}

.cms-lightbox-backdrop {
    position: absolute;
    inset: 0;
    background: rgba(10, 11, 13, 0.92);
    backdrop-filter: blur(4px);
}

.cms-lightbox-frame {
    position: relative;
    max-width: 90vw;
    max-height: 85vh;
    z-index: 1;
}

.cms-lightbox-frame img {
    max-width: 90vw;
    max-height: 85vh;
    border-radius: var(--radius-md);
    box-shadow: var(--shadow-elevated);
}

.cms-lightbox-close, .cms-lightbox-nav {
    position: absolute;
    z-index: 2;
    background: var(--bg-panel);
    border: 1px solid var(--line-bright);
    color: var(--text);
    border-radius: var(--radius-pill);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.cms-lightbox-close {
    top: var(--space-2);
    right: var(--space-2);
    width: 40px;
    height: 40px;
    font-size: 1.25rem;
}

.cms-lightbox-nav {
    top: 50%;
    transform: translateY(-50%);
    width: 48px;
    height: 48px;
    font-size: 1.25rem;
}

.cms-lightbox-prev { left: var(--space-2); }
.cms-lightbox-next { right: var(--space-2); }
```

- [ ] **Step 10: Add nav and footer links**

In `resources/views/partials/navbar.blade.php`, add right after the Portfolio link:
```php
            <a href="{{ route('portfolio') }}" class="{{ request()->routeIs('portfolio') ? 'is-active' : '' }}">Portfolio</a>
            <a href="{{ route('articles.index') }}" class="{{ request()->routeIs('articles.*') ? 'is-active' : '' }}">Artikel</a>
```
In `resources/views/partials/footer.blade.php`, add to the Sitemap column right after Portfolio:
```php
                <a href="{{ route('portfolio') }}">Portfolio</a>
                <a href="{{ route('articles.index') }}">Artikel</a>
```

- [ ] **Step 11: Run the tests to verify they pass**

Run: `php artisan test --filter=ArtikelPageTest`
Expected: PASS (4 tests).

- [ ] **Step 12: Commit**

```bash
git add app/Http/Controllers/CmsController.php routes/web.php resources/views/cms/articles resources/views/partials/lightbox.blade.php resources/views/layouts/app.blade.php resources/views/partials/navbar.blade.php resources/views/partials/footer.blade.php public/css/style.css tests/Feature/ArtikelPageTest.php
git commit -m "Add Artikel pages backed by the CMS API, plus shared lightbox"
```

---

### Task 3: Katalog ("Sorotan Produk") — `/sorotan` list + `/sorotan/{slug}` detail

**Files:**
- Modify: `routes/web.php`
- Create: `resources/views/cms/catalog/index.blade.php`
- Create: `resources/views/cms/catalog/show.blade.php`
- Modify: `public/css/style.css`
- Modify: `resources/views/partials/navbar.blade.php`, `resources/views/partials/footer.blade.php`
- Test: `tests/Feature/SorotanPageTest.php`

**Interfaces:**
- Consumes: `CmsController::catalog()`/`::catalogShow()` (already written in Task 2), `CmsClient::catalog()`/`::catalogItem()` (Task 1), the `.cms-grid`/`.cms-card`/`.cms-pagination`/`.cms-gallery`/`.cms-article` CSS and lightbox JS (Task 2 — nothing new needed there).
- Produces: routes `sorotan.index` (`GET /sorotan`), `sorotan.show` (`GET /sorotan/{slug}`). Nav label is **"Sorotan Produk"**, deliberately not "Produk"/"Layanan" (per spec, to avoid confusion with the existing hardcoded services catalog).

- [ ] **Step 1: Write the failing tests**

```php
<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SorotanPageTest extends TestCase
{
    public function test_index_renders_the_list_from_the_cms(): void
    {
        Http::fake(['*/api/cms/catalog*' => Http::response([
            'data' => [['slug' => 'kaca-film-20', 'title' => 'Kaca Film VLT 20%', 'excerpt' => 'Ringkasan', 'category' => 'Window Film', 'published_at' => '2026-09-18T00:00:00+00:00', 'cover' => null]],
            'meta' => ['current_page' => 1, 'last_page' => 1, 'per_page' => 9, 'total' => 1],
        ], 200)]);

        $response = $this->get('/sorotan');

        $response->assertOk();
        $response->assertSee('Kaca Film VLT 20%');
    }

    public function test_index_shows_an_empty_state_when_the_cms_is_unreachable(): void
    {
        Http::fake(['*/api/cms/catalog*' => Http::response([], 500)]);

        $response = $this->get('/sorotan');

        $response->assertOk();
        $response->assertSee('belum tersedia', false);
    }

    public function test_show_renders_the_body_and_spec_highlights(): void
    {
        Http::fake(['*/api/cms/catalog/kaca-film-20*' => Http::response(['data' => [
            'slug' => 'kaca-film-20', 'title' => 'Kaca Film VLT 20%', 'body' => '<p>Detail produk</p>',
            'published_at' => '2026-09-18T00:00:00+00:00', 'category' => 'Window Film',
            'spec_highlights' => [['label' => 'VLT', 'value' => '20%']],
            'media' => [],
        ]], 200)]);

        $response = $this->get('/sorotan/kaca-film-20');

        $response->assertOk();
        $response->assertSee('Detail produk', false);
        $response->assertSee('VLT');
        $response->assertSee('20%');
    }

    public function test_show_returns_404_when_the_cms_has_no_matching_item(): void
    {
        Http::fake(['*/api/cms/catalog/tidak-ada*' => Http::response(['message' => 'Item katalog tidak ditemukan.'], 404)]);

        $this->get('/sorotan/tidak-ada')->assertStatus(404);
    }
}
```

- [ ] **Step 2: Run the tests to verify they fail**

Run: `php artisan test --filter=SorotanPageTest`
Expected: FAIL — routes don't exist yet.

- [ ] **Step 3: Add the routes**

In `routes/web.php`, right after the `/artikel/{slug}` line:
```php
Route::get('/sorotan', [CmsController::class, 'catalog'])->name('sorotan.index');
Route::get('/sorotan/{slug}', [CmsController::class, 'catalogShow'])->name('sorotan.show');
```

- [ ] **Step 4: Write `resources/views/cms/catalog/index.blade.php`**

```blade
@extends('layouts.app')

@section('title', 'Sorotan Produk')
@section('meta_description', 'Sorotan produk dan promo dari GlossPro — Car Coating, Detailing, Window Film, dan PPF.')

@section('content')

    <section class="page-header">
        <div class="container">
            <span class="eyebrow">Sorotan</span>
            <h1 class="section-title">Sorotan Produk GlossPro</h1>
        </div>
    </section>

    <section style="padding-top: 0;">
        <div class="container">
            @if(empty($items))
                <p class="section-subtitle" style="text-align: center; margin: var(--space-4) auto;">Konten belum tersedia saat ini.</p>
            @else
                <div class="cms-grid">
                    @foreach($items as $item)
                        <a href="{{ route('sorotan.show', $item['slug']) }}" class="cms-card">
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
```

- [ ] **Step 5: Write `resources/views/cms/catalog/show.blade.php`**

```blade
@extends('layouts.app')

@section('title', $item['title'])
@section('meta_description', $item['excerpt'] ?? $item['title'])

@section('content')

    <section class="page-header">
        <div class="container">
            <a href="{{ route('sorotan.index') }}" class="back-link">&larr; Kembali ke Sorotan Produk</a>
            @if(!empty($item['category']))
                <span class="eyebrow">{{ $item['category'] }}</span>
            @endif
            <h1 class="section-title">{{ $item['title'] }}</h1>
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
```

- [ ] **Step 6: Add CSS for the spec-highlight rows**

Append to `public/css/style.css`, right after the `.cms-gallery-item img` rule:
```css
.cms-spec-list {
    display: grid;
    gap: 1px;
    background: var(--line);
    border: 1px solid var(--line);
    border-radius: var(--radius-sm);
    overflow: hidden;
    margin-bottom: var(--space-3);
}

.cms-spec-row {
    display: flex;
    justify-content: space-between;
    padding: var(--space-1) var(--space-2);
    background: var(--bg-alt);
}

.cms-spec-label {
    color: var(--text-muted);
}

.cms-spec-value {
    color: var(--text);
    font-weight: 600;
}
```

- [ ] **Step 7: Add nav and footer links**

In `resources/views/partials/navbar.blade.php`, right after the Artikel link added in Task 2:
```php
            <a href="{{ route('articles.index') }}" class="{{ request()->routeIs('articles.*') ? 'is-active' : '' }}">Artikel</a>
            <a href="{{ route('sorotan.index') }}" class="{{ request()->routeIs('sorotan.*') ? 'is-active' : '' }}">Sorotan Produk</a>
```
In `resources/views/partials/footer.blade.php`, right after the Artikel link added in Task 2:
```php
                <a href="{{ route('articles.index') }}">Artikel</a>
                <a href="{{ route('sorotan.index') }}">Sorotan Produk</a>
```

- [ ] **Step 8: Run the tests to verify they pass**

Run: `php artisan test --filter=SorotanPageTest`
Expected: PASS (4 tests).

- [ ] **Step 9: Commit**

```bash
git add routes/web.php resources/views/cms/catalog public/css/style.css resources/views/partials/navbar.blade.php resources/views/partials/footer.blade.php tests/Feature/SorotanPageTest.php
git commit -m "Add Sorotan Produk pages backed by the CMS API"
```

---

### Task 4: Portofolio — rewire `/portfolio` to the CMS + new `/portfolio/{slug}` detail

**Files:**
- Modify: `app/Http/Controllers/PageController.php`
- Modify: `routes/web.php`
- Modify: `resources/views/portfolio.blade.php`
- Create: `resources/views/portfolio-show.blade.php`
- Test: `tests/Feature/PortfolioPageTest.php`

**Interfaces:**
- Consumes: `CmsClient::portfolio()`/`::portfolioItem()` (Task 1), the shared `.cms-grid`/`.cms-card`/`.cms-pagination`/`.cms-gallery`/`.cms-article` CSS and lightbox JS (Task 2 — nothing new needed there).
- Produces: `portfolio` route (unchanged path/name, new data source), new `portfolio.show` route (`GET /portfolio/{slug}`).

- [ ] **Step 1: Write the failing tests**

```php
<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PortfolioPageTest extends TestCase
{
    public function test_index_renders_items_from_the_cms_instead_of_the_hardcoded_array(): void
    {
        Http::fake(['*/api/cms/portfolio*' => Http::response([
            'data' => [['slug' => 'sedan-eropa', 'title' => 'Graphene Coating — Sedan Eropa', 'excerpt' => 'Ringkasan', 'category' => 'Car Coating', 'published_at' => '2026-09-18T00:00:00+00:00', 'cover' => null]],
            'meta' => ['current_page' => 1, 'last_page' => 1, 'per_page' => 9, 'total' => 1],
        ], 200)]);

        $response = $this->get('/portfolio');

        $response->assertOk();
        $response->assertSee('Graphene Coating — Sedan Eropa');
        $response->assertDontSee('Foto Menyusul');
    }

    public function test_index_shows_an_empty_state_when_the_cms_is_unreachable(): void
    {
        Http::fake(['*/api/cms/portfolio*' => Http::response([], 500)]);

        $response = $this->get('/portfolio');

        $response->assertOk();
        $response->assertSee('belum tersedia', false);
    }

    public function test_show_renders_the_body_and_location(): void
    {
        Http::fake(['*/api/cms/portfolio/sedan-eropa*' => Http::response(['data' => [
            'slug' => 'sedan-eropa', 'title' => 'Graphene Coating — Sedan Eropa', 'body' => '<p>Detail proyek</p>',
            'published_at' => '2026-09-18T00:00:00+00:00', 'category' => 'Car Coating', 'location' => 'Jakarta',
            'media' => [],
        ]], 200)]);

        $response = $this->get('/portfolio/sedan-eropa');

        $response->assertOk();
        $response->assertSee('Detail proyek', false);
        $response->assertSee('Jakarta');
    }

    public function test_show_returns_404_when_the_cms_has_no_matching_item(): void
    {
        Http::fake(['*/api/cms/portfolio/tidak-ada*' => Http::response(['message' => 'Item portofolio tidak ditemukan.'], 404)]);

        $this->get('/portfolio/tidak-ada')->assertStatus(404);
    }
}
```

- [ ] **Step 2: Run the tests to verify they fail**

Run: `php artisan test --filter=PortfolioPageTest`
Expected: `test_index_renders_items_from_the_cms_instead_of_the_hardcoded_array` and the empty-state test FAIL (the old hardcoded-array `portfolio()` still renders `Foto Menyusul` and ignores `Http::fake()`); the two `show` tests FAIL with 404 (route doesn't exist yet).

- [ ] **Step 3: Modify `app/Http/Controllers/PageController.php`**

Add a constructor and the `CmsClient` import:
```php
use App\Services\CmsClient;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private CmsClient $cms;

    public function __construct(CmsClient $cms)
    {
        $this->cms = $cms;
    }

    // ...existing servicePillars()/heroSlides()/serviceVariantLineup()/testimonials()/brandPartners()/stats()/branchList() stay untouched...
```
Delete the `portfolioItems()` private method entirely (no longer used by anything).

Replace the existing `portfolio()` method:
```php
    public function portfolio()
    {
        return view('portfolio', [
            'items' => $this->portfolioItems(),
            'pillars' => array_values($this->servicePillars()),
        ]);
    }
```
with:
```php
    public function portfolio(Request $request)
    {
        $page = max(1, (int) $request->query('page', 1));
        $response = $this->cms->portfolio($page);

        return view('portfolio', [
            'items' => $response['data'] ?? [],
            'meta' => $response['meta'] ?? null,
        ]);
    }

    public function portfolioShow(string $slug)
    {
        $response = $this->cms->portfolioItem($slug);

        abort_if(!isset($response['data']), 404);

        return view('portfolio-show', ['item' => $response['data']]);
    }
```

- [ ] **Step 4: Add the new route**

In `routes/web.php`, change:
```php
Route::get('/portfolio', [PageController::class, 'portfolio'])->name('portfolio');
```
to:
```php
Route::get('/portfolio', [PageController::class, 'portfolio'])->name('portfolio');
Route::get('/portfolio/{slug}', [PageController::class, 'portfolioShow'])->name('portfolio.show');
```
(Route name `portfolio` is preserved exactly, so nothing elsewhere that links to `route('portfolio')` breaks.)

- [ ] **Step 5: Rewrite `resources/views/portfolio.blade.php`**

Replace the whole file:
```blade
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
            @if(empty($items))
                <p class="section-subtitle" style="text-align: center; margin: var(--space-4) auto;">Konten belum tersedia saat ini.</p>
            @else
                <div class="cms-grid">
                    @foreach($items as $item)
                        <a href="{{ route('portfolio.show', $item['slug']) }}" class="cms-card">
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
```
(The old `#portfolioFilter` pillar-filter buttons and their `@section('scripts')` JS block are removed entirely — see the Global Constraints note on why the filter isn't adapted.)

- [ ] **Step 6: Write `resources/views/portfolio-show.blade.php`**

```blade
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
```

- [ ] **Step 7: Run the tests to verify they pass**

Run: `php artisan test --filter=PortfolioPageTest`
Expected: PASS (4 tests).

- [ ] **Step 8: Run the full test suite**

Run: `php artisan test`
Expected: all tests pass, including `CmsClientTest`, `ArtikelPageTest`, `SorotanPageTest`, `PortfolioPageTest`, and the pre-existing scaffold tests.

- [ ] **Step 9: Manual smoke test — do this whole flow by hand in a browser**

Prerequisite: the `dashboard` app must be running (`php artisan serve` in that repo) with at least one **published** Artikel, one Katalog item, and one Portofolio item that all have `show_on_glosspro` checked (create them via the dashboard's CMS admin UI if none exist yet).

1. `php artisan serve` in `compro-1`, visit `/`.
2. Confirm the navbar now shows Portfolio → Artikel → Sorotan Produk, in that order, and the footer Sitemap column matches.
3. Visit `/artikel` — confirm the published article appears as a card with its excerpt; click into it, confirm the body renders, and if it has images, confirm the thumbnail strip appears and clicking a thumbnail opens the lightbox with prev/next arrows and Escape-to-close working.
4. Repeat step 3 for `/sorotan` — additionally confirm the `spec_highlights` rows render under the gallery.
5. Visit `/portfolio` — confirm it now shows real CMS items (not the old 8 hardcoded placeholder cards), confirm the "Foto Menyusul" placeholder text is gone, and click into an item to confirm the new detail page (including `location` if set).
6. Stop the `dashboard` server, then reload `/artikel`, `/sorotan`, and `/portfolio` — confirm each shows the "Konten belum tersedia saat ini." empty-state instead of an error page or blank screen. Restart `dashboard` afterward.
7. Confirm every other existing page (`/`, `/about`, `/layanan`, `/contact`, `/cek-garansi`) still renders with no visual regression — the lightbox partial is inert markup on pages with no `.cms-gallery-item`.

- [ ] **Step 10: Commit**

```bash
git add app/Http/Controllers/PageController.php routes/web.php resources/views/portfolio.blade.php resources/views/portfolio-show.blade.php tests/Feature/PortfolioPageTest.php
git commit -m "Rewire /portfolio to the CMS API and add a portfolio detail page"
```

---

## Self-Review Notes

- **Spec coverage:** `Http` facade server-side calls (never client-side `fetch()`) ✅ (Task 1); config-only `env()` access matching the existing `DASHBOARD_BASE_URL` precedent ✅ (Task 1); resilience (try/catch, empty-state, no crash) ✅ (Task 1 unit tests + every page test's "CMS unreachable" case); 5-minute cache ✅ (Task 1); `/artikel`, `/artikel/{slug}` ✅ (Task 2); `/sorotan`, `/sorotan/{slug}` with the deliberately-distinct "Sorotan Produk" label ✅ (Task 3); hand-rolled lightbox, no new JS dependency, matching the existing hero-slider/compare-slider vanilla-JS style ✅ (Task 2, reused by 3 and 4); `/portfolio` rewired in place (same URL/name) with old fields (`pillar`/`car_type`) dropped and the view redesigned around CMS fields, new `/portfolio/{slug}` detail ✅ (Task 4). `compro-2`'s equivalent integration is a separate plan per the 2026-09-18 scope decision.
- **Placeholder scan:** no TBD/TODO; every step has real, complete code.
- **Type consistency:** `CmsClient` method names/return shapes, the `CmsController` view-data keys (`items`, `meta`, `item`), and the Blade field accesses (`$item['slug']`, `$item['cover']['thumbnail_url']`, `$item['media']`, `$item['spec_highlights']`, `$item['location']`) match the exact API response shapes documented at the top of this plan and match the `dashboard` repo's actual `ArticleController`/`CatalogController`/`PortfolioController` (`app/Http/Controllers/Api/*.php`) output — checked against those files, not just the spec prose.
- **Resilience is tested, not just asserted:** every list/detail page test includes a "CMS returns 500 → empty-state, not a crash" or "CMS returns 404 → real 404" case, and `CmsClientTest` separately proves failures aren't cached (so a transient outage self-heals on the next request instead of serving a 5-minute-stale empty state).
