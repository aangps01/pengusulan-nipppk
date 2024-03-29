<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use App\Models\DokumenWajibTambahan;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nik',
        'email',
        'password',
        'role',
        'is_name_changed',
        'is_email_changed',
        'nomor_peserta',
        'gelar_depan',
        'gelar_belakang',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'pendidikan',
        'jabatan_dilamar',
        'unit_kerja',
        'tipe',
        'tahun',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_name_changed' => 'boolean',
        'is_email_changed' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function permohonan()
    {
        return $this->hasOne(Permohonan::class);
    }

    public function validator()
    {
        return $this->hasMany(Permohonan::class, 'validator_id');
    }

    public function dokumenWajibTambahan()
    {
        return $this->hasMany(DokumenWajibTambahan::class);
    }

    public function getRolenameAttribute()
    {
        return $this->role == 1 ? 'User' : 'Admin';
    }

    public function is_admin()
    {
        return $this->role == 2;
    }

    public function is_user()
    {
        return $this->role == 1;
    }

    public function scopeIsUser($query)
    {
        return $query->where('role', 1);
    }
}
