<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class UserModel extends Authenticatable implements JWTSubject
{
    public function getJWTIdentifier(){
        return $this->getkey();
    }

    public function getJWTCustomClaims(){
        return [];
    }

    use HasFactory;

    protected $table = 'm_user';
    protected $primaryKey = 'user_id';
    protected $fillable = [
        'username', 
        'password', 
        'nama', 
        'level_id', 
        'foto_profil', 
        'created_at', 
        'updated_at'
    ];

    protected $hidden = ['password']; // Tidak ditampilkan saat select
    protected $casts = ['password' => 'hashed']; // Password akan di-hash secara otomatis

    // Relasi ke tabel level
    public function level()
    {
        return $this->belongsTo(LevelModel::class, 'level_id', 'level_id');
    }

    public function getRoleName(): string 
    {
        return $this->level->level_nama;
    }
    public function hasRole($role): bool 
    {
        return $this->level->level_kode == $role;
    }

    /* Mendapatkan Kode Role */
    public function getRole()
    {
        return $this->level->level_kode;
    }
}