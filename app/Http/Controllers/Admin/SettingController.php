<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function index(string $group = 'general')
    {
        $groups = $this->groupConfig();
        if (!isset($groups[$group])) abort(404);

        $fields = $groups[$group];
        $values = SiteSetting::whereIn('key', array_keys($fields))
            ->pluck('value', 'key')
            ->toArray();

        return view('admin.settings.index', compact('group', 'groups', 'fields', 'values'));
    }

    public function update(Request $request, string $group)
    {
        $groups = $this->groupConfig();
        if (!isset($groups[$group])) abort(404);

        $fields = $groups[$group];

        foreach ($fields as $key => $config) {
            $type = $config['type'] ?? 'text';

            if ($type === 'image') {
                // file upload
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $name = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/site'), $name);
                    SiteSetting::set($key, 'uploads/site/' . $name, $group, $type);
                } elseif ($request->filled($key . '_url_manual')) {
                    SiteSetting::set($key, $request->input($key . '_url_manual'), $group, $type);
                }
            } else {
                if ($request->has($key)) {
                    SiteSetting::set($key, $request->input($key), $group, $type);
                }
            }
        }

        return back()->with('success', 'Pengaturan disimpan.');
    }

    /**
     * Konfigurasi semua setting yang bisa diedit.
     * Format: 'group' => ['key' => ['label','type','help']]
     */
    public function groupConfig(): array
    {
        return [
            'general' => [
                'site_title' => ['label' => 'Judul Website', 'type' => 'text'],
                'site_description' => ['label' => 'Deskripsi (meta)', 'type' => 'textarea'],
                'site_keywords' => ['label' => 'Keywords (meta)', 'type' => 'textarea'],
                'site_logo' => ['label' => 'Logo Header', 'type' => 'image'],
                'site_favicon' => ['label' => 'Favicon', 'type' => 'image'],
                'og_image' => ['label' => 'OG Image (share)', 'type' => 'image'],
                'footer_about' => ['label' => 'Teks Footer (deskripsi singkat)', 'type' => 'textarea'],
                'copyright_text' => ['label' => 'Teks Copyright', 'type' => 'text'],
            ],
            'contact' => [
                'contact_address' => ['label' => 'Alamat', 'type' => 'textarea'],
                'contact_whatsapp' => ['label' => 'No. WhatsApp (format internasional 628xxx)', 'type' => 'text'],
                'contact_whatsapp_display' => ['label' => 'WhatsApp (tampilan)', 'type' => 'text'],
                'contact_email' => ['label' => 'Email', 'type' => 'text'],
                'contact_hours' => ['label' => 'Jam Operasional', 'type' => 'text'],
                'contact_instagram' => ['label' => 'URL Instagram', 'type' => 'text'],
                'contact_facebook' => ['label' => 'URL Facebook', 'type' => 'text'],
                'contact_tokopedia' => ['label' => 'URL Tokopedia', 'type' => 'text'],
                'contact_map_embed' => ['label' => 'Google Maps Embed URL', 'type' => 'textarea'],
            ],
            'about' => [
                'about_label' => ['label' => 'Section Label', 'type' => 'text'],
                'about_title' => ['label' => 'Section Title (gunakan \\n untuk baris baru)', 'type' => 'text'],
                'about_image' => ['label' => 'Gambar About', 'type' => 'image'],
                'about_badge_number' => ['label' => 'Badge Angka (mis: 10+)', 'type' => 'text'],
                'about_badge_text' => ['label' => 'Badge Teks (mis: Tahun Berpengalaman)', 'type' => 'text'],
                'about_paragraph_1' => ['label' => 'Paragraf 1', 'type' => 'textarea'],
                'about_paragraph_2' => ['label' => 'Paragraf 2', 'type' => 'textarea'],
                'about_feat_1_title' => ['label' => 'Fitur 1 - Judul', 'type' => 'text'],
                'about_feat_1_text' => ['label' => 'Fitur 1 - Teks', 'type' => 'text'],
                'about_feat_1_icon' => ['label' => 'Fitur 1 - Icon (fas fa-check)', 'type' => 'text'],
                'about_feat_2_title' => ['label' => 'Fitur 2 - Judul', 'type' => 'text'],
                'about_feat_2_text' => ['label' => 'Fitur 2 - Teks', 'type' => 'text'],
                'about_feat_2_icon' => ['label' => 'Fitur 2 - Icon', 'type' => 'text'],
                'about_feat_3_title' => ['label' => 'Fitur 3 - Judul', 'type' => 'text'],
                'about_feat_3_text' => ['label' => 'Fitur 3 - Teks', 'type' => 'text'],
                'about_feat_3_icon' => ['label' => 'Fitur 3 - Icon', 'type' => 'text'],
                'about_feat_4_title' => ['label' => 'Fitur 4 - Judul', 'type' => 'text'],
                'about_feat_4_text' => ['label' => 'Fitur 4 - Teks', 'type' => 'text'],
                'about_feat_4_icon' => ['label' => 'Fitur 4 - Icon', 'type' => 'text'],
            ],
            'keunggulan' => [
                'keung_label' => ['label' => 'Section Label', 'type' => 'text'],
                'keung_title' => ['label' => 'Section Title', 'type' => 'text'],
                'keung_1_title' => ['label' => 'Item 1 - Judul', 'type' => 'text'],
                'keung_1_desc' => ['label' => 'Item 1 - Deskripsi', 'type' => 'textarea'],
                'keung_1_icon' => ['label' => 'Item 1 - Icon', 'type' => 'text'],
                'keung_2_title' => ['label' => 'Item 2 - Judul', 'type' => 'text'],
                'keung_2_desc' => ['label' => 'Item 2 - Deskripsi', 'type' => 'textarea'],
                'keung_2_icon' => ['label' => 'Item 2 - Icon', 'type' => 'text'],
                'keung_3_title' => ['label' => 'Item 3 - Judul', 'type' => 'text'],
                'keung_3_desc' => ['label' => 'Item 3 - Deskripsi', 'type' => 'textarea'],
                'keung_3_icon' => ['label' => 'Item 3 - Icon', 'type' => 'text'],
                'keung_4_title' => ['label' => 'Item 4 - Judul', 'type' => 'text'],
                'keung_4_desc' => ['label' => 'Item 4 - Deskripsi', 'type' => 'textarea'],
                'keung_4_icon' => ['label' => 'Item 4 - Icon', 'type' => 'text'],
            ],
            'hero_stats' => [
                'stat_1_number' => ['label' => 'Stat 1 - Angka', 'type' => 'text'],
                'stat_1_label' => ['label' => 'Stat 1 - Label', 'type' => 'text'],
                'stat_2_number' => ['label' => 'Stat 2 - Angka', 'type' => 'text'],
                'stat_2_label' => ['label' => 'Stat 2 - Label', 'type' => 'text'],
                'stat_3_number' => ['label' => 'Stat 3 - Angka', 'type' => 'text'],
                'stat_3_label' => ['label' => 'Stat 3 - Label', 'type' => 'text'],
                'stat_4_number' => ['label' => 'Stat 4 - Angka', 'type' => 'text'],
                'stat_4_label' => ['label' => 'Stat 4 - Label', 'type' => 'text'],
            ],
            'sosmed_embed' => [
                'elfsight_instagram_id' => ['label' => 'Elfsight Instagram App ID', 'type' => 'text', 'help' => 'Contoh: 217dab31-24a6-4ed0-b50f-0a066961f26f'],
                'elfsight_testimoni_id' => ['label' => 'Elfsight Testimoni App ID', 'type' => 'text'],
            ],
            'analytics' => [
                'google_analytics_id' => ['label' => 'Google Analytics 4 (G-XXXXX)', 'type' => 'text', 'help' => 'GA4 Measurement ID'],
                'google_tag_manager_id' => ['label' => 'Google Tag Manager (GTM-XXXXX)', 'type' => 'text'],
                'meta_pixel_id' => ['label' => 'Meta Pixel ID', 'type' => 'text', 'help' => 'Pixel Facebook/Instagram Ads'],
                'tiktok_pixel_id' => ['label' => 'TikTok Pixel ID', 'type' => 'text'],
            ],
            'ads' => [
                'google_ads_id' => ['label' => 'Google Ads Conversion ID (AW-XXXXX)', 'type' => 'text'],
                'google_ads_conversion_label' => ['label' => 'Google Ads Conversion Label', 'type' => 'text'],
                'google_ads_remarketing_tag' => ['label' => 'Google Ads Remarketing Tag (script tambahan)', 'type' => 'textarea'],
                'meta_ads_extra_script' => ['label' => 'Meta Ads — Script Tambahan (opsional)', 'type' => 'textarea'],
                'custom_head_scripts' => ['label' => 'Custom Script di <head>', 'type' => 'textarea', 'help' => 'Akan diinject sebelum </head>'],
                'custom_body_scripts' => ['label' => 'Custom Script sebelum </body>', 'type' => 'textarea'],
            ],
        ];
    }
}
