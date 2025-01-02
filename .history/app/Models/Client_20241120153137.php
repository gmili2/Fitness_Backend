<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
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
    'updated_at'
];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}