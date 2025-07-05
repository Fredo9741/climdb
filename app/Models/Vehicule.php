<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'marque',
        'modele',
        'immatriculation',
        'annee_fabrication',
        'kilometrage_actuel',
        'type_carburant',
        'date_acquisition',
        'statut_vehicule_id',
        'notes',
    ];

    protected $casts = [
        'date_acquisition' => 'date',
        'kilometrage_actuel' => 'decimal:0',
        'annee_fabrication' => 'integer',
    ];

    // --- Relations ---

    /**
     * Un véhicule a un statut.
     */
    public function statutVehicule()
    {
        return $this->belongsTo(StatutVehicule::class);
    }

    /**
     * Un véhicule peut avoir plusieurs affectations.
     */
    public function affectations()
    {
        return $this->hasMany(AffectationVehicule::class);
    }

    /**
     * Un véhicule peut avoir plusieurs entretiens.
     */
    public function entretiens()
    {
        return $this->hasMany(EntretienVehicule::class);
    }

    /**
     * Un véhicule peut avoir plusieurs suivis de kilométrage.
     */
    public function suivisKilometrage()
    {
        return $this->hasMany(SuiviKilometrage::class);
    }

    /**
     * Un véhicule peut avoir plusieurs photos (relation polymorphique).
     */
    public function photos()
    {
        return $this->morphMany(Photo::class, 'element');
    }

    /**
     * Un véhicule peut avoir plusieurs documents (relation polymorphique).
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'element');
    }

    // --- Scopes ---

    /**
     * Scope pour les véhicules actifs.
     */
    public function scopeActifs($query)
    {
        return $query->whereHas('statutVehicule', function ($q) {
            $q->where('nom', '!=', 'Hors service');
        });
    }

    /**
     * Scope pour les véhicules par type de carburant.
     */
    public function scopeParCarburant($query, $carburant)
    {
        return $query->where('type_carburant', $carburant);
    }

    // --- Accesseurs ---

    /**
     * Âge du véhicule en années.
     */
    public function getAgeAttribute()
    {
        if ($this->annee_fabrication) {
            return now()->year - $this->annee_fabrication;
        }

        return null;
    }

    /**
     * Nom complet du véhicule.
     */
    public function getNomCompletAttribute()
    {
        return $this->marque.' '.$this->modele.' ('.$this->immatriculation.')';
    }

    /**
     * Prochaine révision basée sur le kilométrage.
     */
    public function getProchainEntretienKmAttribute()
    {
        // Logique basique : entretien tous les 10 000 km
        return ceil($this->kilometrage_actuel / 10000) * 10000;
    }
}
