<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriqueAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'entite_type',
        'entite_id',
        'anciennes_valeurs',
        'nouvelles_valeurs',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'anciennes_valeurs' => 'array',
        'nouvelles_valeurs' => 'array'
    ];

    // --- Relations ---

    /**
     * Une action appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation polymorphique vers l'entité concernée.
     */
    public function entite()
    {
        return $this->morphTo();
    }

    // --- Scopes ---

    /**
     * Scope pour les actions par type.
     */
    public function scopeParAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope pour les actions par utilisateur.
     */
    public function scopeParUtilisateur($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
