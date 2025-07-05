<?php

namespace App\Http\Controllers;

use App\Models\Equipement;
use App\Models\Site;
use App\Models\Modele;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class EquipementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $search = $request->get('search');
        
        $equipements = Equipement::with([
            'site.client', 
            'modele.typeEquipement', 
            'modele.typeGaz'
        ])
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('numero_serie', 'like', "%{$search}%")
                      ->orWhere('localisation_precise', 'like', "%{$search}%")
                      ->orWhere('etat', 'like', "%{$search}%")
                      ->orWhereHas('site', function ($q) use ($search) {
                          $q->where('nom', 'like', "%{$search}%");
                      })
                      ->orWhereHas('site.client', function ($q) use ($search) {
                          $q->where('nom', 'like', "%{$search}%")
                            ->orWhere('nom_entreprise', 'like', "%{$search}%");
                      })
                      ->orWhereHas('modele', function ($q) use ($search) {
                          $q->where('nom', 'like', "%{$search}%")
                            ->orWhere('marque', 'like', "%{$search}%");
                      })
                      ->orWhereHas('modele.typeEquipement', function ($q) use ($search) {
                          $q->where('nom', 'like', "%{$search}%");
                      });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
        
        return Inertia::render('equipements/Index', [
            'equipements' => $equipements,
            'filters' => [
                'search' => $search,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $sites = Site::with('client')->get();
        $modeles = Modele::with(['typeEquipement', 'typeGaz'])->get();
        
        return Inertia::render('equipements/Create', [
            'sites' => $sites,
            'modeles' => $modeles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'modele_id' => 'required|exists:modeles,id',
            'numero_serie' => 'required|string|max:255|unique:equipements,numero_serie',
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_installation' => 'required|date',
            'date_derniere_maintenance' => 'nullable|date',
            'localisation_precise' => 'nullable|string|max:500',
            'etat' => 'required|in:actif,inactif,maintenance,panne',
        ]);

        Equipement::create($validated);

        return redirect()->route('equipements.index')
            ->with('success', 'Équipement créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipement $equipement): Response
    {
        $equipement->load([
            'site.client',
            'modele.typeEquipement',
            'modele.typeGaz',
            'pannes',
            'maintenancesProgrammees',
            'mouvementsGaz'
        ]);
        
        return Inertia::render('equipements/Show', [
            'equipement' => $equipement
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Equipement $equipement): Response
    {
        $sites = Site::with('client')->get();
        $modeles = Modele::with(['typeEquipement', 'typeGaz'])->get();
        
        return Inertia::render('equipements/Edit', [
            'equipement' => $equipement,
            'sites' => $sites,
            'modeles' => $modeles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Equipement $equipement): RedirectResponse
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'modele_id' => 'required|exists:modeles,id',
            'numero_serie' => 'required|string|max:255|unique:equipements,numero_serie,' . $equipement->id,
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_installation' => 'required|date',
            'date_derniere_maintenance' => 'nullable|date',
            'localisation_precise' => 'nullable|string|max:500',
            'etat' => 'required|in:actif,inactif,maintenance,panne',
        ]);

        $equipement->update($validated);

        return redirect()->route('equipements.index')
            ->with('success', 'Équipement mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipement $equipement): RedirectResponse
    {
        try {
            // Vérifier s'il y a des pannes ou interventions liées
            $pannesCount = $equipement->pannes()->count();
            $maintenancesCount = $equipement->maintenancesProgrammees()->count();
            
            if ($pannesCount > 0 || $maintenancesCount > 0) {
                return redirect()->route('equipements.index')
                    ->with('error', "Impossible de supprimer l'équipement '{$equipement->nom}' car il a {$pannesCount} panne(s) et {$maintenancesCount} maintenance(s) associée(s).");
            }

            $nomEquipement = $equipement->nom;
            $equipement->delete();

            return redirect()->route('equipements.index')
                ->with('success', "Équipement '{$nomEquipement}' supprimé avec succès !");
                
        } catch (\Exception $e) {
            return redirect()->route('equipements.index')
                ->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }
}
