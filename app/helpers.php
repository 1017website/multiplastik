<?php

use App\Models\SiteSetting;

if (!function_exists('setting')) {
    /**
     * Ambil nilai site setting.
     */
    function setting(string $key, $default = null)
    {
        return SiteSetting::get($key, $default);
    }
}

if (!function_exists('media_url')) {
    /**
     * Resolusi URL media: jika sudah http kembalikan apa adanya,
     * jika path lokal (uploads/...) prefix dengan asset().
     */
    function media_url(?string $value): ?string
    {
        if (!$value) return null;
        if (str_starts_with($value, 'http')) return $value;
        if (str_starts_with($value, 'uploads/')) return asset($value);
        return asset('uploads/' . $value);
    }
}

if (!function_exists('wa_link')) {
    /**
     * Buat link WhatsApp dari nomor di settings.
     */
    function wa_link(?string $text = null): string
    {
        $num = SiteSetting::get('contact_whatsapp', '6281234567890');
        $num = preg_replace('/[^0-9]/', '', $num);
        $url = 'https://wa.me/' . $num;
        if ($text) $url .= '?text=' . urlencode($text);
        return $url;
    }
}
