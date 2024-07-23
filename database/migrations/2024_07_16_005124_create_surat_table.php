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
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->string('nomor')->nullable();
            $table->unsignedBigInteger('id_kategori')->nullable();
            $table->string('judul')->nullable();
            $table->string('file_name')->nullable();
            $table->timestamps();
            $table->foreign('id_kategori')->references('id')->on('kategori')->onDelete('cascade');
            $table->index('id_kategori');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
