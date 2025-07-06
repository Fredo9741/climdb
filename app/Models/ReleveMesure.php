<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReleveMesure extends Model
{
    use HasFactory;

    protected $table = 'releves_mesures';

    protected $fillable = [
        'intervention_id',
        'equipement_id',
        'user_id',
        'type_mesure',
        'valeur',
        'unite',
        'date_mesure',
        'emplacement_mesure',
        'commentaire',
    ];

    protected $casts = [
        'date_mesure' => 'datetime',
        'valeur' => 'float',
    ];

    // --- Relations ---

    /**
     * Un relevé appartient à un équipement.
     */
    public function equipement()
    {
        return $this->belongsTo(Equipement::class);
    }

    /**
     * Un relevé utilise un modèle de relevé.
     */
    public function modeleReleve()
    {
        return $this->belongsTo(ModeleReleve::class);
    }

    /**
     * Un relevé est effectué par un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
