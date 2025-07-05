<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceProgrammee extends Model
{
    protected $table = 'maintenances_programmees';

    protected $fillable = [
        'equipement_id',
        'type_maintenance',
        'frequence',
        'prochaine_maintenance',
        'derniere_maintenance',
        'description',
        'statut',
        'cout_estime',
    ];

    protected $casts = [
        'prochaine_maintenance' => 'date',
        'derniere_maintenance' => 'date',
        'cout_estime' => 'decimal:2',
    ];

    /**
     * L'équipement concerné par cette maintenance
     */
    public function equipement(): BelongsTo
    {
        return $this->belongsTo(Equipement::class);
    }
}
