<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // CS Agents
        Schema::create('cs_agents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('whatsapp'); // format: 628xxx
            $table->string('display_number')->nullable();
            $table->string('greeting')->nullable(); // pesan awal WA
            $table->string('avatar')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('click_count')->default(0);
            $table->timestamps();
        });

        // CS Click Log
        Schema::create('cs_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cs_agent_id')->constrained()->cascadeOnDelete();
            $table->string('ip', 45)->nullable();
            $table->string('page')->nullable();
            $table->string('device')->nullable();
            $table->timestamps();
        });

        // Artisan Log
        Schema::create('artisan_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('command');
            $table->longText('output')->nullable();
            $table->boolean('success')->default(true);
            $table->float('duration')->nullable(); // detik
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('artisan_logs');
        Schema::dropIfExists('cs_clicks');
        Schema::dropIfExists('cs_agents');
    }
};
