<?php

namespace App\Models;


use Laravel\Sanctum\HasApiTokens; 
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'ime',
        'prezime',
        'email',
        'password',
        'telefon',
        'google_id',
        'is_admin',
        'is_couirt',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_couirt' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return (bool) $this->is_admin;
    }

    public function isCouirt(): bool
    {
        return (bool) $this->is_couirt;
    }

    public function getFullNameAttribute()
    {
        return "{$this->ime} {$this->prezime}";
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }

    public function narudzbe()
    {
        return $this->hasMany(Narudzba::class, 'Kupac_ID', 'id');
    }

    public function recenzije()
    {
        return $this->hasMany(Recenzija::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}