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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('system_no')->nullable();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete();
            $table->uuid('icruise_product_id')->nullable()->unique();
            $table->uuid('icruise_object_id')->nullable()->index();
            $table->uuid('icruise_vehicle_id')->nullable()->index();
            $table->uuid('icruise_tot_id')->nullable()->index();
            $table->string('imei')->nullable();
            $table->string('name');
            $table->string('model')->nullable();
            $table->string('brand')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('iccid')->nullable();
            $table->unsignedTinyInteger('tracker_status')->nullable();
            $table->string('timezone')->nullable();
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
        Schema::dropIfExists('devices');
    }
};
