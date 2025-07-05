<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PersonneContact extends Model
{
    protected $table = 'personnes_contact';

    protected $fillable = [
        'nom',
        'prenom',
        'fonction',
        'telephone',
        'email',
        'notes',
    ];

    /**
     * Les sites associés à cette personne de contact
     */
    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class, 'personne_contact_site');
    }
}
