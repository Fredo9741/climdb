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
        'observations',
    ];

    /**
     * Les attributs qui doivent être castés.
     */
    protected $casts = [
        'date_mouvement' => 'datetime',
        'quantite_kg' => 'float',
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

    // --- Scopes ---

    /**
     * Scope pour les mouvements d'entrée.
     */
    public function scopeEntrees($query)
    {
        return $query->where('type_mouvement', 'entrée');
    }

    /**
     * Scope pour les mouvements de sortie.
     */
    public function scopeSorties($query)
    {
        return $query->where('type_mouvement', 'sortie');
    }

    /**
     * Scope pour les mouvements par période.
     */
    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_mouvement', [$dateDebut, $dateFin]);
    }

    // --- Accesseurs ---

    /**
     * Obtient le libellé du type de mouvement.
     */
    public function getTypeMouvementLibelleAttribute()
    {
        return ucfirst($this->type_mouvement);
    }

    /**
     * Obtient la quantité avec le signe (positif pour entrée, négatif pour sortie).
     */
    public function getQuantiteSigneeAttribute()
    {
        return $this->type_mouvement === 'entrée' ? $this->quantite_kg : -$this->quantite_kg;
    }
}
