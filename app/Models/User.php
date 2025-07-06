<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // Ajouté pour Laravel Sanctum (API authentification)
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'telephone',
        'qualification',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // --- Début des Relations Eloquent ---

    /**
     * Un utilisateur peut avoir plusieurs notifications.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Un utilisateur peut effectuer plusieurs actions (historique).
     */
    public function historiquesActions()
    {
        return $this->hasMany(HistoriqueAction::class);
    }

    /**
     * Un utilisateur peut être affecté à plusieurs interventions.
     */
    public function interventions()
    {
        return $this->hasMany(Intervention::class, 'technicien_id');
    }

    /**
     * Un utilisateur peut être l'auteur de plusieurs pannes.
     */
    public function pannes()
    {
        return $this->hasMany(Panne::class);
    }

    /**
     * Un utilisateur peut être l'auteur de plusieurs maintenances programmées.
     */
    public function maintenancesProgrammees()
    {
        return $this->hasMany(MaintenanceProgrammee::class);
    }

    /**
     * Un utilisateur peut être responsable de plusieurs mouvements de gaz.
     */
    public function mouvementsGaz()
    {
        return $this->hasMany(MouvementGaz::class);
    }

    /**
     * Un utilisateur peut avoir plusieurs bouteilles de gaz affectées.
     */
    public function bouteillesGaz()
    {
        return $this->hasMany(BouteilleGaz::class);
    }

    /**
     * Un utilisateur peut avoir plusieurs relevés de mesures.
     */
    public function relevesMesures()
    {
        return $this->hasMany(ReleveMesure::class);
    }

    /**
     * Un utilisateur peut uploader plusieurs photos.
     */
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    /**
     * Un utilisateur peut uploader plusieurs documents.
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    /**
     * Un utilisateur peut être affecté à plusieurs véhicules.
     */
    public function affectationsVehicules()
    {
        return $this->hasMany(AffectationVehicule::class);
    }

    /**
     * Un utilisateur peut effectuer plusieurs suivis de kilométrage.
     */
    public function suiviKilometrage()
    {
        return $this->hasMany(SuiviKilometrage::class);
    }

    /**
     * Un utilisateur peut avoir plusieurs habilitations.
     */
    public function userHabilitations()
    {
        return $this->hasMany(UserHabilitation::class);
    }

    /**
     * Relation avec les habilitations via la table pivot
     */
    public function habilitations()
    {
        return $this->belongsToMany(Habilitation::class, 'user_habilitations')
                    ->withPivot(['date_obtention', 'date_echeance', 'numero_certificat', 'commentaires', 'actif'])
                    ->withTimestamps();
    }

    /**
     * Vérifier si l'utilisateur est un technicien
     */
    public function isTechnicien(): bool
    {
        return $this->hasRole('technicien');
    }

    /**
     * Obtenir les habilitations expirées
     */
    public function getHabilitationsExpirees()
    {
        return $this->userHabilitations()
                    ->where('date_echeance', '<', now())
                    ->where('actif', true)
                    ->get();
    }

    /**
     * Obtenir les habilitations qui expirent bientôt (dans les 30 jours)
     */
    public function getHabilitationsExpirantBientot()
    {
        return $this->userHabilitations()
                    ->where('date_echeance', '>=', now())
                    ->where('date_echeance', '<=', now()->addDays(30))
                    ->where('actif', true)
                    ->get();
    }

    // --- Fin des Relations Eloquent ---
}
