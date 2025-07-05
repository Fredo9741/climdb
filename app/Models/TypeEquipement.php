<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeEquipement extends Model
{
    use HasFactory;

    protected $table = 'types_equipements';

    protected $fillable = ['nom', 'description'];

    // --- Relations ---

    /**
     * Un type d'équipement peut avoir plusieurs modèles.
     */
    public function modeles()
    {
        return $this->hasMany(Modele::class);
    }
}