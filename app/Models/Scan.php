<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'user_id', 'barcode', 'scanned_at', 'date_pointage_sortie'];

    // Relation avec le client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
