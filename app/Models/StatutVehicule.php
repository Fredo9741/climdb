<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutVehicule extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'couleur'
    ];

    // --- Relations ---

    /**
     * Un statut peut être associé à plusieurs véhicules.
     */
    public function vehicules()
    {
        return $this->hasMany(Vehicule::class);
    }
}
