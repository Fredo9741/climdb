<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'nom', 'adresse', 'ville', 'code_postal', 'pays', 'latitude', 'longitude',
    ];

    // --- Relations ---

    /**
     * Un site appartient à un client.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Un site peut avoir plusieurs équipements.
     */
    public function equipements()
    {
        return $this->hasMany(Equipement::class);
    }

    /**
     * Un site peut avoir plusieurs personnes de contact.
     */
    public function personnesContact()
    {
        return $this->belongsToMany(PersonneContact::class, 'personne_contact_site');
    }

    /**
     * Un site peut avoir un inventaire de pièces.
     */
    public function inventairePieces()
    {
        return $this->hasMany(InventairePiece::class);
    }
}
