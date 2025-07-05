<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_document', 'chemin_stockage', 'type_document', 'description', 'element_id', 'element_type', 'user_id',
    ];

    // --- Relations ---

    /**
     * Un document appartient à un élément polymorphique.
     */
    public function element()
    {
        return $this->morphTo();
    }

    /**
     * Un document est uploadé par un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
