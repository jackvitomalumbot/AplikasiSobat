<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajarUnggulan extends Model
{
    use HasFactory;

    protected $table = 'pengajar_unggulan';

    protected $fillable = [
        'nama',
        'spesialisasi',
        'foto',
        'deskripsi',
        'keahlian',
        'motivasi',
        'urutan',
        'aktif',
        'tipe',
    ];

    protected $casts = [
        'aktif'  => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Kembalikan URL foto (fallback ke ui-avatars jika kosong).
     */
    public function getFotoUrlAttribute(): string
    {
        if ($this->foto && filter_var($this->foto, FILTER_VALIDATE_URL)) {
            return $this->foto;
        }

        if ($this->foto) {
            return asset($this->foto);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama) . '&size=256&background=e9e2cc&color=635e4d';
    }

    /**
     * Keahlian sebagai array.
     */
    public function getKeahlianArrayAttribute(): array
    {
        if (!$this->keahlian) return [];
        return array_filter(array_map('trim', explode('|', $this->keahlian)));
    }
}
