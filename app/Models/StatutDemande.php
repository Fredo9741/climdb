<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class StatutDemande extends Model
{
    protected $table = 'statuts_demandes';

    protected $fillable = [
        'nom',
        'description',
    ];

    /**
     * Relation avec les pannes
     */
    public function pannes(): HasMany
    {
        return $this->hasMany(Panne::class, 'statut_demande_id');
    }
}
