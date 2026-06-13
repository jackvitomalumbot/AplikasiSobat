<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    protected $table = 'kuis';

    protected $fillable = ['kelas_id', 'judul', 'deskripsi', 'durasi_menit', 'deadline', 'is_active', 'acak_soal'];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'is_active' => 'boolean',
            'acak_soal' => 'boolean',
        ];
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function soal()
    {
        return $this->hasMany(KuisSoal::class)->orderBy('nomor');
    }

    public function hasil()
    {
        return $this->hasMany(KuisHasil::class);
    }

    public function isExpired(): bool
    {
        return $this->deadline && $this->deadline->isPast();
    }
}
