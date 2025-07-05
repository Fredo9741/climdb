<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'adresse', 'ville', 'code_postal', 'pays', 'telephone', 'email',
    ];

    // --- Relations ---

    /**
     * Un client peut avoir plusieurs sites.
     */
    public function sites()
    {
        return $this->hasMany(Site::class);
    }

    /**
     * Un client peut avoir plusieurs devis.
     */
    public function devis()
    {
        return $this->hasMany(Devis::class);
    }

    /**
     * Un client peut avoir plusieurs factures.
     */
    public function factures()
    {
        return $this->hasMany(Facture::class);
    }
}
