<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flight_pricing_configs', function (Blueprint $table) {
            $table->string('banner_path', 500)->nullable();
            $table->string('banner_link', 500)->nullable();
            $table->boolean('is_banner_active')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('flight_pricing_configs', function (Blueprint $table) {
            $table->dropColumn(['banner_path', 'banner_link', 'is_banner_active']);
        });
    }
};
