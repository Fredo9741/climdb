<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PieceDetachee extends Model
{
    protected $table = 'pieces_detachees';

    protected $fillable = [
        'nom',
        'reference',
        'description',
        'prix_unitaire',
        'fournisseur',
        'stock_minimum',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'stock_minimum' => 'integer',
    ];

    /**
     * Les inventaires de cette pièce
     */
    public function inventaires(): HasMany
    {
        return $this->hasMany(InventairePiece::class);
    }
}
