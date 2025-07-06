<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Devis;
use App\Models\Equipement;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DevisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $devis = Devis::with([
            'client',
            'site',
            'equipements.modele',
        ])->latest()->get();

        return Inertia::render('devis/Index', [
            'devis' => $devis,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $clients = Client::all();
        $sites = Site::with('client')->get();
        $equipements = Equipement::with(['site.client', 'modele'])->get();

        return Inertia::render('devis/Create', [
            'clients' => $clients,
            'sites' => $sites,
            'equipements' => $equipements,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'site_id' => 'nullable|exists:sites,id',
            'numero' => 'required|string|max:255|unique:devis,numero',
            'date_devis' => 'required|date',
            'date_expiration' => 'required|date|after:date_devis',
            'montant_ht' => 'required|numeric|min:0',
            'tva' => 'required|numeric|min:0|max:100',
            'montant_ttc' => 'required|numeric|min:0',
            'statut' => 'required|in:brouillon,envoye,accepte,refuse,expire',
            'description' => 'required|string',
            'conditions_paiement' => 'nullable|string',
            'equipements' => 'nullable|array',
            'equipements.*.equipement_id' => 'required|exists:equipements,id',
            'equipements.*.prix_unitaire' => 'required|numeric|min:0',
            'equipements.*.quantite' => 'required|integer|min:1',
            'equipements.*.description' => 'nullable|string',
        ]);

        $devis = Devis::create($validated);

        // Attacher les équipements si fournis
        if (isset($validated['equipements'])) {
            foreach ($validated['equipements'] as $equipement) {
                $devis->equipements()->attach($equipement['equipement_id'], [
                    'prix_unitaire' => $equipement['prix_unitaire'],
                    'quantite' => $equipement['quantite'],
                    'description' => $equipement['description'] ?? null,
                ]);
            }
        }

        return redirect()->route('devis.index')
            ->with('success', 'Devis créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Devis $devis): Response
    {
        $devis->load([
            'client',
            'site',
            'equipements.modele',
            'factures',
        ]);

        return Inertia::render('devis/Show', [
            'devis' => $devis,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Devis $devis): Response
    {
        $clients = Client::all();
        $sites = Site::with('client')->get();
        $equipements = Equipement::with(['site.client', 'modele'])->get();

        return Inertia::render('devis/Edit', [
            'devis' => $devis,
            'clients' => $clients,
            'sites' => $sites,
            'equipements' => $equipements,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Devis $devis): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'site_id' => 'nullable|exists:sites,id',
            'numero' => 'required|string|max:255|unique:devis,numero,'.$devis->id,
            'date_devis' => 'required|date',
            'date_expiration' => 'required|date|after:date_devis',
            'montant_ht' => 'required|numeric|min:0',
            'tva' => 'required|numeric|min:0|max:100',
            'montant_ttc' => 'required|numeric|min:0',
            'statut' => 'required|in:brouillon,envoye,accepte,refuse,expire',
            'description' => 'required|string',
            'conditions_paiement' => 'nullable|string',
        ]);

        $devis->update($validated);

        return redirect()->route('devis.index')
            ->with('success', 'Devis mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Devis $devis): RedirectResponse
    {
        // Vérifier s'il y a des factures liées
        if ($devis->factures()->count() > 0) {
            return redirect()->route('devis.index')
                ->with('error', 'Impossible de supprimer ce devis car il a des factures associées.');
        }

        $devis->delete();

        return redirect()->route('devis.index')
            ->with('success', 'Devis supprimé avec succès !');
    }

    /**
     * Accepter un devis
     */
    public function accepter(Devis $devis): RedirectResponse
    {
        $devis->update(['statut' => 'accepte']);

        return redirect()->route('devis.show', $devis)
            ->with('success', 'Devis accepté avec succès !');
    }

    /**
     * Refuser un devis
     */
    public function refuser(Devis $devis): RedirectResponse
    {
        $devis->update(['statut' => 'refuse']);

        return redirect()->route('devis.show', $devis)
            ->with('success', 'Devis refusé !');
    }
}
