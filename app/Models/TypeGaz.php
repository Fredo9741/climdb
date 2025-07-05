<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeGaz extends Model
{
    use HasFactory;

    protected $table = 'types_gaz';

    protected $fillable = ['nom', 'formule_chimique', 'description'];

    // --- Relations ---

    /**
     * Un type de gaz peut être associé à plusieurs modèles d'équipement.
     */
    public function modeles()
    {
        return $this->hasMany(Modele::class);
    }

    /**
     * Un type de gaz peut être contenu dans plusieurs bouteilles de gaz.
     */
    public function bouteillesGaz()
    {
        return $this->hasMany(BouteilleGaz::class);
    }

    /**
     * Un type de gaz peut être impliqué dans plusieurs mouvements de gaz.
     */
    public function mouvementsGaz()
    {
        return $this->hasMany(MouvementGaz::class);
    }
}
