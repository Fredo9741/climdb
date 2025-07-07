<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Site;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $sites = Site::with(['client', 'equipements'])->latest()->get();

        return Inertia::render('sites/Index', [
            'sites' => $sites,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $clients = Client::all();

        return Inertia::render('sites/Create', [
            'clients' => $clients,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:500',
            'ville' => 'required|string|max:100',
            'code_postal' => 'required|string|max:10',
            'pays' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'contacts' => 'array',
            'contacts.*.nom' => 'required|string|max:255',
            'contacts.*.prenom' => 'required|string|max:255',
            'contacts.*.fonction' => 'nullable|string|max:255',
            'contacts.*.telephone' => 'nullable|string|max:20',
            'contacts.*.email' => 'required|email|max:255|distinct',
        ]);

        $contactsData = $validated['contacts'] ?? [];
        unset($validated['contacts']);

        $site = Site::create($validated);

        // Traiter les contacts
        foreach ($contactsData as $contactData) {
            $contact = \App\Models\PersonneContact::firstOrCreate([
                'email' => $contactData['email'],
            ], [
                'nom' => $contactData['nom'],
                'prenom' => $contactData['prenom'],
                'fonction' => $contactData['fonction'] ?? null,
                'telephone' => $contactData['telephone'] ?? null,
                'notes' => null,
            ]);

            $site->personnesContact()->syncWithoutDetaching($contact->id);
        }

        return redirect()->route('sites.index')
            ->with('success', 'Site créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Site $site): Response
    {
        $site->load(['client', 'equipements.modele', 'personnesContact']);

        return Inertia::render('sites/Show', [
            'site' => $site,
            'contacts' => $site->personnesContact->map(function ($c) {
                return [
                    'id' => $c->id,
                    'nom' => $c->nom,
                    'prenom' => $c->prenom,
                    'fonction' => $c->fonction,
                    'telephone' => $c->telephone,
                    'email' => $c->email,
                ];
            }),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Site $site): Response
    {
        $clients = Client::all();
        $site->load('personnesContact');

        return Inertia::render('sites/Edit', [
            'site' => $site,
            'clients' => $clients,
            'contacts' => $site->personnesContact->map(function ($c) {
                return [
                    'id' => $c->id,
                    'nom' => $c->nom,
                    'prenom' => $c->prenom,
                    'fonction' => $c->fonction,
                    'telephone' => $c->telephone,
                    'email' => $c->email,
                ];
            }),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Site $site): RedirectResponse
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string|max:500',
            'ville' => 'required|string|max:100',
            'code_postal' => 'required|string|max:10',
            'pays' => 'required|string|max:100',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'contacts' => 'array',
            'contacts.*.nom' => 'required|string|max:255',
            'contacts.*.prenom' => 'required|string|max:255',
            'contacts.*.fonction' => 'nullable|string|max:255',
            'contacts.*.telephone' => 'nullable|string|max:20',
            'contacts.*.email' => 'required|email|max:255|distinct',
        ]);

        $contactsData = $validated['contacts'] ?? [];
        unset($validated['contacts']);

        $site->update($validated);

        // Mettre à jour les contacts : on détache et rattache
        $site->personnesContact()->detach();
        foreach ($contactsData as $contactData) {
            $contact = \App\Models\PersonneContact::firstOrCreate([
                'email' => $contactData['email'],
            ], [
                'nom' => $contactData['nom'],
                'prenom' => $contactData['prenom'],
                'fonction' => $contactData['fonction'] ?? null,
                'telephone' => $contactData['telephone'] ?? null,
                'notes' => null,
            ]);
            $site->personnesContact()->syncWithoutDetaching($contact->id);
        }

        return redirect()->route('sites.index')
            ->with('success', 'Site mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Site $site): RedirectResponse
    {
        // Vérifier s'il y a des équipements liés
        if ($site->equipements()->count() > 0) {
            return redirect()->route('sites.index')
                ->with('error', 'Impossible de supprimer ce site car il a des équipements associés.');
        }

        $site->delete();

        return redirect()->route('sites.index')
            ->with('success', 'Site supprimé avec succès !');
    }
}
