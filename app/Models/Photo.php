<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_fichier', 'chemin_stockage', 'description', 'user_id',
    ];

    // --- Relations ---

    /**
     * Une photo appartient à un élément polymorphique (équipement, modèle, etc.).
     */
    public function element()
    {
        return $this->morphTo();
    }

    /**
     * Une photo est uploadée par un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
