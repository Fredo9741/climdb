<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Panne extends Model
{
    protected $fillable = [
        'equipement_id',
        'description_panne',
        'actions_correctives',
        'statut_demande_id',
        'priorite',
        'date_constat',
        'date_resolution',
        'user_id',
    ];

    protected $casts = [
        'date_constat' => 'datetime',
        'date_resolution' => 'datetime',
    ];

    /**
     * Relation avec l'équipement concerné par la panne
     */
    public function equipement(): BelongsTo
    {
        return $this->belongsTo(Equipement::class);
    }

    /**
     * Relation avec le statut de la demande
     */
    public function statutDemande(): BelongsTo
    {
        return $this->belongsTo(StatutDemande::class, 'statut_demande_id');
    }

    /**
     * Relation avec l'utilisateur qui a créé la panne
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Les interventions liées à cette panne
     */
    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class);
    }
}
