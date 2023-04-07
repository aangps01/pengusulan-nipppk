<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permohonan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'keterangan',
        'tanggal_validasi',
        'validator_id',
    ];

    protected $casts = [
        'tanggal_validasi' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validator_id');
    }

    public function berkasPermohonan()
    {
        return $this->hasMany(BerkasPermohonan::class);
    }

    public function getStatusNameAttribute()
    {
        if ($this->status == 1) {
            return 'Usulan Baru';
        } else if ($this->status == 2) {
            return 'Sedang Verifikasi';
        } else if ($this->status == 3) {
            return 'Revisi';
        } else if ($this->status == 4) {
            return 'Verifikasi Ulang';
        } else if ($this->status == 5) {
            return 'Selesai';
        } else if ($this->status == 6) {
            return 'Ditolak';
        }
    }

    public function getBadgeStatusAttribute()
    {
        if ($this->status == 1) {
            return '<span class="badge bg-primary">Pengajuan Baru</span>';
        } else if ($this->status == 2) {
            return '<span class="badge bg-warning">Sedang Verifikasi</span>';
        } else if ($this->status == 3) {
            return '<span class="badge bg-danger">Revisi</span>';
        } else if ($this->status == 4) {
            return '<span class="badge bg-info">Verifikasi Ulang</span>';
        } else if ($this->status == 5) {
            return '<span class="badge bg-success">Selesai</span>';
        } else if ($this->status == 6) {
            return '<span class="badge bg-secondary">Ditolak</span>';
        }
    }
}
