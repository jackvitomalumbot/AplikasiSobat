<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswa_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('universitas');
            $table->string('nim');
            $table->timestamps();
        });

        Schema::create('pengajar_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('spesialisasi')->nullable();
            $table->string('kontak')->nullable();
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajar_id')->constrained('users')->onDelete('cascade');
            $table->string('nama_kelas');
            $table->decimal('harga', 12, 0)->default(0);
            $table->text('deskripsi');
            $table->string('thumbnail')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('pertemuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal');
            $table->enum('tipe', ['pertemuan', 'tugas'])->default('pertemuan');
            $table->datetime('deadline')->nullable();
            $table->text('instruksi_tugas')->nullable();
            $table->timestamps();
        });

        Schema::create('materi_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_id')->constrained('pertemuan')->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->timestamps();
        });

        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->string('payment_status')->default('pending');
            $table->string('payment_id')->nullable();
            $table->timestamps();
            $table->unique(['mahasiswa_id', 'kelas_id']);
        });

        Schema::create('tugas_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_id')->constrained('pertemuan')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->string('file_path')->nullable();
            $table->text('catatan')->nullable();
            $table->decimal('nilai', 5, 1)->nullable();
            $table->timestamps();
            $table->unique(['pertemuan_id', 'mahasiswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_submissions');
        Schema::dropIfExists('enrollments');
        Schema::dropIfExists('materi_files');
        Schema::dropIfExists('pertemuan');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('pengajar_details');
        Schema::dropIfExists('mahasiswa_details');
    }
};
