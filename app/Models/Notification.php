<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre',
        'message',
        'type',
        'lu',
        'date_lecture',
        'action_url',
    ];

    protected $casts = [
        'lu' => 'boolean',
        'date_lecture' => 'datetime',
    ];

    // --- Relations ---

    /**
     * Une notification appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // --- Scopes ---

    /**
     * Scope pour les notifications non lues.
     */
    public function scopeNonLues($query)
    {
        return $query->where('lu', false);
    }

    /**
     * Scope pour les notifications par type.
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    // --- Méthodes ---

    /**
     * Marquer la notification comme lue.
     */
    public function marquerCommeLue()
    {
        $this->update([
            'lu' => true,
            'date_lecture' => now(),
        ]);
    }
}
