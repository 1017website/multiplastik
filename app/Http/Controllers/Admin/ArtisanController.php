<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArtisanLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ArtisanController extends Controller
{
    // Whitelist command yang boleh dijalankan
    const ALLOWED = [
        'optimize'             => ['php artisan optimize',             'Optimize (cache config/route/view)'],
        'optimize:clear'       => ['php artisan optimize:clear',       'Clear semua cache'],
        'cache:clear'          => ['php artisan cache:clear',          'Clear application cache'],
        'view:clear'           => ['php artisan view:clear',           'Clear compiled views'],
        'config:clear'         => ['php artisan config:clear',         'Clear config cache'],
        'route:clear'          => ['php artisan route:clear',          'Clear route cache'],
        'config:cache'         => ['php artisan config:cache',         'Cache config'],
        'route:cache'          => ['php artisan route:cache',          'Cache routes'],
        'view:cache'           => ['php artisan view:cache',           'Compile semua views'],
        'migrate'              => ['php artisan migrate --force',      'Jalankan migrasi baru'],
        'migrate:status'       => ['php artisan migrate:status',       'Status migrasi'],
        'db:seed'              => ['php artisan db:seed --force',      'Jalankan seeder'],
        'storage:link'         => ['php artisan storage:link',         'Buat symlink storage'],
        'queue:restart'        => ['php artisan queue:restart',        'Restart queue worker'],
    ];

    public function index()
    {
        $logs = ArtisanLog::with('user')->orderByDesc('created_at')->limit(50)->get();
        return view('admin.artisan.index', ['allowed' => self::ALLOWED, 'logs' => $logs]);
    }

    public function run(Request $request)
    {
        $request->validate(['command' => 'required|string']);
        $cmd = $request->input('command');

        if (!array_key_exists($cmd, self::ALLOWED)) {
            return back()->withErrors(['command' => 'Command tidak diizinkan.']);
        }

        $start = microtime(true);
        $output = '';
        $success = true;

        try {
            Artisan::call($cmd, $cmd === 'migrate' || $cmd === 'db:seed' ? ['--force' => true] : []);
            $output = Artisan::output();
        } catch (\Throwable $e) {
            $output = $e->getMessage();
            $success = false;
        }

        $duration = round(microtime(true) - $start, 2);

        ArtisanLog::create([
            'user_id'  => auth()->id(),
            'command'  => 'php artisan ' . $cmd,
            'output'   => $output ?: '(no output)',
            'success'  => $success,
            'duration' => $duration,
        ]);

        return back()->with($success ? 'artisan_success' : 'artisan_error', [
            'command'  => 'php artisan ' . $cmd,
            'output'   => $output ?: '(no output)',
            'duration' => $duration,
        ]);
    }
}
