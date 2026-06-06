<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'kelas_id',
        'payment_status',
        'payment_id',
        'snap_token',
        'payment_type',
        'transaction_time',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'transaction_time' => 'datetime',
            'paid_at' => 'datetime',
        ];
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    public function isFailed(): bool
    {
        return in_array($this->payment_status, ['failed', 'expired', 'cancelled']);
    }
}
