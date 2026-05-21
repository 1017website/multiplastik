<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalog = $this->catalog();

        foreach ($catalog as $bIdx => $bData) {
            $brand = Brand::create([
                'slug' => $bData['id'],
                'name' => $bData['name'],
                'tagline' => $bData['tagline'],
                'logo' => $bData['logo'],
                'description' => $bData['desc'],
                'sort_order' => $bIdx,
                'is_active' => true,
            ]);

            foreach ($bData['categories'] as $cIdx => $cData) {
                $cat = Category::create([
                    'brand_id' => $brand->id,
                    'slug' => $cData['id'],
                    'name' => $cData['name'],
                    'icon' => $cData['icon'],
                    'image' => $cData['img'],
                    'description' => $cData['desc'],
                    'sort_order' => $cIdx,
                    'is_active' => true,
                ]);

                foreach ($cData['products'] as $pIdx => $pData) {
                    Product::create([
                        'category_id' => $cat->id,
                        'slug' => $pData['id'],
                        'name' => $pData['name'],
                        'image' => $pData['img'],
                        'description' => $pData['desc'],
                        'specs' => $pData['specs'],
                        'sort_order' => $pIdx,
                        'is_active' => true,
                    ]);
                }
            }
        }
    }

    private function catalog(): array
    {
        return [
            // ==================== HOK CUP ====================
            [
                'id' => 'hok-cup', 'name' => 'Hok Cup',
                'tagline' => 'Gelas plastik food grade N-series untuk minuman',
                'logo' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623665/logo_3_ow2uyr.png',
                'desc' => 'Hok Cup adalah brand gelas plastik food grade terpercaya, BPA Free, bersertifikasi Halal dan ISO. Tersedia seri Natural (bening) dan Printing (motif cetak) untuk berbagai kebutuhan minuman.',
                'categories' => [
                    [
                        'id' => 'gelas-natural', 'name' => 'Gelas Natural',
                        'icon' => 'fas fa-coffee',
                        'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623686/HOK_CUP_Natural_12_OZ_g3w31m.jpg',
                        'desc' => 'Gelas PP bening N-series, food grade BPA Free, tahan panas dan dingin',
                        'products' => $this->hokCupNatural(),
                    ],
                    [
                        'id' => 'gelas-printing', 'name' => 'Gelas Printing',
                        'icon' => 'fas fa-palette',
                        'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623689/HOK_CUP_Natural_16_OZ_xwq2ax.jpg',
                        'desc' => 'Gelas cetak motif untuk branding kedai dan UMKM minuman',
                        'products' => [], // ditambahkan via admin
                    ],
                ],
            ],
            // ==================== ATOZ ====================
            [
                'id' => 'atoz', 'name' => 'ATOZ',
                'tagline' => 'Tusuk bambu halus & bersih untuk kebutuhan kuliner',
                'logo' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623666/logo_multiplast_2_kyijnj.png',
                'desc' => 'ATOZ adalah brand tusuk bambu premium berkualitas halus, bersih, dan higienis. Berstandar food safe, bersertifikasi Halal MUI dan ISO. Isi lebih banyak per pack dibanding merek lain.',
                'categories' => [
                    [
                        'id' => 'tusuk-bambu', 'name' => 'Tusuk Bambu',
                        'icon' => 'fas fa-utensils',
                        'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623685/ATOS_TUSUK_SATE_20_cm_pgmahm.jpg',
                        'desc' => 'Tusuk cilok, sate, sempol, dan tusuk gigi steril',
                        'products' => $this->atozTusuk(),
                    ],
                ],
            ],
            // ==================== sOne ====================
            [
                'id' => 'sone', 'name' => 'sOne',
                'tagline' => 'Sendok plastik disposable food grade, tidak mudah patah',
                'logo' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623664/logo_4_kmmeer.png',
                'desc' => 'sOne adalah brand sendok makan plastik sekali pakai (disposable cutlery) berkualitas. Bahan food grade, hygienic, tidak mudah patah, dan tersedia dalam tiga pilihan warna.',
                'categories' => [
                    [
                        'id' => 'sendok-makan', 'name' => 'Sendok Makan',
                        'icon' => 'fas fa-utensil-spoon',
                        'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623687/sendok_sone_ig_bening_w5oy6x.jpg',
                        'desc' => 'Sendok plastik disposable food grade, 3 pilihan warna',
                        'products' => $this->soneSendok(),
                    ],
                ],
            ],
            // ==================== LUX ====================
            [
                'id' => 'lux', 'name' => 'LUX',
                'tagline' => 'Styrofoam food packaging solution, Halal & CFC Free',
                'logo' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623666/logo_8_povoxg.png',
                'desc' => 'LUX adalah brand styrofoam food packaging terpercaya dari PT Sinar Taman Plastindo. Produk CFC free, food contact material safe, bersertifikasi Halal. Tersedia berbagai tipe untuk kebutuhan warung hingga katering besar.',
                'categories' => [
                    [
                        'id' => 'styrofoam', 'name' => 'Styrofoam Box',
                        'icon' => 'fas fa-box-open',
                        'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623691/Styerofoam_L01_lshejg.jpg',
                        'desc' => 'Kotak styrofoam berbagai ukuran, Halal & CFC free',
                        'products' => $this->luxStyrofoam(),
                    ],
                ],
            ],
        ];
    }

    private function hokCupNatural(): array
    {
        $sizes = [
            ['6oz', '6 Oz', '9,2', '5', '5,5', 'l4uh45', '6_OZ_l4uh45'],
            ['8oz', '8 Oz', '9,2', '5,5', '6', 'fqfowj', '8_OZ_fqfowj'],
            ['10oz', '10 Oz', '9,2', '5,5', '7,5', 'c2hmeg', '10_OZ_c2hmeg'],
            ['12oz', '12 Oz', '9,2', '5,5', '9,5', 'g3w31m', '12_OZ_g3w31m'],
            ['14oz', '14 Oz', '9,2', '5,5', '11', 'ekyckr', '14_OZ_ekyckr'],
            ['16oz', '16 Oz', '9,2', '5,5', '12,5', 'xwq2ax', '16_OZ_xwq2ax'],
        ];
        $out = [];
        foreach ($sizes as $s) {
            $img = 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623686/HOK_CUP_Natural_' . $s[6] . '.jpg';
            $out[] = [
                'id' => 'hc-natural-' . $s[0],
                'name' => 'Gelas Natural ' . $s[1],
                'img' => $img,
                'desc' => 'Hok Cup Natural ' . $s[0] . ' – gelas plastik PP bening food grade BPA Free. Tahan panas dan dingin, dapat dipakai ulang.',
                'specs' => [
                    ['Ukuran', $s[1]],
                    ['Diameter Atas', $s[2] . ' cm'],
                    ['Diameter Bawah', $s[3] . ' cm'],
                    ['Tinggi', $s[4] . ' cm'],
                    ['Material', 'PP Food Grade, BPA Free'],
                    ['Sertifikasi', 'Halal, ISO 9001, ISO 14001, ISO 45001'],
                    ['Isi per Pack', '50 pcs'],
                    ['Isi per Karton', '2000 pcs'],
                ],
            ];
        }
        return $out;
    }

    private function atozTusuk(): array
    {
        return [
            [
                'id' => 'atoz-tusuk-cilok-15',
                'name' => 'Tusuk Cilok / Pentol 15 cm',
                'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623686/ATOS_TUSUK_CILOK_15cm_oj0eup.jpg',
                'desc' => 'ATOZ Tusuk Cilok/Pentol 15cm – halus, bersih, dan isi lebih banyak. Cocok untuk cilok bakar, pentol, dan jajanan kaki lima. Bersertifikasi Halal MUI.',
                'specs' => [['Panjang','15 cm'],['Material','Bambu Food Grade'],['Sertifikasi','Halal MUI, ISO 22000'],['Berat per Pack','500 gram'],['Isi per Karton','48 pack'],['Berat per Karton','24 kg']],
            ],
            [
                'id' => 'atoz-tusuk-gigi',
                'name' => 'Tusuk Gigi Steril Single Pack',
                'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623682/ATOS_TUSUK_GIGI_imyzmj.jpg',
                'desc' => 'ATOZ Tusuk Gigi Steril – dikemas satu per satu (single wrap) dalam kemasan kertas higienis.',
                'specs' => [['Panjang','6,5 cm'],['Jenis','Steril Single Pack'],['Material','Bambu Pilihan'],['Sertifikasi','Halal MUI'],['Isi per Pack','250 pcs'],['Isi per Karton','25.000 pcs']],
            ],
            [
                'id' => 'atoz-tusuk-sate-20',
                'name' => 'Tusuk Sate 20 cm',
                'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623685/ATOS_TUSUK_SATE_20_cm_pgmahm.jpg',
                'desc' => 'ATOZ Tusuk Sate 20cm – halus, tidak mudah patah, cocok untuk sate ayam, sate kambing.',
                'specs' => [['Panjang','20 cm'],['Material','Bambu Food Grade'],['Sertifikasi','Halal MUI, ISO 22000'],['Berat per Pack','500 gram'],['Isi per Karton','50 pack'],['Berat per Karton','25 kg']],
            ],
            [
                'id' => 'atoz-tusuk-sempol-25',
                'name' => 'Tusuk Sempol 25 cm',
                'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623683/ATOS_TUSUK_SEMPOL_25cm_sfscec.jpg',
                'desc' => 'ATOZ Tusuk Sempol 25cm – ukuran ideal untuk sempol, ayam goreng tusuk, jajanan goreng khas.',
                'specs' => [['Panjang','25 cm'],['Material','Bambu Food Grade'],['Sertifikasi','Halal MUI, ISO 22000'],['Berat per Pack','500 gram'],['Isi per Karton','50 pack'],['Berat per Karton','25 kg']],
            ],
            [
                'id' => 'atoz-tusuk-sempol-30',
                'name' => 'Tusuk Sempol 30 cm',
                'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623688/ATOS_TUSUK_SEMPOL_30cm_dwv4sc.jpg',
                'desc' => 'ATOZ Tusuk Sempol 30cm – ukuran panjang untuk sempol besar, corn dog, jajanan festival.',
                'specs' => [['Panjang','30 cm'],['Material','Bambu Food Grade'],['Sertifikasi','Halal MUI, ISO 22000'],['Berat per Pack','500 gram'],['Isi per Karton','50 pack'],['Berat per Karton','25 kg']],
            ],
        ];
    }

    private function soneSendok(): array
    {
        return [
            [
                'id' => 'sone-sendok-bening', 'name' => 'Sendok Makan – Bening (Transparan)',
                'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623687/sendok_sone_ig_bening_w5oy6x.jpg',
                'desc' => 'sOne Sendok Makan warna Bening – food grade, hygienic, tidak mudah patah. Cocok untuk katering, acara, restoran.',
                'specs' => [['Panjang','16 cm'],['Lebar','3,5 cm'],['Warna','Bening / Transparan'],['Material','PP Food Grade'],['Jenis','Disposable Cutlery'],['Isi per Pack','100 pcs'],['Isi per Karton','2000 pcs']],
            ],
            [
                'id' => 'sone-sendok-hitam', 'name' => 'Sendok Makan – Hitam',
                'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623689/sendok_sone_ig_hitam_elyozb.jpg',
                'desc' => 'sOne Sendok Makan warna Hitam – tampilan elegan, untuk katering premium, box makanan, event formal.',
                'specs' => [['Panjang','16 cm'],['Lebar','3,5 cm'],['Warna','Hitam'],['Material','PP Food Grade'],['Jenis','Disposable Cutlery'],['Isi per Pack','100 pcs'],['Isi per Karton','2000 pcs']],
            ],
            [
                'id' => 'sone-sendok-putih', 'name' => 'Sendok Makan – Putih',
                'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623689/sendok_sone_ig_putih_lobl5b.jpg',
                'desc' => 'sOne Sendok Makan warna Putih – pilihan universal, terlihat bersih dan higienis. Cocok untuk nasi box, warung makan.',
                'specs' => [['Panjang','16 cm'],['Lebar','3,5 cm'],['Warna','Putih'],['Material','PP Food Grade'],['Jenis','Disposable Cutlery'],['Isi per Pack','100 pcs'],['Isi per Karton','2000 pcs']],
            ],
        ];
    }

    private function luxStyrofoam(): array
    {
        return [
            [
                'id' => 'lux-h01', 'name' => 'Styrofoam Tipe H01',
                'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623689/Styerofoam_H01_ekicmr.jpg',
                'desc' => 'LUX Styrofoam H01 – kotak ukuran mini untuk burger, nugget, cilok bakar, jajanan kecil.',
                'specs' => [['Tipe','H01'],['Ukuran','8,5 × 9 × 7 cm'],['Material','EPS Styrofoam, CFC Free'],['Sertifikasi','Halal'],['Isi per Pack','200 pcs'],['Isi per Ball','2400 pcs']],
            ],
            [
                'id' => 'lux-l01', 'name' => 'Styrofoam Tipe L01',
                'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623691/Styerofoam_L01_lshejg.jpg',
                'desc' => 'LUX Styrofoam L01 – ukuran medium untuk nasi, lauk, salad, makanan porsi standar.',
                'specs' => [['Tipe','L01'],['Ukuran','15,5 × 10,5 × 6,5 cm'],['Material','EPS Styrofoam, CFC Free'],['Sertifikasi','Halal'],['Isi per Pack','100 pcs'],['Isi per Ball','1000 pcs']],
            ],
            [
                'id' => 'lux-l03-sekat', 'name' => 'Styrofoam Tipe L03 Sekat',
                'img' => 'https://res.cloudinary.com/dcpleyqfl/image/upload/q_auto/f_auto/v1778623693/Styerofoam_L03_Sekat_as5k2u.jpg',
                'desc' => 'LUX Styrofoam L03 Sekat – 3 kompartemen ideal untuk nasi kotak lengkap lauk, sayur, sambal.',
                'specs' => [['Tipe','L03 Sekat'],['Ukuran','17 × 17 × 4,5 cm'],['Sekat','3 Kompartemen'],['Material','EPS Styrofoam, CFC Free'],['Sertifikasi','Halal'],['Isi per Pack','100 pcs'],['Isi per Ball','1000 pcs']],
            ],
        ];
    }
}
