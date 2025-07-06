<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class UserHabilitation extends Model
{
    use HasFactory;

    protected $table = 'user_habilitations';

    protected $fillable = [
        'user_id',
        'habilitation_id',
        'date_obtention',
        'date_echeance',
        'numero_certificat',
        'commentaires',
        'actif'
    ];

    protected $casts = [
        'date_obtention' => 'date',
        'date_echeance' => 'date',
        'actif' => 'boolean',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec l'habilitation
     */
    public function habilitation(): BelongsTo
    {
        return $this->belongsTo(Habilitation::class);
    }

    /**
     * Vérifier si l'habilitation est expirée
     */
    public function isExpired(): bool
    {
        return $this->date_echeance->isPast();
    }

    /**
     * Vérifier si l'habilitation expire bientôt (dans les 30 jours)
     */
    public function expiresSoon(): bool
    {
        return $this->date_echeance->diffInDays(now()) <= 30 && !$this->isExpired();
    }

    /**
     * Scope pour les habilitations actives
     */
    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }
}
