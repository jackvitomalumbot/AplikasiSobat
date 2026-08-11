<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajar_unggulan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('spesialisasi')->nullable();
            $table->string('foto')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('keahlian')->nullable(); // dipisah |
            $table->string('motivasi')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajar_unggulan');
    }
};

