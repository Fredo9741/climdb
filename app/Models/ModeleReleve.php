<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeleReleve extends Model
{
    use HasFactory;

    protected $table = 'modeles_releves';

    protected $fillable = ['nom', 'description'];

    // --- Relations ---

    /**
     * Un modèle de relevé peut être le modèle de relevé par défaut pour plusieurs modèles d'équipement.
     */
    public function modeles()
    {
        return $this->hasMany(Modele::class, 'modele_releve_defaut_id');
    }

    /**
     * Un modèle de relevé contient plusieurs éléments de relevé.
     */
    public function elementsModeleReleve()
    {
        return $this->hasMany(ElementModeleReleve::class);
    }

    /**
     * Un modèle de relevé peut être utilisé dans plusieurs interventions.
     */
    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'modele_releve_utilise_id');
    }
}