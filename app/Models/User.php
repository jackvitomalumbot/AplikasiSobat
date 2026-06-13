<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'foto_profile',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /* ─── Relationships ─── */

    public function mahasiswaDetail()
    {
        return $this->hasOne(MahasiswaDetail::class);
    }

    public function pengajarDetail()
    {
        return $this->hasOne(PengajarDetail::class);
    }

    public function kelasAsTeacher()
    {
        return $this->hasMany(Kelas::class, 'pengajar_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'mahasiswa_id');
    }

    public function enrolledKelas()
    {
        return $this->belongsToMany(Kelas::class, 'enrollments', 'mahasiswa_id', 'kelas_id')
                    ->withPivot('payment_status', 'payment_id')
                    ->withTimestamps();
    }

    public function tugasSubmissions()
    {
        return $this->hasMany(TugasSubmission::class, 'mahasiswa_id');
    }

    /* ─── Helpers ─── */

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPengajar(): bool
    {
        return $this->role === 'pengajar';
    }

    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }
}
