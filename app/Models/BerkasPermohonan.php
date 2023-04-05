<?php

namespace App\Models;

use App\Models\Permohonan;
use App\Models\BerkasPersyaratan;
use App\Models\DetailBerkasPermohonan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BerkasPermohonan extends Model
{
    use HasFactory;

    protected $fillable = [
        'permohonan_id',
        'berkas_persyaratan_id',
    ];

    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class);
    }

    public function berkasPersyaratan()
    {
        return $this->belongsTo(BerkasPersyaratan::class);
    }

    public function detailBerkasPermohonan()
    {
        return $this->hasMany(DetailBerkasPermohonan::class);
    }
}
