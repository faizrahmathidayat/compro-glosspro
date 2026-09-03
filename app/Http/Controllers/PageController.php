<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * The four LEXENT building window-film series, straight from the printed
     * building catalog. Copy and per-series technology are the catalog's own
     * wording.
     */
    private function seriesCatalog(): array
    {
        return [
            'BV' => [
                'code' => 'BV',
                'name' => 'LEXENT Black Vision',
                'label' => 'Black Vision',
                'accent' => 'accent-bv',
                'tagline' => 'Privasi Tinggi & Kontrol Panas',
                'description' => 'LEXENT Black Series hadir dengan teknologi kaca film yang dirancang untuk memberikan perlindungan optimal dari panas & sinar UV, sekaligus menghadirkan privasi tinggi dan kenyamanan pada bangunan Anda.',
                'attributes' => ['High Privacy', 'Low Haze', 'UV Protection'],
                'metrics' => [
                    ['value' => '99%', 'label' => 'UV Rejection'],
                    ['value' => '62%', 'label' => 'Heat Rejection'],
                    ['value' => '75%', 'label' => 'Infrared Rejection'],
                ],
            ],
            'RF' => [
                'code' => 'RF',
                'name' => 'LEXENT Reflective',
                'label' => 'Reflective Series',
                'accent' => 'accent-rf',
                'tagline' => 'Reflektif, Modern & Elegan',
                'description' => 'LEXENT Reflective Series menghadirkan solusi kaca film dengan karakter reflektif yang dirancang untuk meningkatkan perlindungan dari panas matahari, memberikan privasi yang lebih baik, serta menciptakan tampilan modern dan elegan pada bangunan Anda.',
                'attributes' => ['Karakter Reflektif', 'Privasi Lebih Baik', 'Tampilan Modern'],
                'metrics' => [
                    ['value' => '92%', 'label' => 'Infrared Rejection'],
                    ['value' => '55%', 'label' => 'Heat Rejection'],
                    ['value' => '90%', 'label' => 'UV Rejection'],
                ],
            ],
            'HP' => [
                'code' => 'HP',
                'name' => 'LEXENT High Performance',
                'label' => 'High Performance',
                'accent' => 'accent-hp',
                'tagline' => 'Ultra HD Nano Ceramic',
                'description' => 'LEXENT High Performance hadir dengan teknologi Ultra HD Nano Ceramic terbaru yang dirancang untuk memberikan perlindungan optimal dari panas & sinar UV, dengan kejernihan tinggi untuk menghadirkan kenyamanan dan visibilitas yang lebih baik.',
                'attributes' => ['Ultra HD Clarity', 'High Visibility', 'UV Protection'],
                'metrics' => [
                    ['value' => '99%', 'label' => 'UV Rejection'],
                    ['value' => '72%', 'label' => 'Heat Rejection'],
                    ['value' => '90%', 'label' => 'Infrared Rejection'],
                ],
            ],
            'UP' => [
                'code' => 'UP',
                'name' => 'LEXENT Ultra Protect',
                'label' => 'Ultra Protect',
                'accent' => 'accent-up',
                'tagline' => 'Sputter Magnetron',
                'description' => 'LEXENT Ultra Protect hadir dengan teknologi Sputter Magnetron yang dirancang untuk memberikan perlindungan optimal dari panas dan sinar UV, sekaligus membantu mengurangi paparan sinar matahari dan meningkatkan kenyamanan serta privasi pada bangunan Anda.',
                'attributes' => ['Maximum Heat Protection', 'IR 99% Protection', 'Enhanced Privacy', 'High Visibility Clarity'],
                'metrics' => [
                    ['value' => '99%', 'label' => 'UV Rejection'],
                    ['value' => '99%', 'label' => 'Infrared Rejection'],
                    ['value' => '76%', 'label' => 'Heat Rejection'],
                ],
            ],
        ];
    }

    /**
     * Flat list of every VLT variant across all four series, with the exact
     * specification figures from the building catalog.
     */
    private function productLineup(): array
    {
        $series = $this->seriesCatalog();

        // [ series, number, vlt, vlr, tser, uv, irr, thickness ]
        $rows = [
            ['BV', '05', '5%', '8%', '62%', '99%', '75%', '1,8 mil'],
            ['BV', '18', '5%', '8%', '58%', '99%', '65%', '1,8 mil'],
            ['BV', '35', '5%', '8%', '53%', '99%', '63%', '1,8 mil'],

            ['RF', '05', '5%', '8%', '55%', '90%', '92%', '2 mil'],

            ['HP', '08', '8%', '5%', '72%', '99%', '90%', '2 mil'],
            ['HP', '15', '15%', '5%', '70%', '99%', '90%', '2 mil'],
            ['HP', '35', '35%', '5%', '71%', '99%', '90%', '2 mil'],
            ['HP', '70', '70%', '5%', '71%', '99%', '90%', '2 mil'],

            ['UP', '08', '8%', '6%', '76%', '99%', '99%', '2 mil'],
            ['UP', '20', '20%', '6%', '74%', '99%', '99%', '2 mil'],
            ['UP', '30', '28%', '6%', '73%', '99%', '99%', '2 mil'],
            ['UP', '50', '47%', '6%', '73%', '99%', '99%', '2 mil'],
            ['UP', '65', '58%', '6%', '72%', '99%', '99%', '2 mil'],
            ['UP', '75', '69%', '6%', '72%', '99%', '99%', '2 mil'],
        ];

        $lineup = [];

        foreach ($rows as [$code, $number, $vlt, $vlr, $tser, $uv, $irr, $thickness]) {
            $meta = $series[$code];
            $vltNumber = (int) $vlt;

            if ($vltNumber <= 15) {
                $darkness = 'Sangat gelap — privasi maksimal';
            } elseif ($vltNumber <= 40) {
                $darkness = 'Gelap sedang — seimbang';
            } else {
                $darkness = 'Terang — cahaya alami maksimal';
            }

            $lineup[] = [
                'slug' => strtolower($code) . '-' . $number,
                'name' => $meta['name'] . ' ' . $number,
                'series' => $code,
                'series_label' => $meta['label'],
                'number' => $number,
                'accent' => $meta['accent'],
                'badge' => $meta['attributes'][0],
                'tagline' => $meta['tagline'],
                'series_description' => $meta['description'],
                'darkness' => $darkness,
                'attributes' => $meta['attributes'],
                'vlt' => $vlt,
                'vlr' => $vlr,
                'tser' => $tser,
                'uv' => $uv,
                'irr' => $irr,
                'thickness' => $thickness,
            ];
        }

        return $lineup;
    }

    /**
     * Static official gallery / branch listing.
     */
    private function dealerList(): array
    {
        return [
            [
                'name' => 'LEXENT Gallery Jakarta Pusat',
                'city' => 'Jakarta',
                'address' => 'Jl. Jenderal Sudirman No. 45, Jakarta Pusat',
                'phone' => '(021) 555-0142',
                'maps_url' => 'https://maps.google.com/?q=Jl.+Jenderal+Sudirman+No.+45+Jakarta+Pusat',
            ],
            [
                'name' => 'LEXENT Gallery Bandung',
                'city' => 'Bandung',
                'address' => 'Jl. Ir. H. Djuanda No. 88, Bandung',
                'phone' => '(022) 555-0198',
                'maps_url' => 'https://maps.google.com/?q=Jl.+Ir.+H.+Djuanda+No.+88+Bandung',
            ],
            [
                'name' => 'LEXENT Gallery Surabaya',
                'city' => 'Surabaya',
                'address' => 'Jl. HR. Muhammad No. 12, Surabaya',
                'phone' => '(031) 555-0176',
                'maps_url' => 'https://maps.google.com/?q=Jl.+HR.+Muhammad+No.+12+Surabaya',
            ],
            [
                'name' => 'LEXENT Gallery Denpasar',
                'city' => 'Denpasar',
                'address' => 'Jl. Sunset Road No. 21, Denpasar',
                'phone' => '(0361) 555-0133',
                'maps_url' => 'https://maps.google.com/?q=Jl.+Sunset+Road+No.+21+Denpasar',
            ],
        ];
    }

    public function home()
    {
        return view('home', [
            'series' => array_values($this->seriesCatalog()),
            'products' => $this->productLineup(),
            'dealers' => $this->dealerList(),
        ]);
    }

    public function about()
    {
        return view('about');
    }

    public function products()
    {
        return view('products.index', [
            'series' => array_values($this->seriesCatalog()),
            'products' => $this->productLineup(),
        ]);
    }

    public function productDetail(string $slug)
    {
        $product = collect($this->productLineup())->firstWhere('slug', $slug);

        abort_if(!$product, 404);

        $related = collect($this->productLineup())
            ->where('series', $product['series'])
            ->where('slug', '!=', $product['slug'])
            ->values()
            ->all();

        return view('products.show', [
            'product' => $product,
            'related' => $related,
        ]);
    }

    public function dealers()
    {
        return view('dealers', [
            'dealers' => $this->dealerList(),
        ]);
    }

    public function cekGaransi()
    {
        return view('cek-garansi');
    }
}
