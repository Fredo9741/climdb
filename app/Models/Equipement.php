<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipement extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id', 'modele_id', 'numero_serie', 'nom', 'description',
        'date_installation', 'date_derniere_maintenance', 'localisation_precise', 'etat',
    ];

    // --- Relations ---

    /**
     * Un équipement appartient à un site.
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Un équipement appartient à un modèle.
     */
    public function modele()
    {
        return $this->belongsTo(Modele::class);
    }

    /**
     * Un équipement peut avoir plusieurs pannes.
     */
    public function pannes()
    {
        return $this->hasMany(Panne::class);
    }

    /**
     * Un équipement peut avoir plusieurs maintenances programmées.
     */
    public function maintenancesProgrammees()
    {
        return $this->hasMany(MaintenanceProgrammee::class);
    }

    /**
     * Un équipement peut avoir plusieurs mouvements de gaz.
     */
    public function mouvementsGaz()
    {
        return $this->hasMany(MouvementGaz::class);
    }

    /**
     * Un équipement peut avoir plusieurs photos (relation polymorphique).
     */
    public function photos()
    {
        return $this->morphMany(Photo::class, 'element');
    }

    /**
     * Un équipement peut avoir plusieurs documents (relation polymorphique).
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'element');
    }

    /**
     * Un équipement peut avoir plusieurs relevés de mesures.
     */
    public function relevesMesures()
    {
        return $this->hasMany(ReleveMesure::class);
    }
}
