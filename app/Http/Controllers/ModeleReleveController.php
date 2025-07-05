<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ModeleReleve;
use App\Models\ElementModeleReleve;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

final class ModeleReleveController extends Controller
{
    /**
     * Afficher la liste des modèles de relevés
     */
    public function index(): Response
    {
        $modeles = ModeleReleve::with('elementsModeleReleve')
            ->withCount('elementsModeleReleve')
            ->orderBy('nom')
            ->get();

        return Inertia::render('admin/modeles-releves/Index', [
            'modeles' => $modeles,
        ]);
    }

    /**
     * Afficher le formulaire de création
     */
    public function create(): Response
    {
        return Inertia::render('admin/modeles-releves/Create', [
            'modele' => new ModeleReleve(),
            'elements' => [],
        ]);
    }

    /**
     * Enregistrer un nouveau modèle de relevé
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:modeles_releves,nom',
            'description' => 'nullable|string',
            'elements' => 'nullable|array',
            'elements.*.type_mesure' => 'required|string|max:255',
            'elements.*.unite_attendue' => 'nullable|string|max:50',
            'elements.*.emplacement_suggerer' => 'nullable|string|max:255',
            'elements.*.valeur_min_attendue' => 'nullable|numeric',
            'elements.*.valeur_max_attendue' => 'nullable|numeric',
            'elements.*.obligatoire' => 'boolean',
            'elements.*.ordre' => 'nullable|integer|min:0',
            'elements.*.commentaire_guide' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Créer le modèle de relevé
            $modele = ModeleReleve::create([
                'nom' => $validated['nom'],
                'description' => $validated['description'],
            ]);

            // Créer les éléments de modèle de relevé
            if (!empty($validated['elements'])) {
                foreach ($validated['elements'] as $index => $elementData) {
                    ElementModeleReleve::create([
                        'modele_releve_id' => $modele->id,
                        'type_mesure' => $elementData['type_mesure'],
                        'unite_attendue' => $elementData['unite_attendue'] ?? null,
                        'emplacement_suggerer' => $elementData['emplacement_suggerer'] ?? null,
                        'valeur_min_attendue' => $elementData['valeur_min_attendue'] ?? null,
                        'valeur_max_attendue' => $elementData['valeur_max_attendue'] ?? null,
                        'obligatoire' => $elementData['obligatoire'] ?? false,
                        'ordre' => $elementData['ordre'] ?? $index + 1,
                        'commentaire_guide' => $elementData['commentaire_guide'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return Redirect::route('modeles-releves.index')
                ->with('success', 'Modèle de relevé créé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return Redirect::back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur s\'est produite lors de la création du modèle de relevé.']);
        }
    }

    /**
     * Afficher un modèle de relevé spécifique
     */
    public function show(ModeleReleve $modeleReleve): Response
    {
        $modeleReleve->load('elementsModeleReleve');

        return Inertia::render('admin/modeles-releves/Show', [
            'modele' => $modeleReleve,
        ]);
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(ModeleReleve $modeleReleve): Response
    {
        $modeleReleve->load('elementsModeleReleve');

        return Inertia::render('admin/modeles-releves/Edit', [
            'modele' => $modeleReleve,
            'elements' => $modeleReleve->elementsModeleReleve->sortBy('ordre'),
        ]);
    }

    /**
     * Mettre à jour un modèle de relevé
     */
    public function update(Request $request, ModeleReleve $modeleReleve): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255|unique:modeles_releves,nom,' . $modeleReleve->id,
            'description' => 'nullable|string',
            'elements' => 'nullable|array',
            'elements.*.id' => 'nullable|integer|exists:elements_modele_releve,id',
            'elements.*.type_mesure' => 'required|string|max:255',
            'elements.*.unite_attendue' => 'nullable|string|max:50',
            'elements.*.emplacement_suggerer' => 'nullable|string|max:255',
            'elements.*.valeur_min_attendue' => 'nullable|numeric',
            'elements.*.valeur_max_attendue' => 'nullable|numeric',
            'elements.*.obligatoire' => 'boolean',
            'elements.*.ordre' => 'nullable|integer|min:0',
            'elements.*.commentaire_guide' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            // Mettre à jour le modèle de relevé
            $modeleReleve->update([
                'nom' => $validated['nom'],
                'description' => $validated['description'],
            ]);

            // Supprimer les éléments existants qui ne sont plus dans la liste
            $elementsIds = collect($validated['elements'] ?? [])
                ->pluck('id')
                ->filter()
                ->toArray();

            $modeleReleve->elementsModeleReleve()
                ->whereNotIn('id', $elementsIds)
                ->delete();

            // Créer ou mettre à jour les éléments
            if (!empty($validated['elements'])) {
                foreach ($validated['elements'] as $index => $elementData) {
                    $elementParams = [
                        'modele_releve_id' => $modeleReleve->id,
                        'type_mesure' => $elementData['type_mesure'],
                        'unite_attendue' => $elementData['unite_attendue'] ?? null,
                        'emplacement_suggerer' => $elementData['emplacement_suggerer'] ?? null,
                        'valeur_min_attendue' => $elementData['valeur_min_attendue'] ?? null,
                        'valeur_max_attendue' => $elementData['valeur_max_attendue'] ?? null,
                        'obligatoire' => $elementData['obligatoire'] ?? false,
                        'ordre' => $elementData['ordre'] ?? $index + 1,
                        'commentaire_guide' => $elementData['commentaire_guide'] ?? null,
                    ];

                    if (isset($elementData['id'])) {
                        // Mettre à jour l'élément existant
                        ElementModeleReleve::where('id', $elementData['id'])
                            ->update($elementParams);
                    } else {
                        // Créer un nouvel élément
                        ElementModeleReleve::create($elementParams);
                    }
                }
            }

            DB::commit();

            return Redirect::route('modeles-releves.index')
                ->with('success', 'Modèle de relevé mis à jour avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return Redirect::back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur s\'est produite lors de la mise à jour du modèle de relevé.']);
        }
    }

    /**
     * Supprimer un modèle de relevé
     */
    public function destroy(ModeleReleve $modeleReleve): RedirectResponse
    {
        try {
            DB::beginTransaction();

            // Vérifier si le modèle de relevé est utilisé comme défaut par des modèles d'équipement
            $modeleUtilise = $modeleReleve->modeles()->count() > 0;

            if ($modeleUtilise) {
                return Redirect::back()
                    ->withErrors(['error' => 'Ce modèle de relevé ne peut pas être supprimé car il est utilisé par des modèles d\'équipement.']);
            }

            // Supprimer le modèle de relevé (les éléments seront supprimés automatiquement grâce à la cascade)
            $modeleReleve->delete();

            DB::commit();

            return Redirect::route('modeles-releves.index')
                ->with('success', 'Modèle de relevé supprimé avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return Redirect::back()
                ->withErrors(['error' => 'Une erreur s\'est produite lors de la suppression du modèle de relevé.']);
        }
    }
} 