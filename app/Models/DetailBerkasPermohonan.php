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

    public function getStatusAttribute()
    {
        if ($this->is_valid) {
            return 'Valid';
        } else if ($this->is_revisi) {
            return 'Revisi';
        } else {
            return 'Belum Verifikasi';
        }
    }

    public function getBadgeStatusAttribute()
    {
        if ($this->is_valid) {
            return '<span class="badge bg-success" style="width:120px;">Valid</span>';
        } else if ($this->is_revisi) {
            return '<span class="badge bg-danger" style="width:120px;">Revisi</span>';
        } else {
            return '<span class="badge bg-primary" style="width:120px;">Belum Verifikasi</span>';
        }
    }

    public function scopeRevisi($query)
    {
        return $query->where('is_revisi', true)->where('is_valid', false);
    }
}
