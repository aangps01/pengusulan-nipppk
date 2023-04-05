<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasPersyaratan extends Model
{
    use HasFactory;

    protected $fillable = [
        'berkas_key',
        'nama',
        'deskripsi',
        'is_required',
        'batas_ukuran',
        'nama_format',
        'tipe_format',
        'is_active',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function berkasPermohonan()
    {
        return $this->hasMany(BerkasPermohonan::class);
    }
}
