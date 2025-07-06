<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class Intervention extends Model
{
    protected $fillable = [
        'demande_client_id',
        'panne_id',
        'maintenance_programmee_id',
        'date_planifiee',
        'date_debut',
        'date_fin',
        'technicien_id',
        'rapport',
        'statut',
        'modele_releve_utilise_id',
    ];

    protected $casts = [
        'date_planifiee' => 'datetime',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    /**
     * Relation avec la demande client associée
     */
    public function demandeClient(): BelongsTo
    {
        return $this->belongsTo(DemandeClient::class, 'demande_client_id');
    }

    /**
     * Relation avec la panne associée
     */
    public function panne(): BelongsTo
    {
        return $this->belongsTo(Panne::class);
    }

    /**
     * Relation avec la maintenance programmée
     */
    public function maintenanceProgrammee(): BelongsTo
    {
        return $this->belongsTo(MaintenanceProgrammee::class, 'maintenance_programmee_id');
    }

    /**
     * Relation avec le technicien affecté
     */
    public function technicien(): BelongsTo
    {
        return $this->belongsTo(User::class, 'technicien_id');
    }

    /**
     * Relation avec le modèle de relevé utilisé
     */
    public function modeleReleveUtilise(): BelongsTo
    {
        return $this->belongsTo(ModeleReleve::class, 'modele_releve_utilise_id');
    }

    /**
     * Relation avec les pièces détachées utilisées
     */
    public function piecesDetachees(): BelongsToMany
    {
        return $this->belongsToMany(PieceDetachee::class, 'intervention_piece_detachee')
            ->withPivot('quantite_utilisee')
            ->withTimestamps();
    }

    /**
     * Accesseur site: permet d'accéder directement au site lié à l'intervention (via panne -> équipement -> site).
     */
    public function getSiteAttribute()
    {
        return $this->panne?->equipement?->site;
    }
}
