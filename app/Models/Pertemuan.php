<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertemuan extends Model
{
    use HasFactory;

    protected $table = 'pertemuan';

    protected $fillable = [
        'kelas_id', 'judul', 'deskripsi', 'tanggal', 'tipe', 'deadline', 'instruksi_tugas',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'deadline' => 'datetime',
        ];
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function materiFiles()
    {
        return $this->hasMany(MateriFile::class);
    }

    public function tugasSubmissions()
    {
        return $this->hasMany(TugasSubmission::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function isTugas(): bool
    {
        return $this->tipe === 'tugas';
    }

    public function isPertemuan(): bool
    {
        return $this->tipe === 'pertemuan';
    }
}
