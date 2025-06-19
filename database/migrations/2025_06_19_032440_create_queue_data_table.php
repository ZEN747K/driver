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
        if (Schema::hasTable('queue_Data')) {
            return;
        }

        Schema::create('queue_Data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('customer_phone');
            $table->string('pickup_location');
            $table->string('destination');
            $table->timestamp('first_time')->useCurrent();
            $table->enum('status', ['waiting', 'pickuped', 'abort'])->default('waiting');
            $table->timestamp('pickup_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue_Data');
    }
};