<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class TechnicienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $techniciens = User::role('technicien')
            ->with(['roles'])
            ->withCount(['interventions', 'bouteillesGaz', 'affectationsVehicules'])
            ->paginate(10);

        return Inertia::render('Techniciens/Index', [
            'techniciens' => $techniciens,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Techniciens/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:20',
            'specialite' => 'nullable|string|max:100',
            'actif' => 'boolean',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'telephone' => $validated['telephone'] ?? null,
            'specialite' => $validated['specialite'] ?? null,
            'actif' => $validated['actif'] ?? true,
        ]);

        $user->assignRole('technicien');

        return redirect()->route('techniciens.index')
            ->with('success', 'Technicien créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $technicien)
    {
        // Vérifier que l'utilisateur est bien un technicien
        if (!$technicien->hasRole('technicien')) {
            abort(404, 'Technicien non trouvé');
        }

        $technicien->load([
            'roles',
            'interventions' => function ($query) {
                $query->with(['equipement', 'site', 'panne'])
                    ->latest()
                    ->take(5);
            },
            'bouteillesGaz' => function ($query) {
                $query->with(['typeGaz', 'statutBouteille'])
                    ->latest()
                    ->take(5);
            },
            'affectationsVehicules' => function ($query) {
                $query->with(['vehicule', 'statutAffectation'])
                    ->where('actif', true)
                    ->latest();
            }
        ]);

        // Statistiques du technicien
        $stats = [
            'interventions_total' => $technicien->interventions()->count(),
            'interventions_en_cours' => $technicien->interventions()
                ->whereIn('statut', ['en_cours', 'en_attente'])
                ->count(),
            'interventions_ce_mois' => $technicien->interventions()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'bouteilles_affectees' => $technicien->bouteillesGaz()->count(),
            'vehicules_affectes' => $technicien->affectationsVehicules()
                ->where('actif', true)
                ->count(),
        ];

        return Inertia::render('Techniciens/Show', [
            'technicien' => $technicien,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $technicien)
    {
        // Vérifier que l'utilisateur est bien un technicien
        if (!$technicien->hasRole('technicien')) {
            abort(404, 'Technicien non trouvé');
        }

        return Inertia::render('Techniciens/Edit', [
            'technicien' => $technicien,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $technicien)
    {
        // Vérifier que l'utilisateur est bien un technicien
        if (!$technicien->hasRole('technicien')) {
            abort(404, 'Technicien non trouvé');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($technicien->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'telephone' => 'nullable|string|max:20',
            'specialite' => 'nullable|string|max:100',
            'actif' => 'boolean',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'specialite' => $validated['specialite'] ?? null,
            'actif' => $validated['actif'] ?? true,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $technicien->update($updateData);

        return redirect()->route('techniciens.index')
            ->with('success', 'Technicien mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $technicien)
    {
        // Vérifier que l'utilisateur est bien un technicien
        if (!$technicien->hasRole('technicien')) {
            abort(404, 'Technicien non trouvé');
        }

        // Vérifier s'il y a des interventions en cours
        $interventionsEnCours = $technicien->interventions()
            ->whereIn('statut', ['en_cours', 'en_attente'])
            ->count();

        if ($interventionsEnCours > 0) {
            return back()->withErrors([
                'delete' => 'Impossible de supprimer ce technicien car il a des interventions en cours.',
            ]);
        }

        // Vérifier s'il y a des affectations de véhicules actives
        $affectationsActives = $technicien->affectationsVehicules()
            ->where('actif', true)
            ->count();

        if ($affectationsActives > 0) {
            return back()->withErrors([
                'delete' => 'Impossible de supprimer ce technicien car il a des véhicules affectés.',
            ]);
        }

        $technicien->delete();

        return redirect()->route('techniciens.index')
            ->with('success', 'Technicien supprimé avec succès.');
    }
}