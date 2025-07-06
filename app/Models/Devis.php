<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Devis extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'site_id',
        'numero',
        'date_devis',
        'date_expiration',
        'montant_ht',
        'montant_ttc',
        'tva',
        'statut',
        'description',
        'conditions_paiement',
    ];

    protected $casts = [
        'date_devis' => 'date',
        'date_expiration' => 'date',
        'montant_ht' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
        'tva' => 'decimal:2',
    ];

    // --- Relations ---

    /**
     * Un devis appartient à un client.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Un devis peut appartenir à un site.
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Un devis peut avoir plusieurs équipements (relation many-to-many).
     */
    public function equipements()
    {
        return $this->belongsToMany(Equipement::class, 'devis_equipements')
            ->withPivot(['prix_unitaire', 'quantite', 'description'])
            ->withTimestamps();
    }

    /**
     * Un devis peut générer plusieurs factures.
     */
    public function factures()
    {
        return $this->hasMany(Facture::class);
    }

    // --- Scopes ---

    /**
     * Scope pour les devis actifs (non expirés).
     */
    public function scopeActifs($query)
    {
        return $query->where('date_expiration', '>=', now());
    }

    /**
     * Scope pour les devis par statut.
     */
    public function scopeParStatut($query, $statut)
    {
        return $query->where('statut', $statut);
    }

    // --- Accesseurs ---

    /**
     * Vérification si le devis est expiré.
     */
    public function getIsExpiredAttribute()
    {
        return $this->date_expiration < now();
    }

    /**
     * Calcul automatique du montant TTC à partir du HT et de la TVA.
     */
    public function getMontantTtcCalculeAttribute()
    {
        return $this->montant_ht + ($this->montant_ht * $this->tva / 100);
    }
}
