<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pertemuan extends Model
{
    use HasFactory;

    protected $table = 'pertemuan';

    protected $fillable = [
        'kelas_id', 'judul', 'deskripsi', 'tanggal', 'tipe', 'deadline', 'instruksi_tugas', 'youtube_url',
    ];

    /**
     * Mengekstrak YouTube video ID dari berbagai format URL
     * Mendukung: youtube.com/watch?v=ID, youtu.be/ID, youtube.com/embed/ID, youtube.com/shorts/ID
     */
    public function getYoutubeEmbedIdAttribute(): ?string
    {
        if (!$this->youtube_url) return null;

        $patterns = [
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $this->youtube_url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

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
