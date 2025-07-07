<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $clients = Client::withCount('sites')->latest()->get();

        return Inertia::render('clients/Index', [
            'clients' => $clients,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('clients/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:500',
            'ville' => 'required|string|max:100',
            'code_postal' => 'required|string|max:10',
            'pays' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|unique:clients,email',
            'actif' => 'boolean',
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client): Response
    {
        $client->load('sites.equipements');

        return Inertia::render('clients/Show', [
            'client' => $client,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client): Response
    {
        return Inertia::render('clients/Edit', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:500',
            'ville' => 'required|string|max:100',
            'code_postal' => 'required|string|max:10',
            'pays' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|unique:clients,email,'.$client->id,
            'actif' => 'boolean',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Client mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client): RedirectResponse
    {
        // Vérifier s'il y a des sites liés
        if ($client->sites()->count() > 0) {
            return redirect()->route('clients.index')
                ->with('error', 'Impossible de supprimer ce client car il a des sites associés.');
        }

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Client supprimé avec succès !');
    }
}
