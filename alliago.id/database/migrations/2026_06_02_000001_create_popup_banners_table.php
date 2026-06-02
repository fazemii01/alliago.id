<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('popup_banners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('desktop_banner_path')->nullable();
            $table->string('desktop_banner_url')->nullable();
            $table->string('mobile_banner_path')->nullable();
            $table->string('mobile_banner_url')->nullable();
            $table->string('redirect_link')->nullable();
            $table->boolean('is_active')->default(false);
            $table->integer('delay_seconds')->default(3);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('popup_banners');
    }
};
