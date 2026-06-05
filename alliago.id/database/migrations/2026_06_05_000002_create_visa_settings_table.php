<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visa_settings', function (Blueprint $table) {
            $table->id();
            $table->string('currency', 3)->default('IDR');
            $table->timestamps();
        });

        DB::table('visa_settings')->insert([
            'id' => 1,
            'currency' => 'IDR',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('visa_settings');
    }
};
