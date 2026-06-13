<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisJawaban extends Model
{
    protected $table = 'kuis_jawaban';

    protected $fillable = ['kuis_hasil_id', 'kuis_soal_id', 'jawaban', 'is_correct', 'poin_didapat'];

    protected function casts(): array
    {
        return ['is_correct' => 'boolean'];
    }

    public function hasil()
    {
        return $this->belongsTo(KuisHasil::class, 'kuis_hasil_id');
    }

    public function soal()
    {
        return $this->belongsTo(KuisSoal::class, 'kuis_soal_id');
    }
}
