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
