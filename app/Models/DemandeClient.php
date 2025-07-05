<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class DemandeClient extends Model
{
    protected $table = 'demandes_clients';
    
    protected $fillable = [
        'client_id',
        'site_id',
        'type_demande',
        'description',
        'priorite',
        'statut_demande_id',
        'date_demande',
        'date_souhaite_intervention'
    ];

    protected $casts = [
        'date_demande' => 'datetime',
        'date_souhaite_intervention' => 'datetime'
    ];

    /**
     * Le client qui fait la demande
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Le site concerné par la demande
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Le statut de la demande
     */
    public function statutDemande(): BelongsTo
    {
        return $this->belongsTo(StatutDemande::class, 'statut_demande_id');
    }

    /**
     * Les interventions liées à cette demande
     */
    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class);
    }
} 