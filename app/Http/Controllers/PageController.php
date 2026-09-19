<?php

namespace App\Http\Controllers;

use App\Services\CmsClient;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private CmsClient $cms;

    public function __construct(CmsClient $cms)
    {
        $this->cms = $cms;
    }

    /**
     * The four GlossPro service pillars. Single source of truth shared by
     * the navbar mega-menu, homepage showcase, and services index.
     */
    private function servicePillars(): array
    {
        return [
            'car-coating' => [
                'slug' => 'car-coating',
                'code' => 'CC',
                'icon' => '&#10022;',
                'name' => 'Car Coating',
                'tagline' => 'Nano Ceramic & Graphene',
                'description' => 'Lapisan pelindung cat nano ceramic dan graphene yang mengunci kilau, menahan goresan halus, dan membuat kendaraan Anda mudah dibersihkan bertahun-tahun.',
                'attributes' => ['9H Hardness', 'Hydrophobic', 'UV & Oxidation Protection'],
                'metrics' => [
                    ['label' => 'Hardness', 'value' => '9H'],
                    ['label' => 'Durability', 'value' => 's/d 7 Thn'],
                    ['label' => 'Gloss Boost', 'value' => 'Tinggi'],
                ],
                'process' => [
                    'Inspeksi cat & konsultasi pilihan lapisan (Nano Ceramic / Graphene) sesuai kondisi kendaraan.',
                    'Decontamination — clay bar dan iron remover untuk mengangkat kontaminan yang menempel di cat.',
                    'Paint correction ringan untuk menghaluskan permukaan sebelum lapisan diaplikasikan.',
                    'Aplikasi coating lapis-demi-lapis di ruang bebas debu, lalu curing sesuai waktu kering produk.',
                    'Quality check kilau & ketebalan lapisan, serah terima dengan panduan perawatan & kartu garansi.',
                ],
                'faq' => [
                    ['q' => 'Berapa lama coating bertahan?', 'a' => 'Nano Ceramic bertahan 2-3 tahun, Graphene hingga 5-7 tahun tergantung perawatan dan kondisi pemakaian harian.'],
                    ['q' => 'Apakah coating anti gores total?', 'a' => 'Coating menambah lapisan pelindung 9H yang menahan swirl mark dan goresan halus akibat pencucian, bukan proteksi benturan/gores dalam seperti PPF.'],
                ],
            ],
            'detailing' => [
                'slug' => 'detailing',
                'code' => 'DT',
                'icon' => '&#10038;',
                'name' => 'Detailing',
                'tagline' => 'Interior, Exterior, Engine & Glass',
                'description' => 'Perawatan menyeluruh dari kabin, mesin, hingga kaca — mengembalikan kendaraan ke kondisi sedetail mungkin dari pabrik, bahkan lebih baik.',
                'attributes' => ['Steam Sanitizing', 'Machine Polish', 'Ozone Treatment'],
                'metrics' => [
                    ['label' => 'Paket', 'value' => '4 Area'],
                    ['label' => 'Durasi', 'value' => '3-8 Jam'],
                    ['label' => 'Hasil', 'value' => 'Showroom'],
                ],
                'process' => [
                    'Asesmen kondisi kabin, cat, mesin, dan kaca untuk menentukan paket yang sesuai.',
                    'Exterior wash dua tahap (pre-wash + contact wash) bebas swirl, dilanjut clay bar bila perlu.',
                    'Machine polish untuk exterior, steam sanitizing & pembersihan sela untuk interior.',
                    'Engine bay degreasing dan dressing, glass detailing dengan hydrophobic coating tipis.',
                    'Inspeksi akhir seluruh area sebelum kendaraan diserahkan kembali.',
                ],
                'faq' => [
                    ['q' => 'Berapa lama proses detailing?', 'a' => 'Tergantung paket, mulai dari 3 jam untuk quick detail hingga 8 jam untuk paket lengkap interior + exterior + mesin.'],
                    ['q' => 'Apakah detailing menghilangkan bau apek di kabin?', 'a' => 'Ya, paket interior mencakup steam sanitizing dan ozone treatment yang menetralkan bau serta membunuh bakteri di jok dan karpet.'],
                ],
            ],
            'window-film' => [
                'slug' => 'window-film',
                'code' => 'WF',
                'icon' => '&#9635;',
                'name' => 'Window Film',
                'tagline' => 'Kaca Film Ceramic & Carbon',
                'description' => 'Kaca film performa tinggi yang menahan panas dan silau tanpa mengorbankan kejernihan pandangan, dengan pilihan tingkat kegelapan sesuai regulasi dan selera.',
                'attributes' => ['UV Rejection 99%', 'IR Rejection Tinggi', 'Anti Silau'],
                'metrics' => [
                    ['label' => 'UV Rejection', 'value' => '99%'],
                    ['label' => 'IR Rejection', 'value' => 's/d 95%'],
                    ['label' => 'Garansi', 'value' => 's/d 9 Thn'],
                ],
                'process' => [
                    'Konsultasi tingkat kegelapan (VLT) per posisi kaca sesuai kebutuhan & regulasi lalu lintas.',
                    'Pembersihan menyeluruh permukaan kaca dari debu dan residu sebelum pemasangan.',
                    'Pemotongan film presisi mengikuti pola kaca kendaraan (bukan tempel-potong langsung di kaca).',
                    'Pemasangan dengan larutan aplikasi khusus untuk hasil tanpa gelembung.',
                    'Masa curing beberapa hari sebelum kaca boleh dibuka/dicuci, sesuai instruksi teknisi.',
                ],
                'faq' => [
                    ['q' => 'VLT berapa yang legal untuk kaca depan?', 'a' => 'Regulasi bervariasi per daerah; tim kami membantu merekomendasikan VLT yang sesuai ketentuan sekaligus kebutuhan kenyamanan Anda.'],
                    ['q' => 'Apakah window film mengurangi silau saat malam hari?', 'a' => 'Ya, terutama seri ceramic yang meredam silau lampu dari arah berlawanan tanpa mengurangi visibilitas pengemudi.'],
                ],
            ],
            'ppf' => [
                'slug' => 'ppf',
                'code' => 'PPF',
                'icon' => '&#9906;',
                'name' => 'Paint Protection Film',
                'tagline' => 'Self-Healing TPU Film',
                'description' => 'Lapisan film TPU self-healing yang menahan baret halus, kerikil, dan chip di jalan tol — melindungi cat asli sekaligus mempertahankan nilai jual kembali kendaraan.',
                'attributes' => ['Self-Healing', 'Anti Chipping', 'Gloss / Matte / Satin'],
                'metrics' => [
                    ['label' => 'Ketebalan', 'value' => '150-200 mic'],
                    ['label' => 'Self-Healing', 'value' => 'Ya'],
                    ['label' => 'Garansi', 'value' => 's/d 10 Thn'],
                ],
                'process' => [
                    'Konsultasi cakupan (full body / partial) dan pilihan finish (Gloss, Matte, atau Satin).',
                    'Paint correction ringan agar film menempel sempurna tanpa menjebak kontaminan.',
                    'Pemotongan film mengikuti pola digital presisi per panel bodi kendaraan.',
                    'Instalasi basah panel demi panel dengan heat gun untuk kontur lekukan bodi.',
                    'Trimming detail di tepi panel, inspeksi akhir, dan edukasi perawatan self-healing film.',
                ],
                'faq' => [
                    ['q' => 'Apa itu self-healing pada PPF?', 'a' => 'Lapisan TPU memiliki elastomer yang "menutup" baret halus dengan bantuan panas (sinar matahari/air hangat) sehingga permukaan kembali mulus.'],
                    ['q' => 'PPF Matte bisa diubah kembali ke Gloss?', 'a' => 'Bisa — PPF Matte dapat dilepas kapan saja tanpa merusak cat asli di baliknya, karena film bekerja sebagai lapisan terpisah.'],
                ],
            ],
        ];
    }

    /**
     * Homepage hero slider — one slide per service pillar, each pointing at
     * that pillar's first catalog variant (matches the `<slug>-01` pattern
     * used by servicePillars()/serviceVariantLineup()).
     */
    private function heroSlides(): array
    {
        return [
            [
                'image' => 'images/hero/hero-01-coating.jpg',
                'alt' => 'Mobil sedan hitam mengkilap melaju di jalan raya saat senja',
                'eyebrow' => 'Nano Ceramic & Graphene Coating',
                'title' => 'Kilau yang Bertahan.',
                'title_highlight' => 'Proteksi yang Teruji.',
                'subtext' => 'Coating hardness hingga 9H membentuk lapisan pelindung transparan yang membuat cat mobil Anda tetap mengkilap dan tahan gores lebih lama.',
                'cta_label' => 'Lihat Paket Coating',
                'cta_url' => route('services.show', 'car-coating-01'),
                'stats' => [
                    ['value' => '9H', 'label' => 'Coating Hardness'],
                    ['value' => '3-5 Thn', 'label' => 'Garansi Coating'],
                    ['value' => '10 Thn', 'label' => 'Garansi Tertinggi'],
                ],
            ],
            [
                'image' => 'images/hero/hero-02-detailing.jpg',
                'alt' => 'Siluet mobil di dalam bay detailing tertutup dengan lampu menyala',
                'eyebrow' => 'Detailing Menyeluruh',
                'title' => 'Perawatan Presisi.',
                'title_highlight' => 'Hasil Showroom.',
                'subtext' => 'Dikerjakan di ruang kerja bebas debu oleh installer bersertifikat — dari paint correction hingga interior detailing, tuntas dalam satu kunjungan.',
                'cta_label' => 'Lihat Paket Detailing',
                'cta_url' => route('services.show', 'detailing-01'),
                'stats' => [
                    ['value' => '100%', 'label' => 'Bay Tertutup'],
                    ['value' => 'Bersertifikat', 'label' => 'Installer'],
                    ['value' => '10 Thn', 'label' => 'Garansi Tertinggi'],
                ],
            ],
            [
                'image' => 'images/hero/hero-03-window-film.jpg',
                'alt' => 'Kaca jendela mobil dengan pantulan cahaya kota saat senja',
                'eyebrow' => 'Window Film Premium',
                'title' => 'Sejuk di Dalam.',
                'title_highlight' => 'Elegan di Luar.',
                'subtext' => 'Menolak panas dan sinar UV secara signifikan tanpa mengorbankan kejernihan pandangan — kenyamanan berkendara yang terasa sejak menit pertama.',
                'cta_label' => 'Lihat Paket Window Film',
                'cta_url' => route('services.show', 'window-film-01'),
                'stats' => [
                    ['value' => '99%', 'label' => 'Penolakan UV'],
                    ['value' => 'IR Reject', 'label' => 'Teknologi Nano'],
                    ['value' => '10 Thn', 'label' => 'Garansi Tertinggi'],
                ],
            ],
            [
                'image' => 'images/hero/hero-04-ppf.jpg',
                'alt' => 'Instalasi paint protection film pada bodi mobil oleh teknisi',
                'eyebrow' => 'Paint Protection Film',
                'title' => 'Lindungi Cat Asli.',
                'title_highlight' => 'Sebelum Tergores.',
                'subtext' => 'Film TPU self-healing menahan baret halus, kerikil, dan chip di jalan tol — menjaga cat orisinal sekaligus nilai jual kembali kendaraan Anda.',
                'cta_label' => 'Lihat Paket PPF',
                'cta_url' => route('services.show', 'ppf-01'),
                'stats' => [
                    ['value' => '150-200 mic', 'label' => 'PPF Thickness'],
                    ['value' => 'Self-Healing', 'label' => 'Teknologi Film'],
                    ['value' => '10 Thn', 'label' => 'Garansi Tertinggi'],
                ],
            ],
        ];
    }

    /**
     * Flat catalog of every variant/package across all four pillars — the
     * automotive equivalent of the old building-film VLT lineup.
     */
    private function serviceVariantLineup(): array
    {
        $pillars = $this->servicePillars();

        $rows = [
            // pillar, code-number, name, tagline, badge, description, specs
            ['car-coating', '01', 'Nano Ceramic Coating', 'Kilau Maksimal, Proteksi Harian', '9H Hardness',
                'Lapisan ceramic dasar dengan kejernihan tinggi dan efek hydrophobic untuk pemakaian harian.',
                [['label' => 'Hardness', 'value' => '9H'], ['label' => 'Hydrophobic Angle', 'value' => '100°'], ['label' => 'Durability', 'value' => '2-3 Thn'], ['label' => 'Gloss Enhancement', 'value' => 'Tinggi']]],
            ['car-coating', '02', 'Nano Ceramic HD', 'Ultra Clarity, Proteksi Menengah', '9H Hardness',
                'Formula HD dengan kejernihan optik lebih tinggi, cocok untuk warna cat solid maupun metalik.',
                [['label' => 'Hardness', 'value' => '9H'], ['label' => 'Hydrophobic Angle', 'value' => '105°'], ['label' => 'Durability', 'value' => '3-5 Thn'], ['label' => 'Gloss Enhancement', 'value' => 'Sangat Tinggi']]],
            ['car-coating', '03', 'Graphene Coating', 'Proteksi Maksimal, Tahan Panas', 'Self-Healing Swirl',
                'Struktur graphene menambah resistansi panas dan sifat anti-statis sehingga debu lebih sulit menempel.',
                [['label' => 'Hardness', 'value' => '9H+'], ['label' => 'Hydrophobic Angle', 'value' => '>110°'], ['label' => 'Durability', 'value' => '5-7 Thn'], ['label' => 'Thermal Resistance', 'value' => 'Tinggi']]],

            ['detailing', '01', 'Exterior Detailing', 'Clay Bar, Machine Polish, Swirl Removal', 'Showroom Finish',
                'Decontamination, koreksi cat ringan-menengah, dan proteksi wax/sealant untuk tampilan luar mengkilap.',
                [['label' => 'Durasi', 'value' => '3-5 Jam'], ['label' => 'Termasuk', 'value' => 'Clay + Polish'], ['label' => 'Cocok Untuk', 'value' => 'Perawatan Berkala']]],
            ['detailing', '02', 'Interior Detailing', 'Steam Sanitizing & Leather Conditioning', 'Kabin Higienis',
                'Pembersihan dalam kabin, jok, karpet, hingga dashboard, dengan sanitasi uap dan ozone treatment.',
                [['label' => 'Durasi', 'value' => '3-4 Jam'], ['label' => 'Termasuk', 'value' => 'Steam + Ozone'], ['label' => 'Cocok Untuk', 'value' => 'Kabin Berbau/Kotor']]],
            ['detailing', '03', 'Engine Bay Detailing', 'Degreasing & Dressing', 'Ruang Mesin Bersih',
                'Pembersihan ruang mesin dari debu dan oli membandel, dilanjut dressing anti debu.',
                [['label' => 'Durasi', 'value' => '1-2 Jam'], ['label' => 'Termasuk', 'value' => 'Degrease + Dress'], ['label' => 'Cocok Untuk', 'value' => 'Sebelum Servis/Jual']]],
            ['detailing', '04', 'Glass Detailing', 'Water Spot Removal & Hydrophobic Coat', 'Kaca Sejernih Kristal',
                'Menghilangkan water spot dan baret halus di kaca, ditutup lapisan hydrophobic tipis.',
                [['label' => 'Durasi', 'value' => '1-2 Jam'], ['label' => 'Termasuk', 'value' => 'Polish + Coat'], ['label' => 'Cocok Untuk', 'value' => 'Kaca Buram/Berkerak']]],

            ['window-film', '01', 'Ceramic Film VLT 5%', 'Privasi Maksimal', 'Sangat Gelap',
                'Tingkat kegelapan tertinggi untuk privasi maksimal pada kaca belakang dan samping belakang.',
                [['label' => 'VLT', 'value' => '5%'], ['label' => 'UV Rejection', 'value' => '99%'], ['label' => 'IR Rejection', 'value' => '95%'], ['label' => 'Tipe', 'value' => 'Ceramic']]],
            ['window-film', '02', 'Ceramic Film VLT 20%', 'Gelap Seimbang', 'Gelap Sedang',
                'Kombinasi privasi dan visibilitas yang seimbang, favorit untuk kaca samping depan.',
                [['label' => 'VLT', 'value' => '20%'], ['label' => 'UV Rejection', 'value' => '99%'], ['label' => 'IR Rejection', 'value' => '93%'], ['label' => 'Tipe', 'value' => 'Ceramic']]],
            ['window-film', '03', 'Carbon Film VLT 40%', 'Terang & Anti Silau', 'Terang',
                'Karakter carbon yang stabil warnanya (tidak memudar/berubah ungu) dengan VLT lebih terang.',
                [['label' => 'VLT', 'value' => '40%'], ['label' => 'UV Rejection', 'value' => '99%'], ['label' => 'IR Rejection', 'value' => '85%'], ['label' => 'Tipe', 'value' => 'Carbon']]],
            ['window-film', '04', 'Ceramic Film VLT 60%', 'Cahaya Alami Maksimal', 'Sangat Terang',
                'VLT tinggi untuk kaca depan — proteksi UV & panas tanpa mengurangi cahaya alami secara signifikan.',
                [['label' => 'VLT', 'value' => '60%'], ['label' => 'UV Rejection', 'value' => '99%'], ['label' => 'IR Rejection', 'value' => '80%'], ['label' => 'Tipe', 'value' => 'Ceramic']]],

            ['ppf', '01', 'PPF Full Body Gloss', 'Proteksi Total, Kilau Natural', 'Full Body',
                'Menutupi seluruh panel bodi dengan finish gloss yang mempertahankan tampilan cat asli.',
                [['label' => 'Cakupan', 'value' => 'Full Body'], ['label' => 'Finish', 'value' => 'Gloss'], ['label' => 'Ketebalan', 'value' => '180 mic'], ['label' => 'Garansi', 'value' => '10 Thn']]],
            ['ppf', '02', 'PPF Full Body Matte', 'Proteksi Total, Tampilan Doff', 'Full Body',
                'Mengubah tampilan kendaraan menjadi matte sekaligus melindungi cat asli di baliknya.',
                [['label' => 'Cakupan', 'value' => 'Full Body'], ['label' => 'Finish', 'value' => 'Matte'], ['label' => 'Ketebalan', 'value' => '180 mic'], ['label' => 'Garansi', 'value' => '10 Thn']]],
            ['ppf', '03', 'PPF Partial Front Kit', 'Proteksi Area Rawan Chip', 'Partial',
                'Melindungi bumper depan, kap mesin, fender, dan spion — area paling rawan baret kerikil.',
                [['label' => 'Cakupan', 'value' => 'Bumper, Hood, Fender'], ['label' => 'Finish', 'value' => 'Gloss/Matte'], ['label' => 'Ketebalan', 'value' => '150 mic'], ['label' => 'Garansi', 'value' => '7 Thn']]],
            ['ppf', '04', 'PPF Satin Full Body', 'Efek Semi-Doff Premium', 'Full Body',
                'Finish satin di antara gloss dan matte, memberi kesan premium yang lebih jarang ditemui.',
                [['label' => 'Cakupan', 'value' => 'Full Body'], ['label' => 'Finish', 'value' => 'Satin'], ['label' => 'Ketebalan', 'value' => '180 mic'], ['label' => 'Garansi', 'value' => '10 Thn']]],
        ];

        $lineup = [];

        foreach ($rows as [$pillarSlug, $number, $name, $tagline, $badge, $description, $specs]) {
            $pillar = $pillars[$pillarSlug];

            $lineup[] = [
                'slug' => $pillarSlug . '-' . $number,
                'name' => $name,
                'pillar' => $pillarSlug,
                'pillar_label' => $pillar['name'],
                'number' => $number,
                'code' => $pillar['code'],
                'icon' => $pillar['icon'],
                'badge' => $badge,
                'tagline' => $tagline,
                'pillar_description' => $pillar['description'],
                'description' => $description,
                'attributes' => $pillar['attributes'],
                'specs' => $specs,
                'process' => $pillar['process'],
                'faq' => $pillar['faq'],
            ];
        }

        return $lineup;
    }

    /**
     * Placeholder testimonials — replace with real customer quotes before
     * the site goes live.
     */
    private function testimonials(): array
    {
        return [
            ['quote' => 'Hasil coating-nya jauh melebihi ekspektasi, cat mobil jadi terlihat seperti baru keluar showroom. Prosesnya juga dijelaskan detail dari awal.', 'author' => 'Kevin S.', 'meta' => 'Pemilik Honda Civic'],
            ['quote' => 'Pasang PPF full body di sini karena banyak direkomendasikan. Hasil potongannya rapi banget di lekukan bodi, hampir tidak terlihat sambungannya.', 'author' => 'Amanda R.', 'meta' => 'Pemilik Mazda CX-5'],
            ['quote' => 'Kabin mobil yang tadinya bau apek jadi wangi dan bersih total setelah interior detailing. Timnya juga on-time sesuai janji.', 'author' => 'Deni P.', 'meta' => 'Pemilik Toyota Avanza'],
        ];
    }

    /**
     * Material/brand wordmarks shown in the "Brand Partners" strip.
     * Placeholder generic labels — swap for real supplier logos once
     * confirmed partnerships exist.
     */
    private function brandPartners(): array
    {
        return ['Nano Ceramic Certified', 'Graphene Tech', 'TPU Film Grade-A', 'IR-Cut Ceramic Film'];
    }

    private function stats(): array
    {
        return [
            ['value' => '5.000+', 'label' => 'Kendaraan Ditangani'],
            ['value' => '9 Thn', 'label' => 'Garansi Tertinggi'],
            ['value' => '4', 'label' => 'Cabang Workshop'],
            ['value' => '4.9/5', 'label' => 'Rating Pelanggan'],
        ];
    }

    /**
     * GlossPro's own workshop branches (not third-party dealers).
     */
    private function branchList(): array
    {
        return [
            [
                'name' => 'GlossPro Workshop Jakarta Pusat',
                'city' => 'Jakarta',
                'address' => 'Jl. Jenderal Sudirman No. 45, Jakarta Pusat',
                'phone' => '0858-8889-9558',
                'whatsapp' => '6285888899558',
                'maps_url' => 'https://maps.google.com/?q=Jl.+Jenderal+Sudirman+No.+45+Jakarta+Pusat',
            ],
            [
                'name' => 'GlossPro Workshop Bandung',
                'city' => 'Bandung',
                'address' => 'Jl. Ir. H. Djuanda No. 88, Bandung',
                'phone' => '(022) 555-0198',
                'whatsapp' => '6285888899558',
                'maps_url' => 'https://maps.google.com/?q=Jl.+Ir.+H.+Djuanda+No.+88+Bandung',
            ],
            [
                'name' => 'GlossPro Workshop Surabaya',
                'city' => 'Surabaya',
                'address' => 'Jl. HR. Muhammad No. 12, Surabaya',
                'phone' => '(031) 555-0176',
                'whatsapp' => '6285888899558',
                'maps_url' => 'https://maps.google.com/?q=Jl.+HR.+Muhammad+No.+12+Surabaya',
            ],
            [
                'name' => 'GlossPro Workshop Denpasar',
                'city' => 'Denpasar',
                'address' => 'Jl. Sunset Road No. 21, Denpasar',
                'phone' => '(0361) 555-0133',
                'whatsapp' => '6285888899558',
                'maps_url' => 'https://maps.google.com/?q=Jl.+Sunset+Road+No.+21+Denpasar',
            ],
        ];
    }

    public function home()
    {
        return view('home', [
            'heroSlides' => $this->heroSlides(),
            'pillars' => array_values($this->servicePillars()),
            'stats' => $this->stats(),
            'testimonials' => $this->testimonials(),
            'partners' => $this->brandPartners(),
        ]);
    }

    public function about()
    {
        return view('about');
    }

    public function services()
    {
        return view('services.index', [
            'pillars' => array_values($this->servicePillars()),
            'variants' => $this->serviceVariantLineup(),
        ]);
    }

    public function serviceDetail(string $slug)
    {
        $variant = collect($this->serviceVariantLineup())->firstWhere('slug', $slug);

        abort_if(!$variant, 404);

        $related = collect($this->serviceVariantLineup())
            ->where('pillar', $variant['pillar'])
            ->where('slug', '!=', $variant['slug'])
            ->values()
            ->all();

        return view('services.show', [
            'variant' => $variant,
            'related' => $related,
        ]);
    }

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

    public function contact()
    {
        return view('contact', [
            'branches' => $this->branchList(),
            'pillars' => array_values($this->servicePillars()),
        ]);
    }

    public function cekGaransi()
    {
        return view('cek-garansi', [
            'dashboardBaseUrl' => config('services.dashboard.base_url'),
        ]);
    }
}
