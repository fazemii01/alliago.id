<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ferry_routes', function (Blueprint $table) {
            $table->id();
            $table->string('origin');
            $table->string('destination');
            $table->unsignedInteger('price');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('ferry_routes')->insert([
            ['origin' => 'Pidi',         'destination' => 'Dumai',         'price' => 1125000, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['origin' => 'Pidi',         'destination' => 'Tanjung Balai', 'price' => 1125000, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['origin' => 'Stulang Laut', 'destination' => 'Batam',         'price' => 900000,  'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('ferry_routes');
    }
};
