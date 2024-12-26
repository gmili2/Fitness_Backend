<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserAdmin extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password'];

    // Relation avec les utilisateurs
    public function users()
    {
        return $this->hasMany(User::class, 'user_admin_id');
    }
}
