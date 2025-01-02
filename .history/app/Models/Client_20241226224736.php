<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
class Client extends Authenticatable implements JWTSubject// Hérite de Authenticatable
{
    use HasFactory;
    protected $table = 'clients'; // Si le nom de la table est différent de 'clients', spécifiez-le ici

    protected $fillable = [
        'email',
        'first_name',
        'last_name',
        'age',
        'image_path',
        'phone_number',
        'registration_date',
        'expiration_date',
        'user_id',
        'created_at',
        'password',
        'updated_at'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}