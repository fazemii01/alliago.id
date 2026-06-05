<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flight_pricing_configs', function (Blueprint $table) {
            $table->string('currency', 3)->default('IDR')->after('label');
        });
    }

    public function down(): void
    {
        Schema::table('flight_pricing_configs', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
};
