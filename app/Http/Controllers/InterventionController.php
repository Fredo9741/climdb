<?php

namespace App\Http\Controllers;

use App\Models\Equipement;
use App\Models\Intervention;
use App\Models\Panne;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InterventionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $interventions = Intervention::with([
            'panne.equipement.site.client',
            'panne.equipement.modele.typeEquipement',
            'technicien',
            'demandeClient',
            'maintenanceProgrammee',
        ])->latest('date_planifiee')->get();

        // Ajouter les données extraites du rapport pour chaque intervention
        $interventionsWithDetails = $interventions->map(function ($intervention) {
            // Transformer l'intervention en array pour préserver les relations
            $interventionArray = $intervention->toArray();
            
            // Ajouter les données extraites du rapport
            $interventionArray['type_intervention'] = $this->extractTypeFromRapport($intervention->rapport);
            $interventionArray['description'] = $this->extractDescriptionFromRapport($intervention->rapport);
            $interventionArray['resultat'] = $this->determineResultat($intervention);
            
            return $interventionArray;
        });

        return Inertia::render('interventions/Index', [
            'interventions' => $interventionsWithDetails,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $pannes = Panne::with(['equipement.site.client'])->where('statut_demande_id', '!=', 3)->get();
        $equipements = Equipement::with(['site.client'])->get();
        $techniciens = User::role('technicien')->get();

        return Inertia::render('interventions/Create', [
            'pannes' => $pannes,
            'equipements' => $equipements,
            'techniciens' => $techniciens,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'panne_id' => 'nullable|exists:pannes,id',
            'equipement_id' => 'nullable|exists:equipements,id',
            'technicien_id' => 'required|exists:users,id',
            'date_intervention' => 'required|date',
            'type_intervention' => 'required|string',
            'description' => 'required|string',
            'observations' => 'nullable|string',
            'duree_estimee' => 'nullable|numeric|min:0',
            'cout_main_oeuvre' => 'nullable|numeric|min:0',
            'cout_pieces' => 'nullable|numeric|min:0',
            'statut' => 'required|in:programmee,en_cours,terminee,annulee',
        ]);

        // Mapper les champs du formulaire aux colonnes de la table
        $interventionData = [
            'panne_id' => $validated['panne_id'] ?? null,
            'technicien_id' => $validated['technicien_id'],
            'date_planifiee' => $validated['date_intervention'],
            'statut' => $validated['statut'],
            'rapport' => $validated['description'],
        ];

        // Si on a des observations, les ajouter au rapport
        if (! empty($validated['observations'])) {
            $interventionData['rapport'] .= "\n\nObservations: ".$validated['observations'];
        }

        // Ajouter les informations de coût et durée au rapport
        $infosComplementaires = [];
        if (! empty($validated['type_intervention'])) {
            $infosComplementaires[] = 'Type: '.$validated['type_intervention'];
        }
        if (! empty($validated['duree_estimee'])) {
            $infosComplementaires[] = 'Durée estimée: '.$validated['duree_estimee'].'h';
        }
        if (! empty($validated['cout_main_oeuvre'])) {
            $infosComplementaires[] = "Coût main d'œuvre: ".$validated['cout_main_oeuvre'].'€';
        }
        if (! empty($validated['cout_pieces'])) {
            $infosComplementaires[] = 'Coût pièces: '.$validated['cout_pieces'].'€';
        }

        if (! empty($infosComplementaires)) {
            $interventionData['rapport'] .= "\n\n".implode("\n", $infosComplementaires);
        }

        $intervention = Intervention::create($interventionData);

        return redirect()->route('interventions.index')
            ->with('success', 'Intervention créée avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Intervention $intervention): Response
    {
        $intervention->load([
            'panne.equipement.site.client',
            'technicien',
        ]);

        return Inertia::render('interventions/Show', [
            'intervention' => $intervention,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Intervention $intervention): Response
    {
        $pannes = Panne::with(['equipement.site.client'])->get();
        $equipements = Equipement::with(['site.client', 'modele'])->get();
        $techniciens = User::role('technicien')->get();

        // Convertir les données de la base vers le format attendu par le formulaire
        $interventionForForm = [
            'id' => $intervention->id,
            'panne_id' => $intervention->panne_id, // Ajouter panne_id
            'date_intervention' => $intervention->date_planifiee,
            'type_intervention' => $this->extractTypeFromRapport($intervention->rapport),
            'equipement_id' => $intervention->panne?->equipement_id ?? null,
            'technicien_id' => $intervention->technicien_id,
            'description' => $this->extractDescriptionFromRapport($intervention->rapport),
            'observations' => $this->extractObservationsFromRapport($intervention->rapport),
            'duree_estimee' => $this->extractDureeFromRapport($intervention->rapport),
            'cout_main_oeuvre' => $this->extractCoutMainOeuvreFromRapport($intervention->rapport),
            'cout_pieces' => $this->extractCoutPiecesFromRapport($intervention->rapport),
            'statut' => $intervention->statut,
        ];

        return Inertia::render('interventions/Edit', [
            'intervention' => $interventionForForm,
            'pannes' => $pannes,
            'equipements' => $equipements,
            'techniciens' => $techniciens,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Intervention $intervention): RedirectResponse
    {
        // Debug temporaire - voir quelles données arrivent
        \Log::info('Données reçues pour update intervention:', $request->all());

        $validated = $request->validate([
            'panne_id' => 'nullable|exists:pannes,id',
            'equipement_id' => 'nullable|exists:equipements,id',
            'technicien_id' => 'required|exists:users,id',
            'date_intervention' => 'required|date',
            'type_intervention' => 'required|string',
            'description' => 'required|string',
            'observations' => 'nullable|string',
            'duree_estimee' => 'nullable|numeric|min:0',
            'cout_main_oeuvre' => 'nullable|numeric|min:0',
            'cout_pieces' => 'nullable|numeric|min:0',
            'statut' => 'required|in:programmee,en_cours,terminee,annulee',
        ]);

        \Log::info('Données validées:', $validated);

        // Mapper les champs du formulaire aux colonnes de la table (même logique que store)
        $interventionData = [
            'panne_id' => $validated['panne_id'] ?? null,
            'technicien_id' => $validated['technicien_id'],
            'date_planifiee' => $validated['date_intervention'],
            'statut' => $validated['statut'],
            'rapport' => $validated['description'],
        ];

        // Si on a des observations, les ajouter au rapport
        if (! empty($validated['observations'])) {
            $interventionData['rapport'] .= "\n\nObservations: ".$validated['observations'];
        }

        // Ajouter les informations de coût et durée au rapport
        $infosComplementaires = [];
        if (! empty($validated['type_intervention'])) {
            $infosComplementaires[] = 'Type: '.$validated['type_intervention'];
        }
        if (! empty($validated['duree_estimee'])) {
            $infosComplementaires[] = 'Durée estimée: '.$validated['duree_estimee'].'h';
        }
        if (! empty($validated['cout_main_oeuvre'])) {
            $infosComplementaires[] = "Coût main d'œuvre: ".$validated['cout_main_oeuvre'].'€';
        }
        if (! empty($validated['cout_pieces'])) {
            $infosComplementaires[] = 'Coût pièces: '.$validated['cout_pieces'].'€';
        }

        if (! empty($infosComplementaires)) {
            $interventionData['rapport'] .= "\n\n".implode("\n", $infosComplementaires);
        }

        \Log::info('Données à sauvegarder:', $interventionData);

        $intervention->update($interventionData);

        return redirect()->route('interventions.index')
            ->with('success', 'Intervention mise à jour avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Intervention $intervention): RedirectResponse
    {
        $intervention->delete();

        return redirect()->route('interventions.index')
            ->with('success', 'Intervention supprimée avec succès !');
    }

    /**
     * Méthodes helper pour extraire les informations du rapport
     */
    private function extractTypeFromRapport(string $rapport): string
    {
        if (preg_match('/Type: (.+?)(?:\n|$)/m', $rapport, $matches)) {
            return $matches[1];
        }

        return '';
    }

    private function extractDescriptionFromRapport(string $rapport): string
    {
        // La description est la première partie avant les observations ou infos complémentaires
        $lines = explode("\n", $rapport);
        $description = [];

        foreach ($lines as $line) {
            if (str_starts_with($line, 'Observations:') ||
                str_starts_with($line, 'Type:') ||
                str_starts_with($line, 'Durée estimée:') ||
                str_starts_with($line, 'Coût')) {
                break;
            }
            $description[] = $line;
        }

        return trim(implode("\n", $description));
    }

    private function extractObservationsFromRapport(string $rapport): string
    {
        if (preg_match('/Observations: (.+?)(?:\n\n|$)/s', $rapport, $matches)) {
            return trim($matches[1]);
        }

        return '';
    }

    private function extractDureeFromRapport(string $rapport): string
    {
        if (preg_match('/Durée estimée: (.+?)h/m', $rapport, $matches)) {
            return $matches[1];
        }

        return '';
    }

    private function extractCoutMainOeuvreFromRapport(string $rapport): string
    {
        if (preg_match('/Coût main d\'œuvre: (.+?)€/m', $rapport, $matches)) {
            return $matches[1];
        }

        return '';
    }

    private function extractCoutPiecesFromRapport(string $rapport): string
    {
        if (preg_match('/Coût pièces: (.+?)€/m', $rapport, $matches)) {
            return $matches[1];
        }

        return '';
    }

    /**
     * Déterminer le résultat d'une intervention basé sur son statut
     */
    private function determineResultat($intervention): string
    {
        switch ($intervention->statut) {
            case 'terminee':
                // Si terminée, on considère que c'est réussi par défaut
                // On pourrait améliorer cela en analysant le rapport
                return 'reussi';
            case 'annulee':
                return 'echec';
            case 'en_cours':
            case 'programmee':
            default:
                // Pour les interventions en cours ou programmées, on ne retourne pas de résultat
                // car elles ne sont pas encore terminées
                return '';
        }
    }
}
