<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partnership_applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('whatsapp', 30);
            $table->string('email')->nullable();
            $table->string('city');
            $table->text('address')->nullable();
            $table->string('business_stage');
            $table->string('capital_range');
            $table->string('start_timeline');
            $table->json('preferred_products')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('new')->index();
            $table->text('admin_notes')->nullable();
            $table->string('source_url', 500)->nullable();
            $table->timestamp('consent_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partnership_applications');
    }
};
