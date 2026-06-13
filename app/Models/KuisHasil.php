<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KuisHasil extends Model
{
    protected $table = 'kuis_hasil';

    protected $fillable = ['kuis_id', 'mahasiswa_id', 'total_benar', 'total_poin', 'max_poin', 'nilai', 'waktu_mulai', 'waktu_selesai'];

    protected function casts(): array
    {
        return [
            'waktu_mulai' => 'datetime',
            'waktu_selesai' => 'datetime',
            'nilai' => 'decimal:1',
        ];
    }

    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function jawaban()
    {
        return $this->hasMany(KuisJawaban::class);
    }

    public function isCompleted(): bool
    {
        return $this->waktu_selesai !== null;
    }
}
