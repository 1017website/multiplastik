<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;

class TrackVisit
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // skip non-GET, asset, admin, dan ajax
        if (
            !$request->isMethod('GET')
            || $request->ajax()
            || $request->is('admin*')
            || $request->is('login')
            || $request->is('logout')
            || $request->is('uploads/*')
            || $request->is('build/*')
        ) {
            return $response;
        }

        // tentukan page_type dari path
        $path = $request->path() === '/' ? 'home' : $request->path();
        $pageType = 'home';
        if (str_starts_with($path, 'brand')) $pageType = 'brand';
        elseif (str_starts_with($path, 'product')) $pageType = 'product';
        elseif (str_starts_with($path, 'news')) $pageType = 'news';
        elseif (str_starts_with($path, 'search')) $pageType = 'search';

        // device detection sederhana
        $ua = $request->userAgent() ?? '';
        $device = 'desktop';
        if (preg_match('/mobile|android|iphone|ipod/i', $ua) && !preg_match('/ipad/i', $ua)) {
            $device = 'mobile';
        } elseif (preg_match('/ipad|tablet/i', $ua)) {
            $device = 'tablet';
        }

        try {
            PageVisit::create([
                'path' => $request->fullUrl(),
                'page_type' => $pageType,
                'referrer' => $request->header('referer'),
                'utm_source' => $request->query('utm_source'),
                'utm_medium' => $request->query('utm_medium'),
                'utm_campaign' => $request->query('utm_campaign'),
                'ip' => $request->ip(),
                'user_agent' => substr($ua, 0, 500),
                'device' => $device,
                'visited_date' => now()->toDateString(),
            ]);
        } catch (\Throwable $e) {
            // jangan ganggu user experience kalau gagal log
        }

        return $response;
    }
}
