<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Static product lineup shared across the home, products, and detail pages.
     */
    private function productLineup(): array
    {
        return [
            [
                'slug' => 'ultimate',
                'name' => 'Glosspro Ultimate',
                'tagline' => 'Puncak Proteksi & Privasi',
                'short_description' => 'Kaca film paling gelap dan paling protektif di lini Glosspro, dirancang untuk kenyamanan dan privasi maksimal.',
                'description' => 'Glosspro Ultimate adalah jawaban untuk Anda yang menginginkan privasi penuh tanpa mengorbankan performa optik. Dilapisi teknologi Heat Reflection Technology berlapis ganda, varian ini menahan hampir seluruh radiasi inframerah sebelum menembus kabin, menjaga interior tetap sejuk sekaligus melindungi kulit dan material dari paparan sinar UV berkepanjangan.',
                'vlt' => '5%',
                'heat_rejection' => '98%',
                'irr' => '99%',
                'badge' => 'Signature Dark',
                'accent' => 'from-obsidian',
                'features' => [
                    'Privasi kabin maksimal, cocok untuk kendaraan eksekutif',
                    'Menahan hingga 99% sinar infrared (IRR)',
                    'Lapisan anti gores (scratch resistant) premium',
                    'Garansi resmi Glosspro hingga 10 tahun',
                ],
            ],
            [
                'slug' => 'signature',
                'name' => 'Glosspro Signature',
                'tagline' => 'Keseimbangan Sempurna Estetika & Performa',
                'short_description' => 'Varian paling populer - menyeimbangkan kejernihan visual dengan penolakan panas kelas atas.',
                'description' => 'Glosspro Signature dirancang untuk pemilik kendaraan yang menginginkan tampilan elegan tanpa terlihat terlalu gelap, namun tetap mendapatkan penolakan panas setara varian premium. Teknologi nano-ceramic pada lapisannya menjaga kejernihan pandangan di malam hari sekaligus menahan panas matahari di siang hari.',
                'vlt' => '20%',
                'heat_rejection' => '90%',
                'irr' => '95%',
                'badge' => 'Best Seller',
                'accent' => 'from-gold',
                'features' => [
                    'Visibilitas malam hari tetap optimal',
                    'Penolakan panas hingga 90%',
                    'Tampilan metallic satin yang elegan',
                    'Garansi resmi Glosspro hingga 8 tahun',
                ],
            ],
            [
                'slug' => 'eco-shield',
                'name' => 'Glosspro Eco Shield',
                'tagline' => 'Terang, Ringan, Tetap Terlindungi',
                'short_description' => 'Pilihan tepat untuk kendaraan niaga dan keluarga yang mengutamakan visibilitas tinggi.',
                'description' => 'Glosspro Eco Shield hadir dengan tingkat kegelapan paling rendah di lini produk kami, ideal untuk kendaraan yang membutuhkan visibilitas tinggi seperti mobil keluarga dan armada niaga, tanpa mengorbankan perlindungan dari panas dan sinar UV berbahaya.',
                'vlt' => '40%',
                'heat_rejection' => '85%',
                'irr' => '90%',
                'badge' => 'High Visibility',
                'accent' => 'from-metallic',
                'features' => [
                    'Visibilitas terbaik untuk berkendara malam & siang',
                    'Tetap menahan 85% panas matahari',
                    'Ideal untuk armada dan kendaraan keluarga',
                    'Garansi resmi Glosspro hingga 5 tahun',
                ],
            ],
        ];
    }

    /**
     * Static official dealer / branch listing.
     */
    private function dealerList(): array
    {
        return [
            [
                'name' => 'Glosspro Gallery Jakarta Pusat',
                'city' => 'Jakarta',
                'address' => 'Jl. Jenderal Sudirman No. 45, Jakarta Pusat',
                'phone' => '(021) 555-0142',
                'maps_url' => 'https://maps.google.com/?q=Jl.+Jenderal+Sudirman+No.+45+Jakarta+Pusat',
            ],
            [
                'name' => 'Glosspro Gallery Bandung',
                'city' => 'Bandung',
                'address' => 'Jl. Ir. H. Djuanda No. 88, Bandung',
                'phone' => '(022) 555-0198',
                'maps_url' => 'https://maps.google.com/?q=Jl.+Ir.+H.+Djuanda+No.+88+Bandung',
            ],
            [
                'name' => 'Glosspro Gallery Surabaya',
                'city' => 'Surabaya',
                'address' => 'Jl. HR. Muhammad No. 12, Surabaya',
                'phone' => '(031) 555-0176',
                'maps_url' => 'https://maps.google.com/?q=Jl.+HR.+Muhammad+No.+12+Surabaya',
            ],
            [
                'name' => 'Glosspro Gallery Denpasar',
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
            'products' => $this->productLineup(),
        ]);
    }

    public function productDetail(string $slug)
    {
        $product = collect($this->productLineup())->firstWhere('slug', $slug);

        abort_if(!$product, 404);

        return view('products.show', [
            'product' => $product,
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
