<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Devis;
use App\Models\Equipement;
use App\Models\Facture;
use App\Models\Intervention;
use App\Models\Panne;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();
        $isAdmin = $user->hasRole('admin');
        $isTechnicien = $user->hasRole('technicien');

        // Statistiques de base
        $stats = [
            'clients' => Client::count(),
            'equipements' => Equipement::count(),
            'pannesActives' => Panne::whereIn('statut_demande_id', [1, 2])->count(),
            'interventionsMois' => Intervention::whereMonth('date_debut', now()->month)
                ->whereYear('date_debut', now()->year)
                ->count(),
        ];

        // Pannes urgentes (communes à tous)
        $pannesUrgentes = Panne::with(['equipement.site.client', 'statutDemande'])
            ->where('priorite', 'urgente')
            ->whereIn('statut_demande_id', [1, 2]) // En attente ou en cours
            ->latest()
            ->take(5)
            ->get();

        $data = [
            'stats' => $stats,
            'pannesUrgentes' => $pannesUrgentes,
            'devisEnAttente' => [],
            'facturesImpayees' => [],
            'mesInterventions' => [],
        ];

        // Données spécifiques aux admins
        if ($isAdmin) {
            $data['devisEnAttente'] = Devis::with('client')
                ->where('statut', 'envoye')
                ->latest()
                ->take(5)
                ->get();

            $data['facturesImpayees'] = Facture::with('client')
                ->whereIn('statut', ['emise', 'en_retard'])
                ->latest()
                ->take(5)
                ->get();
        }

        // Données spécifiques aux techniciens
        if ($isTechnicien) {
            $data['mesInterventions'] = Intervention::with(['equipement.site', 'panne'])
                ->where('technicien_id', $user->id)
                ->where(function ($query) {
                    $query->whereNull('date_fin')
                        ->orWhere('date_debut', '>=', now()->startOfWeek());
                })
                ->latest('date_debut')
                ->take(5)
                ->get();
        }

        return Inertia::render('Dashboard', $data);
    }
}
