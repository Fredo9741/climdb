<?php

namespace App\Http\Controllers;

use App\Models\MouvementGaz;
use App\Models\Equipement;
use App\Models\TypeGaz;
use App\Models\Intervention;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MouvementGazController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $mouvements = MouvementGaz::with(['equipement', 'typeGaz', 'intervention', 'user'])
            ->latest('date_mouvement')
            ->get();

        return Inertia::render('mouvements-gaz/Index', [
            'mouvements' => $mouvements,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $equipements = Equipement::with('site.client')->get();
        $typesGaz = TypeGaz::all();
        $interventions = Intervention::with('site')->latest()->get();
        $techniciens = User::role('technicien')->get();

        return Inertia::render('mouvements-gaz/Create', [
            'equipements' => $equipements,
            'typesGaz' => $typesGaz,
            'interventions' => $interventions,
            'techniciens' => $techniciens,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'equipement_id' => 'required|exists:equipements,id',
            'type_gaz_id' => 'required|exists:types_gaz,id',
            'intervention_id' => 'nullable|exists:interventions,id',
            'user_id' => 'required|exists:users,id',
            'type_mouvement' => 'required|in:entrée,sortie',
            'quantite_kg' => 'required|numeric|min:0.1|max:100',
            'date_mouvement' => 'required|date',
            'observations' => 'nullable|string|max:1000',
        ]);

        MouvementGaz::create($validated);

        return redirect()->route('mouvements-gaz.index')
            ->with('success', 'Mouvement de gaz enregistré avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(MouvementGaz $mouvementGaz): Response
    {
        $mouvementGaz->load([
            'equipement.site.client',
            'typeGaz',
            'intervention',
            'user',
        ]);

        return Inertia::render('mouvements-gaz/Show', [
            'mouvement' => $mouvementGaz,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MouvementGaz $mouvementGaz): Response
    {
        $equipements = Equipement::with('site.client')->get();
        $typesGaz = TypeGaz::all();
        $interventions = Intervention::with('site')->latest()->get();
        $techniciens = User::role('technicien')->get();

        return Inertia::render('mouvements-gaz/Edit', [
            'mouvement' => $mouvementGaz,
            'equipements' => $equipements,
            'typesGaz' => $typesGaz,
            'interventions' => $interventions,
            'techniciens' => $techniciens,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MouvementGaz $mouvementGaz): RedirectResponse
    {
        $validated = $request->validate([
            'equipement_id' => 'required|exists:equipements,id',
            'type_gaz_id' => 'required|exists:types_gaz,id',
            'intervention_id' => 'nullable|exists:interventions,id',
            'user_id' => 'required|exists:users,id',
            'type_mouvement' => 'required|in:entrée,sortie',
            'quantite_kg' => 'required|numeric|min:0.1|max:100',
            'date_mouvement' => 'required|date',
            'observations' => 'nullable|string|max:1000',
        ]);

        $mouvementGaz->update($validated);

        return redirect()->route('mouvements-gaz.index')
            ->with('success', 'Mouvement de gaz mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MouvementGaz $mouvementGaz): RedirectResponse
    {
        $mouvementGaz->delete();

        return redirect()->route('mouvements-gaz.index')
            ->with('success', 'Mouvement de gaz supprimé avec succès !');
    }
}
