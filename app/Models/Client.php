<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Storage;

class Client extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

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

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            return Storage::url($this->image_path);
        }
        return null;
    }

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

    // Relation avec les scans
    public function scans()
    {
        return $this->hasMany(Scan::class);
    }
}