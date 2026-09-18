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
