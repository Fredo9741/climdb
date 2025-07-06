<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Devis;
use App\Models\Facture;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class FactureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $factures = Facture::with([
            'client',
            'devis',
        ])->latest()->get();

        return Inertia::render('factures/Index', [
            'factures' => $factures,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $clients = Client::all();
        $devis = Devis::where('statut', 'accepte')->get();

        return Inertia::render('factures/Create', [
            'clients' => $clients,
            'devis' => $devis,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'devis_id' => 'nullable|exists:devis,id',
            'numero_facture' => 'required|string|max:255|unique:factures,numero_facture',
            'date_facture' => 'required|date',
            'date_echeance' => 'required|date|after:date_facture',
            'montant_ht' => 'required|numeric|min:0',
            'montant_ttc' => 'required|numeric|min:0',
            'statut' => 'required|in:brouillon,emise,payee,en_retard,annulee',
            'description' => 'required|string',
        ]);

        $facture = Facture::create($validated);

        return redirect()->route('factures.index')
            ->with('success', 'Facture créée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Facture $facture): Response
    {
        $facture->load([
            'client',
            'devis',
        ]);

        return Inertia::render('factures/Show', [
            'facture' => $facture,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facture $facture): Response
    {
        $clients = Client::all();
        $devis = Devis::where('statut', 'accepte')->get();

        return Inertia::render('factures/Edit', [
            'facture' => $facture,
            'clients' => $clients,
            'devis' => $devis,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Facture $facture): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'devis_id' => 'nullable|exists:devis,id',
            'numero_facture' => 'required|string|max:255|unique:factures,numero_facture,'.$facture->id,
            'date_facture' => 'required|date',
            'date_echeance' => 'required|date|after:date_facture',
            'montant_ht' => 'required|numeric|min:0',
            'montant_ttc' => 'required|numeric|min:0',
            'statut' => 'required|in:brouillon,emise,payee,en_retard,annulee',
            'description' => 'required|string',
        ]);

        $facture->update($validated);

        return redirect()->route('factures.index')
            ->with('success', 'Facture mise à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Facture $facture): RedirectResponse
    {
        // Vérifier si la facture est déjà payée
        if ($facture->statut === 'payee') {
            return redirect()->route('factures.index')
                ->with('error', 'Impossible de supprimer une facture déjà payée.');
        }

        $facture->delete();

        return redirect()->route('factures.index')
            ->with('success', 'Facture supprimée avec succès !');
    }

    /**
     * Marquer une facture comme payée
     */
    public function marquerPayee(Facture $facture): RedirectResponse
    {
        $facture->update(['statut' => 'payee']);

        return redirect()->route('factures.show', $facture)
            ->with('success', 'Facture marquée comme payée !');
    }

    /**
     * Générer une facture à partir d'un devis
     */
    public function genererDepuisDevis(Devis $devis): RedirectResponse
    {
        // Vérifier que le devis est accepté
        if ($devis->statut !== 'accepte') {
            return redirect()->route('devis.show', $devis)
                ->with('error', 'Seuls les devis acceptés peuvent être transformés en facture.');
        }

        // Créer la facture
        $facture = Facture::create([
            'client_id' => $devis->client_id,
            'devis_id' => $devis->id,
            'numero_facture' => 'FAC-'.date('Y').'-'.str_pad(Facture::count() + 1, 4, '0', STR_PAD_LEFT),
            'date_facture' => now(),
            'date_echeance' => now()->addDays(30),
            'montant_ht' => $devis->montant_ht,
            'montant_ttc' => $devis->montant_ttc,
            'statut' => 'emise',
            'description' => 'Facture générée depuis le devis '.$devis->numero,
        ]);

        return redirect()->route('factures.show', $facture)
            ->with('success', 'Facture générée avec succès depuis le devis !');
    }
}
