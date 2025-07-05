<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventairePiece extends Model
{
    protected $table = 'inventaire_pieces';

    protected $fillable = [
        'piece_detachee_id',
        'site_id',
        'quantite_stock',
        'emplacement',
        'date_derniere_entree',
        'date_derniere_sortie',
    ];

    protected $casts = [
        'quantite_stock' => 'integer',
        'date_derniere_entree' => 'date',
        'date_derniere_sortie' => 'date',
    ];

    /**
     * La pièce détachée
     */
    public function pieceDetachee(): BelongsTo
    {
        return $this->belongsTo(PieceDetachee::class);
    }

    /**
     * Le site où est stockée la pièce
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }
}
