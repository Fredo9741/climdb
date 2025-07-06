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
        'annee_fabrication' => 'integer',
        'kilometrage_actuel' => 'integer',
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
        // Les affectations les plus récentes (date_debut la plus proche) en premier
        return $this->hasMany(AffectationVehicule::class)->orderByDesc('date_debut');
    }

    /**
     * Affectation active (en cours) du véhicule.
     * Retourne la dernière affectation sans date de fin ou dont la date de fin est dans le futur.
     */
    public function affectationActive()
    {
        return $this->hasOne(AffectationVehicule::class)
            ->where('date_debut', '<=', now())
            ->where(function ($q) {
                $q->whereNull('date_fin')
                  ->orWhere('date_fin', '>=', now());
            })
            ->orderByDesc('date_debut');
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
        // On utilise le kilométrage actuel saisi ou, si non disponible, le dernier relevé enregistré.
        $dernierKm = $this->kilometrage_actuel ?? $this->suivisKilometrage()->latest('date_releve')->value('kilometrage_actuel') ?? 0;
        return ceil($dernierKm / 10000) * 10000;
    }
}
