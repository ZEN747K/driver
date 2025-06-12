<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('drivers')) {
            return;
        }

        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender')->nullable();
            $table->string('password')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('id_card_path')->nullable();
            $table->string('driver_license_path')->nullable();
            $table->string('face_photo_path')->nullable();
            $table->string('vehicle_registration_path')->nullable();
            $table->string('compulsory_insurance_path')->nullable();
            $table->string('vehicle_insurance_path')->nullable();

            $table->enum('service_type', ['car', 'motorcycle', 'delivery']);
            $table->boolean('approved')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
