<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->integer('durasi_menit')->default(60);
            $table->datetime('deadline')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('acak_soal')->default(false);
            $table->timestamps();
        });

        Schema::create('kuis_soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuis_id')->constrained('kuis')->onDelete('cascade');
            $table->integer('nomor');
            $table->enum('tipe', ['pilihan_ganda', 'essay']);
            $table->text('pertanyaan');
            $table->string('opsi_a')->nullable();
            $table->string('opsi_b')->nullable();
            $table->string('opsi_c')->nullable();
            $table->string('opsi_d')->nullable();
            $table->string('opsi_e')->nullable();
            $table->string('jawaban_benar')->nullable(); // A/B/C/D/E for pilihan_ganda, text for essay
            $table->integer('poin')->default(1);
            $table->timestamps();
        });

        Schema::create('kuis_hasil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuis_id')->constrained('kuis')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->integer('total_benar')->default(0);
            $table->integer('total_poin')->default(0);
            $table->integer('max_poin')->default(0);
            $table->decimal('nilai', 5, 1)->nullable(); // 0-100
            $table->datetime('waktu_mulai')->nullable();
            $table->datetime('waktu_selesai')->nullable();
            $table->timestamps();
            $table->unique(['kuis_id', 'mahasiswa_id']);
        });

        Schema::create('kuis_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuis_hasil_id')->constrained('kuis_hasil')->onDelete('cascade');
            $table->foreignId('kuis_soal_id')->constrained('kuis_soal')->onDelete('cascade');
            $table->text('jawaban')->nullable();
            $table->boolean('is_correct')->nullable();
            $table->integer('poin_didapat')->default(0);
            $table->timestamps();
            $table->unique(['kuis_hasil_id', 'kuis_soal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuis_jawaban');
        Schema::dropIfExists('kuis_hasil');
        Schema::dropIfExists('kuis_soal');
        Schema::dropIfExists('kuis');
    }
};
