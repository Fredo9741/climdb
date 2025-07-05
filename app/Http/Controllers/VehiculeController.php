<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
use App\Models\StatutVehicule;
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

        return Inertia::render('vehicules/Create', [
            'statuts' => $statuts,
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
            'statut_vehicule_id' => 'required|exists:statut_vehicules,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        Vehicule::create($validated);

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

        return Inertia::render('vehicules/Edit', [
            'vehicule' => $vehicule,
            'statuts' => $statuts,
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
            'statut_vehicule_id' => 'required|exists:statut_vehicules,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $vehicule->update($validated);

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
}
