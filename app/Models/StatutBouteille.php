<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutBouteille extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'couleur'
    ];

    // --- Relations ---

    /**
     * Un statut peut être associé à plusieurs bouteilles de gaz.
     */
    public function bouteillesGaz()
    {
        return $this->hasMany(BouteilleGaz::class);
    }
}
