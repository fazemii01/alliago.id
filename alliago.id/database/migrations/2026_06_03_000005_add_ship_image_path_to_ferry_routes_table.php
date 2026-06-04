<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ferry_routes', function (Blueprint $table) {
            $table->string('ship_image_path')->nullable()->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('ferry_routes', function (Blueprint $table) {
            $table->dropColumn('ship_image_path');
        });
    }
};
