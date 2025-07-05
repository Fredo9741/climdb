<?php

namespace App\Http\Controllers;

use App\Models\Equipement;
use App\Models\Panne;
use App\Models\StatutDemande;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PanneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $pannes = Panne::with([
            'equipement.site.client',
            'equipement.modele',
            'statutDemande',
        ])->latest()->get();

        return Inertia::render('pannes/Index', [
            'pannes' => $pannes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $equipements = Equipement::with(['site.client', 'modele'])->get();

        return Inertia::render('pannes/Create', [
            'equipements' => $equipements,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'equipement_id' => 'required|exists:equipements,id',
            'description_panne' => 'required|string',
            'priorite' => 'required|in:faible,moyenne,haute,urgente',
            'date_constat' => 'required|date',
            'actions_correctives' => 'nullable|string',
        ]);

        // Ajouter les valeurs par défaut
        $validated['statut_demande_id'] = 1; // En attente par défaut
        $validated['user_id'] = auth()->id(); // Utilisateur connecté

        $panne = Panne::create($validated);

        // Mettre à jour l'état de l'équipement
        $panne->equipement->update(['etat' => 'panne']);

        return redirect()->route('pannes.index')
            ->with('success', 'Panne déclarée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Panne $panne): Response
    {
        $panne->load([
            'equipement.site.client',
            'equipement.modele',
            'statutDemande',
            'user',
            'interventions',
        ]);

        return Inertia::render('pannes/Show', [
            'panne' => $panne,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Panne $panne): Response
    {
        $equipements = Equipement::with(['site.client', 'modele'])->get();
        $statuts = StatutDemande::all();

        return Inertia::render('pannes/Edit', [
            'panne' => $panne,
            'equipements' => $equipements,
            'statuts' => $statuts,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Panne $panne): RedirectResponse
    {
        $validated = $request->validate([
            'equipement_id' => 'required|exists:equipements,id',
            'statut_demande_id' => 'required|exists:statuts_demandes,id',
            'description_panne' => 'required|string',
            'actions_correctives' => 'nullable|string',
            'priorite' => 'required|in:faible,moyenne,haute,urgente',
            'date_constat' => 'required|date',
            'date_resolution' => 'nullable|date|after_or_equal:date_constat',
        ]);

        $ancienStatut = $panne->statut_demande_id;
        $panne->update($validated);

        // Gérer le changement d'état de l'équipement
        if ($validated['statut_demande_id'] == 3 && $ancienStatut != 3) { // Résolu
            $panne->equipement->update(['etat' => 'actif']);
        } elseif ($validated['statut_demande_id'] != 3 && $ancienStatut == 3) { // Retour d'un état résolu
            $panne->equipement->update(['etat' => 'panne']);
        }

        return redirect()->route('pannes.show', $panne)
            ->with('success', 'Panne mise à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Panne $panne): RedirectResponse
    {
        // Vérifier s'il y a des interventions liées
        if ($panne->interventions()->count() > 0) {
            return redirect()->route('pannes.index')
                ->with('error', 'Impossible de supprimer cette panne car elle a des interventions associées.');
        }

        $panne->delete();

        return redirect()->route('pannes.index')
            ->with('success', 'Panne supprimée avec succès !');
    }
}
