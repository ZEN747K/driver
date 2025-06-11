<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasColumn('admins', 'is_super')) {
            return;
        }
        Schema::table('admins', function (Blueprint $table) {
            $table->boolean('is_super')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('is_super');
        });
    }
};
