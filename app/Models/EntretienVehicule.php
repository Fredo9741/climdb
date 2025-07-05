<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntretienVehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'user_id',
        'type_entretien',
        'date_entretien',
        'kilometrage',
        'cout',
        'description',
        'pieces_changees',
        'prochaine_echeance_km',
        'prochaine_echeance_date'
    ];

    protected $casts = [
        'date_entretien' => 'date',
        'prochaine_echeance_date' => 'date',
        'cout' => 'decimal:2',
        'kilometrage' => 'decimal:0',
        'prochaine_echeance_km' => 'decimal:0',
        'pieces_changees' => 'array'
    ];

    // --- Relations ---

    /**
     * Un entretien appartient à un véhicule.
     */
    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    /**
     * Un entretien est effectué par un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // --- Scopes ---

    /**
     * Scope pour les entretiens par type.
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_entretien', $type);
    }
}
