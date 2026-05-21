<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ====================== SITE SETTINGS (key-value) ======================
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->string('group')->default('general'); // general, contact, seo, analytics, ads, hero_stats, about, keunggulan
            $table->string('type')->default('text');     // text, textarea, image, html, number
            $table->timestamps();
        });

        // ====================== BRANDS ======================
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->string('logo')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ====================== CATEGORIES ======================
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->string('icon')->nullable(); // contoh: fas fa-coffee
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['brand_id', 'slug']);
        });

        // ====================== PRODUCTS ======================
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('name');
            $table->string('image')->nullable(); // gambar utama
            $table->text('description')->nullable();
            $table->json('specs')->nullable(); // [["Ukuran","6 Oz"], ...]
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['category_id', 'slug']);
        });

        // ====================== PRODUCT IMAGES (gallery) ======================
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // ====================== SLIDES (hero slider) ======================
        Schema::create('slides', function (Blueprint $table) {
            $table->id();
            $table->string('tag')->nullable();
            $table->string('title_top')->nullable();   // contoh: "Solusi Plastik"
            $table->string('title_em')->nullable();    // contoh: "& Kemasan" (warna merah)
            $table->string('title_bottom')->nullable();// contoh: "Terlengkap"
            $table->text('subtitle')->nullable();
            $table->string('background_image')->nullable();
            $table->string('btn_primary_text')->nullable();
            $table->string('btn_primary_link')->nullable(); // bisa url atau action seperti nav:brands
            $table->string('btn_primary_icon')->nullable(); // fas fa-layer-group
            $table->string('btn_secondary_text')->nullable();
            $table->string('btn_secondary_link')->nullable();
            $table->string('btn_secondary_icon')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ====================== PROMO BAR ITEMS ======================
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ====================== NEWS ======================
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('category')->nullable(); // Promo, Produk Baru, Tips & Info, dll
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->string('image')->nullable();
            $table->longText('content')->nullable(); // HTML
            $table->date('published_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ====================== PAGE VISITS (analytics) ======================
        Schema::create('page_visits', function (Blueprint $table) {
            $table->id();
            $table->string('path')->nullable();
            $table->string('page_type')->nullable();   // home, brand, product, news, dll
            $table->string('referrer')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('ip', 45)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->string('device')->nullable();      // mobile, tablet, desktop
            $table->string('country')->nullable();
            $table->date('visited_date')->index();
            $table->timestamps();

            $table->index(['utm_source', 'visited_date']);
            $table->index(['page_type', 'visited_date']);
        });

        // ====================== ADMIN USERS ======================
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        // role kolom tambahan untuk users
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('admin')->after('password');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('page_visits');
        Schema::dropIfExists('news');
        Schema::dropIfExists('promos');
        Schema::dropIfExists('slides');
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('site_settings');
    }
};
