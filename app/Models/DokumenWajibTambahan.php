<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DokumenWajibTambahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_dokumen',
        'kode_dokumen',
        'filepath',
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
