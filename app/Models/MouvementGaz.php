<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MouvementGaz extends Model
{
    use HasFactory;
    
    /**
     * Le nom de la table associée au modèle.
     */
    protected $table = 'mouvements_gaz';
    
    /**
     * Les attributs qui peuvent être assignés en masse.
     */
    protected $fillable = [
        'equipement_id',
        'type_gaz_id', 
        'intervention_id',
        'user_id',
        'type_mouvement',
        'quantite_kg',
        'date_mouvement',
        'observations'
    ];
    
    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'date_mouvement' => 'datetime',
        'quantite_kg' => 'float'
    ];
    
    // --- Relations ---
    
    /**
     * Un mouvement de gaz appartient à un équipement.
     */
    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }
    
    /**
     * Un mouvement de gaz appartient à un type de gaz.
     */
    public function typeGaz()
    {
        return $this->belongsTo(TypeGaz::class);
    }
    
    /**
     * Un mouvement de gaz peut appartenir à une intervention.
     */
    public function intervention()
    {
        return $this->belongsTo(Intervention::class);
    }
    
    /**
     * Un mouvement de gaz appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
