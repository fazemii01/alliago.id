<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_pricing_configs', function (Blueprint $table) {
            $table->id();
            $table->string('label')->default('Default');
            $table->decimal('addon_cost', 12, 2)->default(0);
            $table->decimal('service_fee', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_pricing_configs');
    }
};
