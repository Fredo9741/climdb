<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Habilitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'duree_validite_mois',
        'actif'
    ];

    protected $casts = [
        'duree_validite_mois' => 'integer',
        'actif' => 'boolean',
    ];

    /**
     * Relation avec les habilitations des utilisateurs
     */
    public function userHabilitations(): HasMany
    {
        return $this->hasMany(UserHabilitation::class);
    }

    /**
     * Relation avec les utilisateurs via la table pivot
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_habilitations')
                    ->withPivot(['date_obtention', 'date_echeance', 'numero_certificat', 'commentaires', 'actif'])
                    ->withTimestamps();
    }

    /**
     * Scope pour les habilitations actives
     */
    public function scopeActives($query)
    {
        return $query->where('actif', true);
    }
}
