<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flight_pricing_configs', function (Blueprint $table) {
            $table->decimal('zz_markup', 12, 2)->nullable()->after('service_fee');
            $table->string('zz_name', 255)->nullable()->after('zz_markup');
            $table->string('zz_logo_url', 500)->nullable()->after('zz_name');
        });
    }

    public function down(): void
    {
        Schema::table('flight_pricing_configs', function (Blueprint $table) {
            $table->dropColumn(['zz_markup', 'zz_name', 'zz_logo_url']);
        });
    }
};
