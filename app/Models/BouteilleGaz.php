<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BouteilleGaz extends Model
{
    use HasFactory;

    protected $table = 'bouteilles_gaz';

    protected $fillable = [
        'numero_serie',
        'type_gaz_id',
        'capacite_kg',
        'poids_actuel_kg',
        'statut_bouteille_id',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'capacite_kg' => 'decimal:2',
        'poids_actuel_kg' => 'decimal:2',
    ];

    // --- Relations ---

    /**
     * Une bouteille de gaz appartient à un type de gaz.
     */
    public function typeGaz()
    {
        return $this->belongsTo(TypeGaz::class);
    }

    /**
     * Une bouteille de gaz a un statut.
     */
    public function statutBouteille()
    {
        return $this->belongsTo(StatutBouteille::class);
    }

    /**
     * Une bouteille de gaz peut être affectée à un utilisateur (technicien).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Une bouteille de gaz peut avoir plusieurs mouvements.
     */
    public function mouvements()
    {
        return $this->hasMany(MouvementGaz::class);
    }

    /**
     * Une bouteille de gaz peut avoir plusieurs photos (relation polymorphique).
     */
    public function photos()
    {
        return $this->morphMany(Photo::class, 'element');
    }

    /**
     * Une bouteille de gaz peut avoir plusieurs documents (relation polymorphique).
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'element');
    }

    // --- Scopes ---

    /**
     * Scope pour les bouteilles disponibles.
     */
    public function scopeDisponibles($query)
    {
        return $query->whereHas('statutBouteille', function ($q) {
            $q->where('nom', 'Disponible');
        });
    }

    /**
     * Scope pour les bouteilles affectées.
     */
    public function scopeAffectees($query)
    {
        return $query->whereNotNull('user_id');
    }

    /**
     * Scope pour les bouteilles par type de gaz.
     */
    public function scopeParTypeGaz($query, $typeGazId)
    {
        return $query->where('type_gaz_id', $typeGazId);
    }

    // --- Accesseurs ---

    /**
     * Pourcentage de remplissage de la bouteille.
     */
    public function getPourcentageRemplissageAttribute()
    {
        if ($this->capacite_kg > 0) {
            return round(($this->poids_actuel_kg / $this->capacite_kg) * 100, 1);
        }

        return 0;
    }

    /**
     * Quantité de gaz restante.
     */
    public function getQuantiteRestanteAttribute()
    {
        return max(0, $this->poids_actuel_kg);
    }

    /**
     * Vérification si la bouteille est vide.
     */
    public function getIsVideAttribute()
    {
        return $this->poids_actuel_kg <= 0;
    }

    /**
     * Vérification si la bouteille est pleine.
     */
    public function getIsPleineAttribute()
    {
        return $this->poids_actuel_kg >= $this->capacite_kg * 0.95; // 95% pour tolérance
    }

    /**
     * Nom complet avec numéro de série et type de gaz.
     */
    public function getNomCompletAttribute()
    {
        return $this->numero_serie.' ('.$this->typeGaz->nom ?? 'N/A'.')';
    }
}
