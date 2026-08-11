<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengajar_unggulan', function (Blueprint $table) {
            // 'unggulan' = tampil di section Pengajar Unggulan (maks 3)
            // 'rekan'    = tampil di section Rekan Pengajar (tidak terbatas)
            $table->string('tipe', 20)->default('unggulan')->after('aktif');
        });
    }

    public function down(): void
    {
        Schema::table('pengajar_unggulan', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};

