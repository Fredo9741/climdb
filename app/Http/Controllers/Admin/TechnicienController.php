<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Habilitation;
use App\Models\UserHabilitation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class TechnicienController extends Controller
{
    /**
     * Afficher la liste des techniciens
     */
    public function index(): Response
    {
        $techniciens = User::role('technicien')
            ->with(['userHabilitations.habilitation', 'roles'])
            ->orderBy('name')
            ->get()
            ->map(function ($technicien) {
                return [
                    'id' => $technicien->id,
                    'name' => $technicien->name,
                    'email' => $technicien->email,
                    'telephone' => $technicien->telephone,
                    'qualification' => $technicien->qualification,
                    'created_at' => $technicien->created_at,
                    'habilitations_count' => $technicien->userHabilitations->count(),
                    'habilitations_expirees' => $technicien->getHabilitationsExpirees()->count(),
                    'habilitations_expirant_bientot' => $technicien->getHabilitationsExpirantBientot()->count(),
                    'interventions_count' => $technicien->interventions()->count(),
                ];
            });

        return Inertia::render('admin/techniciens/Index', [
            'techniciens' => $techniciens,
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(): Response
    {
        $habilitations = Habilitation::actives()->orderBy('nom')->get();

        return Inertia::render('admin/techniciens/Create', [
            'habilitations' => $habilitations,
        ]);
    }

    /**
     * Enregistrer un nouveau technicien
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'telephone' => 'nullable|string|max:20',
            'qualification' => 'nullable|string|max:255',
            'habilitations' => 'array',
            'habilitations.*.habilitation_id' => 'required|exists:habilitations,id',
            'habilitations.*.date_obtention' => 'required|date',
            'habilitations.*.numero_certificat' => 'nullable|string|max:100',
            'habilitations.*.commentaires' => 'nullable|string',
        ]);

        // Générer un mot de passe aléatoire
        $password = Str::random(12);

        // Créer l'utilisateur
        $technicien = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'qualification' => $validated['qualification'],
            'password' => Hash::make($password),
        ]);

        // Assigner le rôle technicien
        $technicien->assignRole('technicien');

        // Ajouter les habilitations
        if (isset($validated['habilitations'])) {
            foreach ($validated['habilitations'] as $habilitationData) {
                $habilitation = Habilitation::find($habilitationData['habilitation_id']);
                $dateObtention = \Carbon\Carbon::parse($habilitationData['date_obtention']);
                $dateEcheance = $dateObtention->copy()->addMonths($habilitation->duree_validite_mois);

                UserHabilitation::create([
                    'user_id' => $technicien->id,
                    'habilitation_id' => $habilitationData['habilitation_id'],
                    'date_obtention' => $habilitationData['date_obtention'],
                    'date_echeance' => $dateEcheance,
                    'numero_certificat' => $habilitationData['numero_certificat'] ?? null,
                    'commentaires' => $habilitationData['commentaires'] ?? null,
                    'actif' => true,
                ]);
            }
        }

        return redirect()->route('admin.techniciens.index')
            ->with('success', "Technicien créé avec succès. Mot de passe temporaire : {$password}");
    }

    /**
     * Afficher les détails d'un technicien
     */
    public function show(User $technicien): Response
    {
        // Vérifier que c'est bien un technicien
        if (!$technicien->hasRole('technicien')) {
            abort(404);
        }

        $technicien->load([
            'userHabilitations.habilitation',
            'interventions.panne.equipement.site.client',
            'affectationsVehicules.vehicule',
            'mouvementsGaz',
            'roles'
        ]);

        return Inertia::render('admin/techniciens/Show', [
            'technicien' => [
                'id' => $technicien->id,
                'name' => $technicien->name,
                'email' => $technicien->email,
                'telephone' => $technicien->telephone,
                'qualification' => $technicien->qualification,
                'created_at' => $technicien->created_at,
                'habilitations' => $technicien->userHabilitations->map(function ($userHabilitation) {
                    return [
                        'id' => $userHabilitation->id,
                        'habilitation' => $userHabilitation->habilitation->nom,
                        'date_obtention' => $userHabilitation->date_obtention,
                        'date_echeance' => $userHabilitation->date_echeance,
                        'numero_certificat' => $userHabilitation->numero_certificat,
                        'commentaires' => $userHabilitation->commentaires,
                        'expired' => $userHabilitation->isExpired(),
                        'expires_soon' => $userHabilitation->expiresSoon(),
                    ];
                }),
                'interventions' => $technicien->interventions->take(5)->map(function ($intervention) {
                    return [
                        'id' => $intervention->id,
                        'date_planifiee' => $intervention->date_planifiee,
                        'statut' => $intervention->statut,
                        'equipement' => $intervention->panne->equipement->nom ?? 'N/A',
                        'site' => $intervention->panne->equipement->site->nom ?? 'N/A',
                    ];
                }),
                'affectations_vehicules' => $technicien->affectationsVehicules->map(function ($affectation) {
                    return [
                        'id' => $affectation->id,
                        'vehicule' => $affectation->vehicule->marque . ' ' . $affectation->vehicule->modele,
                        'immatriculation' => $affectation->vehicule->immatriculation,
                        'date_debut' => $affectation->date_debut,
                        'date_fin' => $affectation->date_fin,
                        'motif' => $affectation->motif,
                        'active' => $affectation->isActive(),
                    ];
                }),
                'stats' => [
                    'interventions_count' => $technicien->interventions()->count(),
                    'habilitations_count' => $technicien->userHabilitations()->count(),
                    'habilitations_expirees' => $technicien->getHabilitationsExpirees()->count(),
                    'mouvements_gaz_count' => $technicien->mouvementsGaz()->count(),
                ],
            ],
        ]);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(User $technicien): Response
    {
        // Vérifier que c'est bien un technicien
        if (!$technicien->hasRole('technicien')) {
            abort(404);
        }

        $technicien->load(['userHabilitations.habilitation', 'affectationsVehicules.vehicule']);
        $habilitations = Habilitation::actives()->orderBy('nom')->get();
        $vehicules = \App\Models\Vehicule::actifs()->orderBy('marque')->orderBy('modele')->get();

        return Inertia::render('admin/techniciens/Edit', [
            'technicien' => [
                'id' => $technicien->id,
                'name' => $technicien->name,
                'email' => $technicien->email,
                'telephone' => $technicien->telephone,
                'qualification' => $technicien->qualification,
                'habilitations' => $technicien->userHabilitations->map(function ($userHabilitation) {
                    return [
                        'id' => $userHabilitation->id,
                        'habilitation_id' => $userHabilitation->habilitation_id,
                        'habilitation_nom' => $userHabilitation->habilitation->nom,
                        'date_obtention' => $userHabilitation->date_obtention,
                        'date_echeance' => $userHabilitation->date_echeance,
                        'numero_certificat' => $userHabilitation->numero_certificat,
                        'commentaires' => $userHabilitation->commentaires,
                    ];
                }),
                'affectations_vehicules' => $technicien->affectationsVehicules->map(function ($affectation) {
                    return [
                        'id' => $affectation->id,
                        'vehicule_id' => $affectation->vehicule_id,
                        'vehicule_nom' => $affectation->vehicule->marque . ' ' . $affectation->vehicule->modele,
                        'immatriculation' => $affectation->vehicule->immatriculation,
                        'date_debut' => $affectation->date_debut,
                        'date_fin' => $affectation->date_fin,
                        'motif' => $affectation->motif,
                    ];
                }),
            ],
            'habilitations' => $habilitations,
            'vehicules' => $vehicules,
        ]);
    }

    /**
     * Mettre à jour un technicien
     */
    public function update(Request $request, User $technicien): RedirectResponse
    {
        // Vérifier que c'est bien un technicien
        if (!$technicien->hasRole('technicien')) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($technicien->id)],
            'telephone' => 'nullable|string|max:20',
            'qualification' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:8',
            'habilitations' => 'array',
            'habilitations.*.habilitation_id' => 'required|exists:habilitations,id',
            'habilitations.*.date_obtention' => 'required|date',
            'habilitations.*.numero_certificat' => 'nullable|string|max:100',
            'habilitations.*.commentaires' => 'nullable|string',
            'affectations_vehicules' => 'array',
            'affectations_vehicules.*.vehicule_id' => 'required|exists:vehicules,id',
            'affectations_vehicules.*.date_debut' => 'required|date',
            'affectations_vehicules.*.date_fin' => 'nullable|date|after:affectations_vehicules.*.date_debut',
            'affectations_vehicules.*.motif' => 'nullable|string|max:255',
        ]);

        // Mettre à jour les informations de base
        $technicien->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'],
            'qualification' => $validated['qualification'],
        ]);

        // Mettre à jour le mot de passe si fourni
        if (isset($validated['password'])) {
            $technicien->update(['password' => Hash::make($validated['password'])]);
        }

        // Mettre à jour les habilitations
        if (isset($validated['habilitations'])) {
            // Supprimer toutes les habilitations existantes
            $technicien->userHabilitations()->delete();

            // Ajouter les nouvelles habilitations
            foreach ($validated['habilitations'] as $habilitationData) {
                $habilitation = Habilitation::find($habilitationData['habilitation_id']);
                $dateObtention = \Carbon\Carbon::parse($habilitationData['date_obtention']);
                $dateEcheance = $dateObtention->copy()->addMonths($habilitation->duree_validite_mois);

                UserHabilitation::create([
                    'user_id' => $technicien->id,
                    'habilitation_id' => $habilitationData['habilitation_id'],
                    'date_obtention' => $habilitationData['date_obtention'],
                    'date_echeance' => $dateEcheance,
                    'numero_certificat' => $habilitationData['numero_certificat'] ?? null,
                    'commentaires' => $habilitationData['commentaires'] ?? null,
                    'actif' => true,
                ]);
            }
        }

        // Mettre à jour les affectations de véhicules
        if (isset($validated['affectations_vehicules'])) {
            // Supprimer toutes les affectations existantes
            $technicien->affectationsVehicules()->delete();

            // Ajouter les nouvelles affectations
            foreach ($validated['affectations_vehicules'] as $affectationData) {
                $vehicule = \App\Models\Vehicule::find($affectationData['vehicule_id']);
                if ($vehicule) {
                    $technicien->affectationsVehicules()->create([
                        'vehicule_id' => $affectationData['vehicule_id'],
                        'date_debut' => $affectationData['date_debut'],
                        'date_fin' => $affectationData['date_fin'] ?? null,
                        'motif' => $affectationData['motif'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.techniciens.index')
            ->with('success', 'Technicien mis à jour avec succès.');
    }

    /**
     * Supprimer un technicien
     */
    public function destroy(User $technicien): RedirectResponse
    {
        // Vérifier que c'est bien un technicien
        if (!$technicien->hasRole('technicien')) {
            abort(404);
        }

        // Vérifier s'il y a des interventions liées
        $interventionsCount = $technicien->interventions()->count();
        if ($interventionsCount > 0) {
            return redirect()->route('admin.techniciens.index')
                ->with('error', "Impossible de supprimer ce technicien car il est lié à {$interventionsCount} intervention(s). Veuillez réaffecter ou supprimer ces interventions d'abord.");
        }

        // Supprimer le technicien
        $technicien->delete();

        return redirect()->route('admin.techniciens.index')
            ->with('success', 'Technicien supprimé avec succès.');
    }

    /**
     * Réinitialiser le mot de passe d'un technicien
     */
    public function resetPassword(User $technicien): RedirectResponse
    {
        // Vérifier que c'est bien un technicien
        if (!$technicien->hasRole('technicien')) {
            abort(404);
        }

        // Générer un nouveau mot de passe
        $password = Str::random(12);
        $technicien->update(['password' => Hash::make($password)]);

        return redirect()->route('admin.techniciens.edit', $technicien)
            ->with('success', "Mot de passe réinitialisé. Nouveau mot de passe : {$password}");
    }
} 