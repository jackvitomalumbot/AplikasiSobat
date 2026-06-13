<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisSoal extends Model
{
    protected $table = 'kuis_soal';

    protected $fillable = ['kuis_id', 'nomor', 'tipe', 'pertanyaan', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'opsi_e', 'jawaban_benar', 'poin'];

    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }

    public function isPilihanGanda(): bool
    {
        return $this->tipe === 'pilihan_ganda';
    }

    public function isEssay(): bool
    {
        return $this->tipe === 'essay';
    }
}
