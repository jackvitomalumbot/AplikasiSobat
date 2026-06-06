<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'pengajar_id', 'nama_kelas', 'harga', 'deskripsi', 'thumbnail', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:0',
            'is_active' => 'boolean',
        ];
    }

    public function pengajar()
    {
        return $this->belongsTo(User::class, 'pengajar_id');
    }

    public function pertemuan()
    {
        return $this->hasMany(Pertemuan::class)->orderBy('tanggal');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function mahasiswa()
    {
        return $this->belongsToMany(User::class, 'enrollments', 'kelas_id', 'mahasiswa_id')
                    ->withPivot('payment_status', 'payment_id')
                    ->withTimestamps();
    }

    public function activeEnrollments()
    {
        return $this->enrollments()->where('payment_status', 'paid');
    }

    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
