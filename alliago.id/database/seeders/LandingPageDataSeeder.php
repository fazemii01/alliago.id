<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\SiteFaq;
use App\Models\Testimonial;
use App\Models\VisaProduct;
use Illuminate\Database\Seeder;

class LandingPageDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCountries();
        $this->seedVisaProducts();
        $this->seedTestimonials();
        $this->seedSiteFaqs();
    }

    private function seedCountries(): void
    {
        $countries = [
            // Featured (hero search + services)
            ['name' => 'Indonesia', 'code' => 'ID', 'flag_emoji' => '🇮🇩'],
            ['name' => 'Malaysia', 'code' => 'MY', 'flag_emoji' => '🇲🇾'],
            ['name' => 'Singapore', 'code' => 'SG', 'flag_emoji' => '🇸🇬'],
            ['name' => 'Jepang', 'code' => 'JP', 'flag_emoji' => '🇯🇵'],
            ['name' => 'Korea Selatan', 'code' => 'KR', 'flag_emoji' => '🇰🇷'],
            ['name' => 'Australia', 'code' => 'AU', 'flag_emoji' => '🇦🇺'],
            ['name' => 'Amerika Serikat', 'code' => 'US', 'flag_emoji' => '🇺🇸'],

            // Marquee Row 1 — Southeast Asia & Oceania
            ['name' => 'Myanmar', 'code' => 'MM', 'flag_emoji' => '🇲🇲'],
            ['name' => 'Brunei', 'code' => 'BN', 'flag_emoji' => '🇧🇳'],
            ['name' => 'Kamboja', 'code' => 'KH', 'flag_emoji' => '🇰🇭'],
            ['name' => 'Laos', 'code' => 'LA', 'flag_emoji' => '🇱🇦'],
            ['name' => 'Fiji', 'code' => 'FJ', 'flag_emoji' => '🇫🇯'],
            ['name' => 'Kiribati', 'code' => 'KI', 'flag_emoji' => '🇰🇮'],
            ['name' => 'Vietnam', 'code' => 'VN', 'flag_emoji' => '🇻🇳'],

            // Marquee Row 2 — South Asia
            ['name' => 'Bangladesh', 'code' => 'BD', 'flag_emoji' => '🇧🇩'],
            ['name' => 'Bhutan', 'code' => 'BT', 'flag_emoji' => '🇧🇹'],
            ['name' => 'India', 'code' => 'IN', 'flag_emoji' => '🇮🇳'],
            ['name' => 'Maladewa', 'code' => 'MV', 'flag_emoji' => '🇲🇻'],
            ['name' => 'Nepal', 'code' => 'NP', 'flag_emoji' => '🇳🇵'],
            ['name' => 'Pakistan', 'code' => 'PK', 'flag_emoji' => '🇵🇰'],
            ['name' => 'Afganistan', 'code' => 'AF', 'flag_emoji' => '🇦🇫'],

            // Marquee Row 3 — Europe
            ['name' => 'Lithuania', 'code' => 'LT', 'flag_emoji' => '🇱🇹'],
            ['name' => 'Luksemburg', 'code' => 'LU', 'flag_emoji' => '🇱🇺'],
            ['name' => 'Malta', 'code' => 'MT', 'flag_emoji' => '🇲🇹'],
            ['name' => 'Monako', 'code' => 'MC', 'flag_emoji' => '🇲🇨'],
            ['name' => 'Montenegro', 'code' => 'ME', 'flag_emoji' => '🇲🇪'],
            ['name' => 'Belanda', 'code' => 'NL', 'flag_emoji' => '🇳🇱'],
            ['name' => 'Liechtenstein', 'code' => 'LI', 'flag_emoji' => '🇱🇮'],
            ['name' => 'Schengen', 'code' => null, 'flag_emoji' => '🇪🇺'],

            // Marquee Row 4 — Americas
            ['name' => 'Honduras', 'code' => 'HN', 'flag_emoji' => '🇭🇳'],
            ['name' => 'Nikaragua', 'code' => 'NI', 'flag_emoji' => '🇳🇮'],
            ['name' => 'Panama', 'code' => 'PA', 'flag_emoji' => '🇵🇦'],
            ['name' => 'Antigua dan Barbuda', 'code' => 'AG', 'flag_emoji' => '🇦🇬'],
            ['name' => 'Bahama', 'code' => 'BS', 'flag_emoji' => '🇧🇸'],
            ['name' => 'Barbados', 'code' => 'BB', 'flag_emoji' => '🇧🇧'],

            // Marquee Row 5 — Africa
            ['name' => 'Sudan', 'code' => 'SD', 'flag_emoji' => '🇸🇩'],
            ['name' => 'Swaziland', 'code' => 'SZ', 'flag_emoji' => '🇸🇿'],
            ['name' => 'Tanzania', 'code' => 'TZ', 'flag_emoji' => '🇹🇿'],
            ['name' => 'Togo', 'code' => 'TG', 'flag_emoji' => '🇹🇬'],
            ['name' => 'Tunisia', 'code' => 'TN', 'flag_emoji' => '🇹🇳'],
            ['name' => 'Uganda', 'code' => 'UG', 'flag_emoji' => '🇺🇬'],
            ['name' => 'Zambia', 'code' => 'ZM', 'flag_emoji' => '🇿🇲'],
        ];

        foreach ($countries as $country) {
            Country::firstOrCreate(
                ['name' => $country['name']],
                array_merge($country, ['is_active' => true]),
            );
        }
    }

    private function seedVisaProducts(): void
    {
        // Japan Visa Waiver
        $japan = Country::where('name', 'Jepang')->first();
        if ($japan) {
            $jpVisa = VisaProduct::firstOrCreate(
                ['slug' => 'japan-visa-waiver'],
                [
                    'country_id' => $japan->id,
                    'name' => 'Japan Visa Waiver',
                    'type' => 'Online',
                    'promo_label' => 'Paling populer',
                    'processing_time' => '2–5 hari',
                    'stay_duration' => '15 hari',
                    'validity' => '3 tahun',
                    'base_price' => 279000,
                    'discount_price' => 239000,
                    'short_description' => 'Untuk pemegang e-paspor Indonesia',
                    'description' => 'Visa waiver Jepang membantu traveler Indonesia dengan e-paspor mengajukan izin kunjungan singkat secara lebih sederhana. Cocok untuk wisata, kunjungan keluarga, atau perjalanan singkat.',
                    'is_active' => true,
                    'sort_order' => 1,
                ],
            );

            // Documents
            $this->seedRelation($jpVisa, 'documents', [
                ['name' => 'Paspor elektronik', 'is_required' => true, 'sort_order' => 1],
                ['name' => 'KTP dan Kartu Keluarga', 'is_required' => true, 'sort_order' => 2],
                ['name' => 'Halaman pengesahan paspor', 'is_required' => true, 'sort_order' => 3],
            ]);

            // Requirements
            $this->seedRelation($jpVisa, 'requirements', [
                ['title' => 'Untuk pemegang e-paspor Indonesia', 'sort_order' => 1],
                ['title' => 'Tidak perlu appointment fisik', 'sort_order' => 2],
                ['title' => 'Dokumen dikirim dan dicek online', 'sort_order' => 3],
            ]);

            // FAQs
            if ($jpVisa->faqs()->count() === 0) {
                $jpVisa->faqs()->createMany([
                    ['question' => 'Berapa lama proses visa waiver Jepang?', 'answer' => 'Proses biasanya memakan waktu 2-5 hari kerja setelah semua dokumen diterima dan diverifikasi.', 'sort_order' => 1],
                    ['question' => 'Apakah bisa perpanjang visa waiver?', 'answer' => 'Visa waiver berlaku untuk kunjungan singkat hingga 15 hari dan tidak dapat diperpanjang selama di Jepang.', 'sort_order' => 2],
                ]);
            }
        }

        // Korea Tourist Visa
        $korea = Country::where('name', 'Korea Selatan')->first();
        if ($korea) {
            $krVisa = VisaProduct::firstOrCreate(
                ['slug' => 'korea-tourist-visa'],
                [
                    'country_id' => $korea->id,
                    'name' => 'Korea Tourist Visa',
                    'type' => 'Sticker',
                    'promo_label' => 'Promo',
                    'processing_time' => '8–12 hari',
                    'stay_duration' => '30 hari',
                    'validity' => '3 bulan',
                    'base_price' => 1800000,
                    'discount_price' => 1550000,
                    'short_description' => 'Wisata, keluarga, dan kunjungan singkat',
                    'description' => 'Visa turis Korea Selatan untuk kunjungan wisata, keluarga, atau perjalanan singkat. Proses melalui sticker visa pada paspor.',
                    'is_active' => true,
                    'sort_order' => 2,
                ],
            );

            $this->seedRelation($krVisa, 'documents', [
                ['name' => 'Paspor asli (min. 6 bulan)', 'is_required' => true, 'sort_order' => 1],
                ['name' => 'Foto 3.5x4.5cm background putih', 'is_required' => true, 'sort_order' => 2],
                ['name' => 'Bukti keuangan (rekening koran)', 'is_required' => true, 'sort_order' => 3],
                ['name' => 'Itinerary perjalanan', 'is_required' => true, 'sort_order' => 4],
            ]);
        }

        // Australia Visitor Visa
        $australia = Country::where('name', 'Australia')->first();
        if ($australia) {
            VisaProduct::firstOrCreate(
                ['slug' => 'australia-visitor-visa'],
                [
                    'country_id' => $australia->id,
                    'name' => 'Australia Visitor',
                    'type' => 'Online',
                    'promo_label' => 'E-visa',
                    'processing_time' => '14–21 hari',
                    'stay_duration' => '90 hari',
                    'validity' => '1 tahun',
                    'base_price' => 4100000,
                    'discount_price' => 3650000,
                    'short_description' => 'Liburan, keluarga, dan bisnis singkat',
                    'description' => 'Visa kunjungan Australia untuk wisata, keluarga, dan bisnis singkat. Diproses secara online melalui sistem e-visa.',
                    'is_active' => true,
                    'sort_order' => 3,
                ],
            );
        }
    }

    private function seedRelation(VisaProduct $product, string $relation, array $items): void
    {
        if ($product->$relation()->count() === 0) {
            $product->$relation()->createMany($items);
        }
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            [
                'name' => 'Nadia A.',
                'visa_label' => 'Japan Visa Waiver',
                'rating' => 5,
                'content' => 'Checklist dokumennya jelas. Saya jadi tahu apa yang kurang sebelum submit.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Kevin R.',
                'visa_label' => 'Korea Tourist Visa',
                'rating' => 5,
                'content' => 'Admin responsif di WhatsApp dan update statusnya mudah diikuti.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Maya S.',
                'visa_label' => 'Australia Visitor Visa',
                'rating' => 5,
                'content' => 'Harga dari awal sudah kelihatan, jadi tidak bingung soal biaya tambahan.',
                'sort_order' => 3,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::firstOrCreate(
                ['name' => $testimonial['name']],
                array_merge($testimonial, ['is_active' => true]),
            );
        }
    }

    private function seedSiteFaqs(): void
    {
        $faqs = [
            [
                'question' => 'Apakah visa pasti disetujui?',
                'answer' => 'Keputusan akhir tetap di pihak kedutaan atau imigrasi. Kami membantu memastikan dokumen lebih lengkap dan sesuai syarat sebelum diajukan.',
                'sort_order' => 1,
            ],
            [
                'question' => 'Berapa lama proses visa?',
                'answer' => 'Tergantung negara dan jenis visa. Setiap halaman visa menampilkan estimasi proses yang bisa dilihat sebelum mengajukan.',
                'sort_order' => 2,
            ],
            [
                'question' => 'Bagaimana kalau dokumen kurang?',
                'answer' => 'Tim kami akan memberi catatan dokumen yang perlu dilengkapi sebelum proses pengajuan dilanjutkan.',
                'sort_order' => 3,
            ],
            [
                'question' => 'Apakah bisa konsultasi dulu?',
                'answer' => 'Bisa. Kamu bisa mulai dari form cek visa atau langsung chat konsultan melalui WhatsApp.',
                'sort_order' => 4,
            ],
        ];

        foreach ($faqs as $faq) {
            SiteFaq::firstOrCreate(
                ['question' => $faq['question']],
                array_merge($faq, ['is_active' => true]),
            );
        }
    }
}
