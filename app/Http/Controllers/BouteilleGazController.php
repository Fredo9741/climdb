<?php

namespace App\Http\Controllers;

use App\Models\BouteilleGaz;
use App\Models\TypeGaz;
use App\Models\StatutBouteille;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BouteilleGazController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $bouteilles = BouteilleGaz::with(['typeGaz', 'statutBouteille', 'user'])
            ->withCount('mouvements')
            ->latest()
            ->get();

        return Inertia::render('bouteilles-gaz/Index', [
            'bouteilles' => $bouteilles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $typesGaz = TypeGaz::all();
        $statuts = StatutBouteille::all();
        $techniciens = User::role('technicien')->get();

        return Inertia::render('bouteilles-gaz/Create', [
            'typesGaz' => $typesGaz,
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
            'numero_serie' => 'required|string|max:255|unique:bouteille_gaz,numero_serie',
            'type_gaz_id' => 'required|exists:type_gaz,id',
            'capacite_kg' => 'required|numeric|min:0.1|max:100',
            'poids_actuel_kg' => 'required|numeric|min:0|max:100',
            'statut_bouteille_id' => 'required|exists:statut_bouteilles,id',
            'user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        BouteilleGaz::create($validated);

        return redirect()->route('bouteilles-gaz.index')
            ->with('success', 'Bouteille de gaz créée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(BouteilleGaz $bouteilleGaz): Response
    {
        $bouteilleGaz->load([
            'typeGaz',
            'statutBouteille',
            'user',
            'mouvements' => function($query) {
                $query->with('user')->latest()->limit(10);
            },
        ]);

        return Inertia::render('bouteilles-gaz/Show', [
            'bouteille' => $bouteilleGaz,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BouteilleGaz $bouteilleGaz): Response
    {
        $typesGaz = TypeGaz::all();
        $statuts = StatutBouteille::all();
        $techniciens = User::role('technicien')->get();

        return Inertia::render('bouteilles-gaz/Edit', [
            'bouteille' => $bouteilleGaz,
            'typesGaz' => $typesGaz,
            'statuts' => $statuts,
            'techniciens' => $techniciens,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BouteilleGaz $bouteilleGaz): RedirectResponse
    {
        $validated = $request->validate([
            'numero_serie' => 'required|string|max:255|unique:bouteille_gaz,numero_serie,'.$bouteilleGaz->id,
            'type_gaz_id' => 'required|exists:type_gaz,id',
            'capacite_kg' => 'required|numeric|min:0.1|max:100',
            'poids_actuel_kg' => 'required|numeric|min:0|max:100',
            'statut_bouteille_id' => 'required|exists:statut_bouteilles,id',
            'user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $bouteilleGaz->update($validated);

        return redirect()->route('bouteilles-gaz.index')
            ->with('success', 'Bouteille de gaz mise à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BouteilleGaz $bouteilleGaz): RedirectResponse
    {
        // Vérifier s'il y a des mouvements récents
        if ($bouteilleGaz->mouvements()->where('created_at', '>=', now()->subDays(30))->count() > 0) {
            return redirect()->route('bouteilles-gaz.index')
                ->with('error', 'Impossible de supprimer cette bouteille car elle a des mouvements récents.');
        }

        $bouteilleGaz->delete();

        return redirect()->route('bouteilles-gaz.index')
            ->with('success', 'Bouteille de gaz supprimée avec succès !');
    }
}
