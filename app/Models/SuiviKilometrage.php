<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuiviKilometrage extends Model
{
    use HasFactory;

    protected $table = 'suivi_kilometrage';

    protected $fillable = [
        'vehicule_id',
        'user_id',
        'kilometrage',
        'date_releve',
        'notes',
    ];

    protected $casts = [
        'date_releve' => 'datetime',
        'kilometrage' => 'decimal:0',
    ];

    // --- Relations ---

    /**
     * Un suivi appartient à un véhicule.
     */
    public function vehicule()
    {
        return $this->belongsTo(Vehicule::class);
    }

    /**
     * Un suivi est effectué par un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
