<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasSubmission extends Model
{
    protected $fillable = ['pertemuan_id', 'mahasiswa_id', 'file_path', 'catatan', 'nilai'];

    protected function casts(): array
    {
        return ['nilai' => 'decimal:1'];
    }

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }
}
