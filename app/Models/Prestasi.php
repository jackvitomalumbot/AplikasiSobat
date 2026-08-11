<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestasi extends Model
{
    use HasFactory;

    protected $table = 'prestasi';

    protected $fillable = [
        'judul',
        'deskripsi',
        'foto',
        'tipe',      // 'featured' | 'mahasiswa' | 'pengajar'
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'aktif'  => 'boolean',
        'urutan' => 'integer',
    ];

    public function getFotoUrlAttribute(): string
    {
        if (!$this->foto) {
            return 'https://ui-avatars.com/api/?name=' . urlencode($this->judul) . '&size=400&background=e9e2cc&color=635e4d';
        }
        if (filter_var($this->foto, FILTER_VALIDATE_URL)) {
            return $this->foto;
        }
        return asset($this->foto);
    }

    public function getTipeLabelAttribute(): string
    {
        return match ($this->tipe) {
            'featured'  => 'Prestasi Utama',
            'mahasiswa' => 'Prestasi Mahasiswa',
            'pengajar'  => 'Prestasi Pengajar',
            default     => ucfirst($this->tipe),
        };
    }
}
