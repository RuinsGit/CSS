<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            
            // Çok dilli alanlar
            $table->string('name_az')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_ru')->nullable();
            
            $table->string('position_az')->nullable();
            $table->string('position_en')->nullable();
            $table->string('position_ru')->nullable();
            
            $table->text('biography_az')->nullable();
            $table->text('biography_en')->nullable();
            $table->text('biography_ru')->nullable();
            
            // Diğer alanlar
            $table->string('image')->nullable(); // Profil resmi
            $table->json('social_accounts')->nullable(); // Sosyal medya hesapları JSON formatında
            $table->integer('order')->default(0); // Sıralama
            $table->boolean('status')->default(true); // Durum
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
}; 