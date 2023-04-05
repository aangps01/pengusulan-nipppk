<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBerkasPermohonan extends Model
{
    use HasFactory;

    protected $fillable = [
        'berkas_permohonan_id',
        'original_filename',
        'generated_filename',
        'filepath',
        'is_valid',
        'is_revisi',
        'keterangan',
    ];

    protected $casts = [
        'is_valid' => 'boolean',
        'is_revisi' => 'boolean',
    ];

    public function berkasPermohonan()
    {
        return $this->belongsTo(BerkasPermohonan::class);
    }
}
