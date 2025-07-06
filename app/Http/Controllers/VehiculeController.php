<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
use App\Models\StatutVehicule;
use App\Models\User;
use App\Models\AffectationVehicule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VehiculeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $vehicules = Vehicule::with(['statutVehicule', 'affectations.user'])
            ->withCount(['entretiens', 'suivisKilometrage'])
            ->latest()
            ->get();

        return Inertia::render('vehicules/Index', [
            'vehicules' => $vehicules,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $statuts = StatutVehicule::all();
        // On récupère tous les utilisateurs ayant le rôle "technicien"
        $techniciens = User::role('technicien')->orderBy('name')->get();

        return Inertia::render('vehicules/Create', [
            'statuts' => $statuts,
            'techniciens' => $techniciens,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'immatriculation' => 'required|string|max:20|unique:vehicules,immatriculation',
            'annee_fabrication' => 'required|integer|min:1900|max:'.now()->year,
            'kilometrage_actuel' => 'required|numeric|min:0',
            'type_carburant' => 'required|in:Essence,Diesel,Électrique,Hybride,GPL',
            'date_acquisition' => 'required|date',
            'statut_vehicule_id' => 'required|exists:statuts_vehicules,id',
            'notes' => 'nullable|string|max:1000',
            'affectations' => 'nullable|array',
            'affectations.*.user_id' => 'required|exists:users,id',
            'affectations.*.date_debut' => 'required|date',
            'affectations.*.motif' => 'required|string|max:255',
        ]);

        // Créer le véhicule
        $vehicule = Vehicule::create($validated);

        // Gérer les affectations
        if (isset($validated['affectations'])) {
            foreach ($validated['affectations'] as $affectationData) {
                AffectationVehicule::create([
                    'vehicule_id' => $vehicule->id,
                    'user_id' => $affectationData['user_id'],
                    'date_debut' => $affectationData['date_debut'],
                    'motif' => $affectationData['motif'],
                ]);
            }
        }

        return redirect()->route('vehicules.index')
            ->with('success', 'Véhicule créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicule $vehicule): Response
    {
        $vehicule->load([
            'statutVehicule',
            'affectations.user',
            'entretiens' => function($query) {
                $query->latest()->limit(10);
            },
            'suivisKilometrage' => function($query) {
                $query->latest()->limit(10);
            },
        ]);

        return Inertia::render('vehicules/Show', [
            'vehicule' => $vehicule,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicule $vehicule): Response
    {
        $statuts = StatutVehicule::all();
        $techniciens = User::role('technicien')->orderBy('name')->get();
        
        $vehicule->load('affectations.user');

        return Inertia::render('vehicules/Edit', [
            'vehicule' => $vehicule,
            'statuts' => $statuts,
            'techniciens' => $techniciens,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicule $vehicule): RedirectResponse
    {
        $validated = $request->validate([
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'immatriculation' => 'required|string|max:20|unique:vehicules,immatriculation,'.$vehicule->id,
            'annee_fabrication' => 'required|integer|min:1900|max:'.now()->year,
            'kilometrage_actuel' => 'required|numeric|min:0',
            'type_carburant' => 'required|in:Essence,Diesel,Électrique,Hybride,GPL',
            'date_acquisition' => 'required|date',
            'statut_vehicule_id' => 'required|exists:statuts_vehicules,id',
            'notes' => 'nullable|string|max:1000',
            'affectations' => 'nullable|array',
            'affectations.*.id' => 'nullable|exists:affectations_vehicules,id',
            'affectations.*.user_id' => 'required|exists:users,id',
            'affectations.*.date_debut' => 'required|date',
            'affectations.*.date_fin' => 'nullable|date|after:affectations.*.date_debut',
            'affectations.*.motif' => 'required|string|max:255',
            'affectations_to_delete' => 'nullable|array',
            'affectations_to_delete.*' => 'exists:affectations_vehicules,id',
        ]);

        // Mettre à jour le véhicule
        $vehicule->update($validated);

        // Supprimer les affectations marquées pour suppression
        if (isset($validated['affectations_to_delete'])) {
            AffectationVehicule::whereIn('id', $validated['affectations_to_delete'])->delete();
        }

        // Gérer les affectations
        if (isset($validated['affectations'])) {
            foreach ($validated['affectations'] as $affectationData) {
                if (isset($affectationData['id'])) {
                    // Mettre à jour une affectation existante
                    $affectation = AffectationVehicule::find($affectationData['id']);
                    if ($affectation) {
                        $affectation->update([
                            'user_id' => $affectationData['user_id'],
                            'date_debut' => $affectationData['date_debut'],
                            'date_fin' => $affectationData['date_fin'] ?? null,
                            'motif' => $affectationData['motif'],
                        ]);
                    }
                } else {
                    // Créer une nouvelle affectation
                    AffectationVehicule::create([
                        'vehicule_id' => $vehicule->id,
                        'user_id' => $affectationData['user_id'],
                        'date_debut' => $affectationData['date_debut'],
                        'date_fin' => $affectationData['date_fin'] ?? null,
                        'motif' => $affectationData['motif'],
                    ]);
                }
            }
        }

        return redirect()->route('vehicules.index')
            ->with('success', 'Véhicule mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicule $vehicule): RedirectResponse
    {
        // Vérifier s'il y a des affectations actives
        if ($vehicule->affectations()->whereNull('date_fin')->count() > 0) {
            return redirect()->route('vehicules.index')
                ->with('error', 'Impossible de supprimer ce véhicule car il a des affectations actives.');
        }

        $vehicule->delete();

        return redirect()->route('vehicules.index')
            ->with('success', 'Véhicule supprimé avec succès !');
    }

    /**
     * Terminer une affectation de véhicule
     */
    public function terminerAffectation(Request $request, AffectationVehicule $affectation): RedirectResponse
    {
        $validated = $request->validate([
            'date_fin' => 'required|date|after:'.$affectation->date_debut,
        ]);

        $affectation->update([
            'date_fin' => $validated['date_fin'],
        ]);

        return redirect()->back()
            ->with('success', 'Affectation terminée avec succès !');
    }
}
