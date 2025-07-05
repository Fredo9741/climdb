<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffectationVehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicule_id',
        'user_id',
        'date_debut',
        'date_fin',
        'motif',
        'observations'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date'
    ];

    // --- Relations ---

    /**
     * Une affectation appartient à un véhicule.
     */
    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    /**
     * Une affectation est faite à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // --- Scopes ---

    /**
     * Scope pour les affectations actives.
     */
    public function scopeActives($query)
    {
        return $query->where('date_debut', '<=', now())
                    ->where(function($q) {
                        $q->whereNull('date_fin')
                          ->orWhere('date_fin', '>=', now());
                    });
    }
}
