<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'guru_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // Relasi untuk murid - guru
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    // Relasi untuk guru - murid
    public function murid()
    {
        return $this->hasMany(User::class, 'guru_id');
    }

    // Relasi kebiasaan untuk murid
    public function kebiasaan()
    {
        return $this->hasMany(Kebiasaan::class, 'murid_id');
    }

    // METHOD: Cek role user - PERBAIKAN DI SINI
    public function isMurid()
    {
        return $this->role === 'murid';
    }

    public function isGuru()
    {
        return $this->role === 'guru';
    }

    public function isPengawas()
    {
        return $this->role === 'pengawas';
    }

}
