<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElementModeleReleve extends Model
{
    protected $table = 'elements_modele_releve';

    protected $fillable = [
        'modele_releve_id',
        'type_mesure',
        'unite_attendue',
        'emplacement_suggerer',
        'valeur_min_attendue',
        'valeur_max_attendue',
        'obligatoire',
        'ordre',
        'commentaire_guide',
    ];

    protected $casts = [
        'valeur_min_attendue' => 'float',
        'valeur_max_attendue' => 'float',
        'obligatoire' => 'boolean',
        'ordre' => 'integer',
    ];

    // --- Relations ---

    /**
     * Un élément de modèle de relevé appartient à un modèle de relevé.
     */
    public function modeleReleve()
    {
        return $this->belongsTo(ModeleReleve::class);
    }
}
