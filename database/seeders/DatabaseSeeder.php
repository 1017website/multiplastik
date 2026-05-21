<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\News;
use App\Models\Product;
use App\Models\Promo;
use App\Models\SiteSetting;
use App\Models\Slide;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ====================== ADMIN USER ======================
        User::updateOrCreate(
            ['email' => 'admin@multiplastik.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // ====================== SITE SETTINGS ======================
        $settings = [
            // general
            'site_title' => ['Multi Plastik – Distributor Plastik dan Kemasan Terpercaya', 'general'],
            'site_description' => ['Multi Plastik distributor plastik dan kemasan: Hok Cup, ATOZ, sOne, LUX. Produk food grade berkualitas untuk industri dan UMKM.', 'general'],
            'site_keywords' => ['distributor plastik, kemasan plastik, hok cup, gelas plastik, styrofoam lux, sendok plastik sone, tusuk atoz, Multi Plastik Surabaya', 'general'],
            'site_logo' => ['https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623666/logo_multiplast_mgto3e.png', 'general', 'image'],
            'og_image' => ['https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623666/logo_multiplast_mgto3e.png', 'general', 'image'],
            'footer_about' => ['Distributor resmi plastik dan kemasan terpercaya untuk industri dan UMKM. Harga kompetitif, stok lengkap, siap kirim.', 'general', 'textarea'],
            'copyright_text' => ['© 2025 Multi Plastik. All rights reserved.', 'general'],

            // contact
            'contact_address' => ['Jl. Greges Jaya II No.D 9, Greges, Kec. Asem Rowo, Surabaya, Jawa Timur 60183', 'contact', 'textarea'],
            'contact_whatsapp' => ['6281234567890', 'contact'],
            'contact_whatsapp_display' => ['+62 812-3456-7890', 'contact'],
            'contact_email' => ['info@multiplastik.com', 'contact'],
            'contact_hours' => ['Senin–Sabtu: 08.00–17.00 WIB', 'contact'],
            'contact_instagram' => ['#', 'contact'],
            'contact_facebook' => ['#', 'contact'],
            'contact_tokopedia' => ['#', 'contact'],
            'contact_map_embed' => ['https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3958.124021786718!2d112.6799964!3d-7.2266922!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7ff1d0bf7c425%3A0x7c359145f8670c2b!2sMULTI%20PLASTIK!5e0!3m2!1sen!2sid!4v1779166223162!5m2!1sen!2sid', 'contact', 'textarea'],

            // about
            'about_label' => ['Tentang Kami', 'about'],
            'about_title' => ['Distributor Plastik\n& Kemasan Terpercaya', 'about'],
            'about_image' => ['https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&q=80', 'about', 'image'],
            'about_badge_number' => ['10+', 'about'],
            'about_badge_text' => ['Tahun Berpengalaman', 'about'],
            'about_paragraph_1' => ['Multi Plastik adalah distributor resmi berbagai brand plastik dan kemasan terpercaya yang melayani kebutuhan industri, UMKM, dan rumah tangga.', 'about', 'textarea'],
            'about_paragraph_2' => ['Stok selalu tersedia, harga kompetitif, dan pengiriman cepat ke seluruh wilayah.', 'about', 'textarea'],
            'about_feat_1_title' => ['Stok Lengkap', 'about'],
            'about_feat_1_text' => ['Ratusan varian produk tersedia', 'about'],
            'about_feat_1_icon' => ['fas fa-check', 'about'],
            'about_feat_2_title' => ['Pengiriman Cepat', 'about'],
            'about_feat_2_text' => ['Jangkauan luas ke seluruh area', 'about'],
            'about_feat_2_icon' => ['fas fa-truck', 'about'],
            'about_feat_3_title' => ['Produk Terjamin', 'about'],
            'about_feat_3_text' => ['Food grade & berstandar halal', 'about'],
            'about_feat_3_icon' => ['fas fa-shield-alt', 'about'],
            'about_feat_4_title' => ['Konsultasi Gratis', 'about'],
            'about_feat_4_text' => ['Tim siap bantu pilih produk', 'about'],
            'about_feat_4_icon' => ['fas fa-headset', 'about'],

            // keunggulan
            'keung_label' => ['Mengapa Kami', 'keunggulan'],
            'keung_title' => ['Keunggulan Multi Plastik', 'keunggulan'],
            'keung_1_title' => ['Stok Selalu Tersedia', 'keunggulan'],
            'keung_1_desc' => ['Gudang besar dengan ribuan item siap kirim tanpa menunggu lama.', 'keunggulan', 'textarea'],
            'keung_1_icon' => ['fas fa-warehouse', 'keunggulan'],
            'keung_2_title' => ['Harga Grosir Kompetitif', 'keunggulan'],
            'keung_2_desc' => ['Dapatkan harga terbaik langsung dari distributor tanpa perantara.', 'keunggulan', 'textarea'],
            'keung_2_icon' => ['fas fa-tags', 'keunggulan'],
            'keung_3_title' => ['Pengiriman Cepat', 'keunggulan'],
            'keung_3_desc' => ['Pengiriman ke seluruh wilayah dengan mitra logistik terpercaya.', 'keunggulan', 'textarea'],
            'keung_3_icon' => ['fas fa-truck-fast', 'keunggulan'],
            'keung_4_title' => ['Produk Berstandar', 'keunggulan'],
            'keung_4_desc' => ['Food grade, halal certified, dan telah melewati quality control ketat.', 'keunggulan', 'textarea'],
            'keung_4_icon' => ['fas fa-medal', 'keunggulan'],

            // hero stats
            'stat_1_number' => ['4', 'hero_stats'],
            'stat_1_label' => ['Brand Unggulan', 'hero_stats'],
            'stat_2_number' => ['19+', 'hero_stats'],
            'stat_2_label' => ['Varian Produk', 'hero_stats'],
            'stat_3_number' => ['1000+', 'hero_stats'],
            'stat_3_label' => ['Pelanggan Aktif', 'hero_stats'],
            'stat_4_number' => ['10+', 'hero_stats'],
            'stat_4_label' => ['Tahun Pengalaman', 'hero_stats'],

            // sosmed embed
            'elfsight_instagram_id' => ['217dab31-24a6-4ed0-b50f-0a066961f26f', 'sosmed_embed'],
            'elfsight_testimoni_id' => ['5082c0d6-eb7e-4d3f-9f50-29193505ab70', 'sosmed_embed'],

            // analytics & ads (kosong default)
            'google_analytics_id' => ['', 'analytics'],
            'google_tag_manager_id' => ['', 'analytics'],
            'meta_pixel_id' => ['', 'analytics'],
            'tiktok_pixel_id' => ['', 'analytics'],
            'google_ads_id' => ['', 'ads'],
            'google_ads_conversion_label' => ['', 'ads'],
            'google_ads_remarketing_tag' => ['', 'ads', 'textarea'],
            'meta_ads_extra_script' => ['', 'ads', 'textarea'],
            'custom_head_scripts' => ['', 'ads', 'textarea'],
            'custom_body_scripts' => ['', 'ads', 'textarea'],
        ];

        foreach ($settings as $key => $val) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $val[0],
                    'group' => $val[1] ?? 'general',
                    'type' => $val[2] ?? 'text',
                ]
            );
        }

        // ====================== SLIDES ======================
        $slides = [
            [
                'tag' => 'Distributor Resmi Terpercaya',
                'title_top' => 'Solusi Plastik',
                'title_em' => '& Kemasan',
                'title_bottom' => 'Terlengkap',
                'subtitle' => 'Distributor resmi Hok Cup, ATOZ, sOne, LUX dan brand kemasan terpercaya lainnya. Stok lengkap, harga kompetitif, siap kirim.',
                'background_image' => 'https://images.unsplash.com/photo-1553413077-190dd305871c?w=1600&q=80',
                'btn_primary_text' => 'Lihat Brand & Produk',
                'btn_primary_link' => 'nav:brands',
                'btn_primary_icon' => 'fas fa-layer-group',
                'btn_secondary_text' => 'Chat Sekarang',
                'btn_secondary_link' => 'wa',
                'btn_secondary_icon' => 'fab fa-whatsapp',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'tag' => 'Hok Cup — N-Series',
                'title_top' => 'Gelas Plastik',
                'title_em' => 'Food Grade',
                'title_bottom' => 'BPA Free',
                'subtitle' => 'Tersedia 6 ukuran (6oz–16oz) + gelas printing motif. Sertifikasi Halal, ISO 9001, ISO 14001, ISO 45001.',
                'background_image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=1600&q=80',
                'btn_primary_text' => 'Lihat Produk Hok Cup',
                'btn_primary_link' => 'nav:brand:hok-cup',
                'btn_primary_icon' => 'fas fa-box-open',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'tag' => 'ATOZ — Tusuk Bambu Premium',
                'title_top' => 'Halus, Bersih',
                'title_em' => 'Isi Lebih',
                'title_bottom' => 'Banyak',
                'subtitle' => 'Tusuk cilok, sate, sempol 15–30cm dan tusuk gigi steril single pack. Halal MUI & ISO 22000.',
                'background_image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1600&q=80',
                'btn_primary_text' => 'Lihat Produk ATOZ',
                'btn_primary_link' => 'nav:brand:atoz',
                'btn_primary_icon' => 'fas fa-box-open',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'tag' => 'LUX — Food Packaging Solution',
                'title_top' => 'Styrofoam',
                'title_em' => 'CFC Free',
                'title_bottom' => 'Halal',
                'subtitle' => 'Tersedia tipe H01, L01, dan L03 Sekat untuk berbagai kebutuhan katering dan warung makan. Food contact material safe.',
                'background_image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=1600&q=80',
                'btn_primary_text' => 'Lihat Produk LUX',
                'btn_primary_link' => 'nav:brand:lux',
                'btn_primary_icon' => 'fas fa-box-open',
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];
        foreach ($slides as $s) Slide::create($s);

        // ====================== PROMOS ======================
        $promos = [
            '🔥 Stok Hok Cup N-Series tersedia lengkap 6oz – 16oz',
            '✅ ATOZ Tusuk Bambu Halal MUI — isi lebih banyak per pack',
            '📦 Styrofoam LUX H01 / L01 / L03 Sekat — CFC Free & Halal',
            '🥄 sOne Sendok Disposable Food Grade — 3 pilihan warna',
            '🚚 Pengiriman cepat ke seluruh wilayah Indonesia',
            '💬 Hubungi kami via WhatsApp untuk penawaran harga grosir',
        ];
        foreach ($promos as $i => $text) {
            Promo::create(['text' => $text, 'sort_order' => $i, 'is_active' => true]);
        }

        // ====================== BRANDS / CATEGORIES / PRODUCTS ======================
        $this->call(ProductCatalogSeeder::class);

        // ====================== NEWS ======================
        $newsData = [
            [
                'slug' => 'promo-hok-cup-2025',
                'category' => 'Promo',
                'published_at' => '2025-05-10',
                'title' => 'Promo Grosir Hok Cup Natural – Minimum Order Karton',
                'excerpt' => 'Dapatkan harga spesial untuk pembelian Hok Cup Natural Series minimum 1 karton. Berlaku untuk semua ukuran 6oz hingga 16oz.',
                'image' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623686/HOK_CUP_Natural_12_OZ_g3w31m.jpg',
                'content' => "<p>Multi Plastik menghadirkan penawaran harga grosir spesial untuk produk Hok Cup Natural Series (N-Series). Promo ini berlaku bagi pembeli yang melakukan pembelian minimal 1 karton untuk setiap ukuran.</p><h3>Produk yang termasuk promo:</h3><p>Hok Cup Natural tersedia dalam ukuran 6oz, 8oz, 10oz, 12oz, 14oz, dan 16oz. Semua varian food grade BPA Free dengan sertifikasi Halal, ISO 9001, ISO 14001, dan ISO 45001.</p><h3>Cara order:</h3><p>Hubungi tim sales kami via WhatsApp untuk mendapatkan penawaran harga terbaik. Tersedia pengiriman ke seluruh wilayah Indonesia dengan mitra logistik terpercaya.</p><p>Jangan lewatkan kesempatan ini untuk melengkapi stok kemasan minuman bisnis Anda dengan produk berkualitas harga kompetitif!</p>",
                'is_active' => true,
            ],
            [
                'slug' => 'produk-baru-atoz-sempol',
                'category' => 'Produk Baru',
                'published_at' => '2025-05-02',
                'title' => 'Kini Tersedia: ATOZ Tusuk Sempol 30cm di Multi Plastik',
                'excerpt' => 'Melengkapi lini tusuk bambu ATOZ, kini hadir varian Tusuk Sempol 30cm untuk kebutuhan sempol jumbo dan jajanan festival.',
                'image' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623688/ATOS_TUSUK_SEMPOL_30cm_dwv4sc.jpg',
                'content' => "<p>Multi Plastik dengan bangga mengumumkan ketersediaan varian terbaru dari brand ATOZ, yaitu Tusuk Sempol 30cm.</p><h3>Keunggulan ATOZ Tusuk Sempol 30cm:</h3><p>Dibuat dari bambu pilihan yang diproses secara higienis, ATOZ Tusuk Sempol 30cm memiliki permukaan halus dan tidak mudah bengkok saat digunakan dalam proses memasak.</p><h3>Spesifikasi:</h3><p>Panjang 30cm, berat 500 gram per pack, isi 1 karton 50 pack (25 kg total). Bersertifikasi Halal MUI dan ISO 22000.</p>",
                'is_active' => true,
            ],
            [
                'slug' => 'tips-pilih-gelas-plastik',
                'category' => 'Tips & Info',
                'published_at' => '2025-04-25',
                'title' => 'Tips Memilih Gelas Plastik yang Tepat untuk Bisnis Minuman',
                'excerpt' => 'Panduan singkat memilih ukuran dan jenis gelas plastik yang sesuai dengan menu minuman bisnis Anda agar lebih efisien dan profesional.',
                'image' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623689/HOK_CUP_Natural_16_OZ_xwq2ax.jpg',
                'content' => "<p>Memilih gelas plastik yang tepat adalah keputusan penting untuk bisnis minuman.</p><h3>Panduan ukuran gelas per jenis minuman:</h3><p><strong>6oz (180ml)</strong> — Ideal untuk dessert cup, puding, es krim.</p><p><strong>8–10oz</strong> — Cocok untuk es buah dan minuman dingin porsi kecil-sedang.</p><p><strong>12oz</strong> — Ukuran standar paling populer untuk es kopi, boba.</p><p><strong>14oz</strong> — Pilihan untuk kopi susu, matcha latte.</p><p><strong>16oz</strong> — Ukuran jumbo untuk boba, smoothie.</p>",
                'is_active' => true,
            ],
        ];
        foreach ($newsData as $n) News::create($n);
    }
}
