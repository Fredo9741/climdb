<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modele extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_equipement_id', 'marque', 'nom', 'reference_constructeur',
        'description', 'quantite_gaz_kg', 'type_gaz_id', 'modele_releve_defaut_id',
    ];

    // --- Relations ---

    /**
     * Un modèle appartient à un type d'équipement.
     */
    public function typeEquipement()
    {
        return $this->belongsTo(TypeEquipement::class);
    }

    /**
     * Un modèle utilise un type de gaz.
     */
    public function typeGaz()
    {
        return $this->belongsTo(TypeGaz::class);
    }

    /**
     * Un modèle a plusieurs équipements.
     */
    public function equipements()
    {
        return $this->hasMany(Equipement::class);
    }

    /**
     * Un modèle peut avoir un modèle de relevé par défaut.
     */
    public function modeleReleveDefaut()
    {
        return $this->belongsTo(ModeleReleve::class, 'modele_releve_defaut_id');
    }

    /**
     * Un modèle peut avoir plusieurs photos (relation polymorphique).
     */
    public function photos()
    {
        return $this->morphMany(Photo::class, 'element');
    }

    /**
     * Un modèle peut avoir plusieurs documents (relation polymorphique).
     */
    public function documents()
    {
        return $this->morphMany(Document::class, 'element');
    }
}
