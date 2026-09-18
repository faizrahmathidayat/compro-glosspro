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
