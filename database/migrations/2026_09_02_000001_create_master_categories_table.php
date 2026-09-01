<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('master_category_id')
                ->nullable()
                ->after('brand_id')
                ->constrained('master_categories')
                ->nullOnDelete();
        });

        $definitions = [
            ['slug' => 'gelas', 'name' => 'Gelas', 'needles' => ['gelas', 'cup']],
            ['slug' => 'kresek', 'name' => 'Kresek', 'needles' => ['kresek']],
            ['slug' => 'sedotan', 'name' => 'Sedotan', 'needles' => ['sedotan', 'straw']],
            ['slug' => 'sendok-makan', 'name' => 'Sendok Makan', 'needles' => ['sendok']],
            ['slug' => 'tusuk-bambu', 'name' => 'Tusuk Bambu', 'needles' => ['tusuk']],
            ['slug' => 'styrofoam', 'name' => 'Styrofoam', 'needles' => ['styrofoam']],
            ['slug' => 'kantong-plastik', 'name' => 'Kantong Plastik', 'needles' => ['kantong plastik', 'plastik laundry']],
            ['slug' => 'thinwall', 'name' => 'Thinwall', 'needles' => ['thinwall']],
        ];

        $masterIds = [];
        $categories = DB::table('categories')->orderBy('id')->get();

        foreach ($categories as $index => $category) {
            $haystack = Str::lower($category->name.' '.$category->slug);
            $definition = collect($definitions)->first(
                fn (array $item) => Str::contains($haystack, $item['needles'])
            );

            $slug = $definition['slug'] ?? Str::slug($category->name);
            $name = $definition['name'] ?? $category->name;

            if (! isset($masterIds[$slug])) {
                $existingId = DB::table('master_categories')->where('slug', $slug)->value('id');
                $masterIds[$slug] = $existingId ?: DB::table('master_categories')->insertGetId([
                    'slug' => $slug,
                    'name' => $name,
                    'icon' => $category->icon,
                    // File lokal kategori berada di uploads/categories, bukan di folder
                    // master-categories. Biarkan frontend memakai fallback kategori agar
                    // path lama tetap benar; URL eksternal aman untuk dipakai langsung.
                    'image' => $category->image && str_starts_with($category->image, 'http')
                        ? $category->image
                        : null,
                    'description' => 'Pilihan '.$name.' dari berbagai brand.',
                    'sort_order' => $index,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('categories')
                ->where('id', $category->id)
                ->update(['master_category_id' => $masterIds[$slug]]);
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('master_category_id');
        });

        Schema::dropIfExists('master_categories');
    }
};
