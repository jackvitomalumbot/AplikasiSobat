<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MateriFile extends Model
{
    protected $fillable = ['pertemuan_id', 'file_name', 'file_path', 'file_type'];

    public function pertemuan()
    {
        return $this->belongsTo(Pertemuan::class);
    }
}
