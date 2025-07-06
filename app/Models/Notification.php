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
        'type_notification',
        'lien_associe',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
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
        return $query->whereNull('read_at');
    }

    /**
     * Scope pour les notifications par type.
     */
    public function scopeParType($query, $type)
    {
        return $query->where('type_notification', $type);
    }

    // --- Méthodes ---

    /**
     * Marquer la notification comme lue.
     */
    public function marquerCommeLue()
    {
        $this->update([
            'read_at' => now(),
        ]);
    }
}
