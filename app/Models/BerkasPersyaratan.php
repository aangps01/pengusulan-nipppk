<?php

namespace App\Models;

use App\Models\BerkasSementara;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function berkasSementara()
    {
        return $this->hasMany(BerkasSementara::class);
    }
}
