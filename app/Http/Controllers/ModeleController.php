<?php

namespace App\Http\Controllers;

use App\Models\Modele;
use App\Models\ModeleReleve;
use App\Models\TypeEquipement;
use App\Models\TypeGaz;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ModeleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $modeles = Modele::with([
            'typeEquipement',
            'typeGaz',
            'modeleReleveDefaut',
            'equipements', // Pour compter les équipements utilisant ce modèle
        ])->latest()->get();

        return Inertia::render('modeles/Index', [
            'modeles' => $modeles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $typesEquipements = TypeEquipement::all();
        $typesGaz = TypeGaz::all();
        $modelesReleves = ModeleReleve::all();

        return Inertia::render('modeles/Create', [
            'typesEquipements' => $typesEquipements,
            'typesGaz' => $typesGaz,
            'modelesReleves' => $modelesReleves,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type_equipement_id' => 'required|exists:types_equipements,id',
            'marque' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'reference_constructeur' => 'nullable|string|max:255|unique:modeles,reference_constructeur',
            'description' => 'nullable|string',
            'quantite_gaz_kg' => 'nullable|numeric|min:0',
            'type_gaz_id' => 'nullable|exists:types_gaz,id',
            'modele_releve_defaut_id' => 'nullable|exists:modeles_releves,id',
        ]);

        $modele = Modele::create($validated);

        return redirect()->route('modeles.index')
            ->with('success', 'Modèle créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Modele $modele): Response
    {
        $modele->load([
            'typeEquipement',
            'typeGaz',
            'modeleReleveDefaut',
            'equipements.site.client', // Équipements utilisant ce modèle
            'photos',
            'documents',
        ]);

        return Inertia::render('modeles/Show', [
            'modele' => $modele,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Modele $modele): Response
    {
        $typesEquipements = TypeEquipement::all();
        $typesGaz = TypeGaz::all();
        $modelesReleves = ModeleReleve::all();

        return Inertia::render('modeles/Edit', [
            'modele' => $modele,
            'typesEquipements' => $typesEquipements,
            'typesGaz' => $typesGaz,
            'modelesReleves' => $modelesReleves,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Modele $modele): RedirectResponse
    {
        $validated = $request->validate([
            'type_equipement_id' => 'required|exists:types_equipements,id',
            'marque' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'reference_constructeur' => 'nullable|string|max:255|unique:modeles,reference_constructeur,'.$modele->id,
            'description' => 'nullable|string',
            'quantite_gaz_kg' => 'nullable|numeric|min:0',
            'type_gaz_id' => 'nullable|exists:types_gaz,id',
            'modele_releve_defaut_id' => 'nullable|exists:modeles_releves,id',
        ]);

        $modele->update($validated);

        return redirect()->route('modeles.index')
            ->with('success', 'Modèle mis à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Modele $modele): RedirectResponse
    {
        // Vérifier si des équipements utilisent ce modèle
        if ($modele->equipements()->count() > 0) {
            return redirect()->route('modeles.index')
                ->with('error', 'Impossible de supprimer ce modèle car des équipements l\'utilisent encore.');
        }

        $modele->delete();

        return redirect()->route('modeles.index')
            ->with('success', 'Modèle supprimé avec succès !');
    }
}
