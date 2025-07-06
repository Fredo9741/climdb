<?php

namespace App\Http\Controllers;

use App\Models\ReleveMesure;
use App\Models\Intervention;
use App\Models\Equipement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReleveMesureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $releves = ReleveMesure::with(['equipement.site.client', 'user', 'intervention'])
            ->latest('date_mesure')
            ->paginate(20);

        return Inertia::render('releves/Index', [
            'releves' => $releves,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('releves/Create', [
            'equipements' => Equipement::select('id', 'nom')->orderBy('nom')->get(),
            'interventions' => Intervention::select('id', 'date_planifiee')->latest()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'intervention_id'     => 'nullable|exists:interventions,id',
            'equipement_id'       => 'required|exists:equipements,id',
            'user_id'             => 'required|exists:users,id',
            'type_mesure'         => 'required|string|max:255',
            'valeur'              => 'required|numeric',
            'unite'               => 'required|string|max:20',
            'date_mesure'         => 'required|date',
            'emplacement_mesure'  => 'nullable|string|max:255',
            'commentaire'         => 'nullable|string',
        ]);

        ReleveMesure::create($validated);

        return redirect()->route('releves-mesures.index')
            ->with('success', 'Relevé de mesure enregistré avec succès.');
    }

    /**
     * Show the resource.
     */
    public function show(ReleveMesure $releves_mesure): Response
    {
        $releves_mesure->load(['equipement.site.client', 'user', 'intervention']);

        return Inertia::render('releves/Show', [
            'releve' => $releves_mesure,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReleveMesure $releves_mesure): RedirectResponse
    {
        $releves_mesure->delete();

        return redirect()->route('releves-mesures.index')
            ->with('success', 'Relevé supprimé avec succès.');
    }
}