<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('device_states', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->unique()->constrained()->cascadeOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->jsonb('geo_address')->nullable();
            $table->decimal('speed', 8, 2)->nullable();
            $table->integer('angle')->nullable();
            $table->decimal('altitude', 10, 2)->nullable();
            $table->boolean('gps_status')->nullable();
            $table->timestamp('gps_time')->nullable();
            $table->string('ignition')->nullable();
            $table->decimal('oil', 10, 2)->nullable();
            $table->decimal('voltage', 10, 2)->nullable();
            $table->decimal('mileage', 12, 2)->nullable();
            $table->string('temperature')->nullable();
            $table->jsonb('payload')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_states');
    }
};
