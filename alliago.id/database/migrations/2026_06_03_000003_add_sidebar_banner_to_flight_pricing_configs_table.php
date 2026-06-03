<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flight_pricing_configs', function (Blueprint $table) {
            $table->string('sidebar_banner_path', 500)->nullable();
            $table->string('sidebar_banner_image_url', 500)->nullable();
            $table->string('sidebar_banner_link', 500)->nullable();
            $table->boolean('is_sidebar_banner_active')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('flight_pricing_configs', function (Blueprint $table) {
            $table->dropColumn([
                'sidebar_banner_path',
                'sidebar_banner_image_url',
                'sidebar_banner_link',
                'is_sidebar_banner_active',
            ]);
        });
    }
};
