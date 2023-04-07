<?php

namespace App\Models;

use App\Models\BerkasPersyaratan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BerkasSementara extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'berkas_persyaratan_id',
        'permohonan_id',
        'filepath',
        'filename',
    ];

    public function berkasPersyaratan()
    {
        return $this->belongsTo(BerkasPersyaratan::class);
    }
}
