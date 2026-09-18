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
