<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'devis_id',
        'client_id',
        'numero_facture',
        'date_facture',
        'date_echeance',
        'statut',
        'description',
    ];

    protected $casts = [
        'date_facture' => 'date',
        'date_echeance' => 'date',
    ];

    // --- Relations ---

    /**
     * Une facture appartient à un devis.
     */
    public function devis()
    {
        return $this->belongsTo(Devis::class);
    }

    /**
     * Une facture appartient à un client.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Une facture peut appartenir à un site.
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Une facture peut avoir plusieurs équipements (relation many-to-many).
     */
    public function equipements()
    {
        return $this->belongsToMany(Equipement::class, 'facture_equipements')
            ->withPivot(['prix_unitaire', 'quantite', 'description'])
            ->withTimestamps();
    }

    // --- Scopes ---

    /**
     * Scope pour les factures payées.
     */
    public function scopePayees($query)
    {
        return $query->where('statut', 'payee');
    }

    /**
     * Scope pour les factures en attente.
     */
    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    /**
     * Scope pour les factures par statut.
     */
    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    // --- Accesseurs ---

    /**
     * Vérification si la facture est en retard.
     */
    public function getIsEnRetardAttribute()
    {
        return $this->date_echeance < now() && $this->statut !== 'payee';
    }

    /**
     * Nombre de jours de retard.
     */
    public function getJoursRetardAttribute()
    {
        if ($this->is_en_retard) {
            return now()->diffInDays($this->date_echeance);
        }

        return 0;
    }
}
