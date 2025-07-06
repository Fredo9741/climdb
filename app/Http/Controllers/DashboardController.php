<?php

namespace App\Http\Controllers;

use App\Models\BouteilleGaz;
use App\Models\Client;
use App\Models\Devis;
use App\Models\Equipement;
use App\Models\Facture;
use App\Models\Intervention;
use App\Models\Panne;
use App\Models\Site;
use App\Models\Vehicule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $user = $request->user();
        $isAdmin = $user->hasRole('admin');
        $isTechnicien = $user->hasRole('technicien');

        // Statistiques principales enrichies
        $stats = [
            'clients' => Client::count(),
            'sites' => Site::count(),
            'equipements' => Equipement::count(),
            'pannesActives' => Panne::whereIn('statut_demande_id', [1, 2])->count(),
            'interventionsMois' => Intervention::whereMonth('date_planifiee', now()->month)
                ->whereYear('date_planifiee', now()->year)
                ->count(),
            'vehicules' => Vehicule::count(),
            'bouteillesGaz' => BouteilleGaz::count(),
            'facturesEnAttente' => Facture::where('statut', 'emise')->count(),
            'chiffreAffaireMois' => Facture::where('statut', 'payee')
                ->whereMonth('date_facture', now()->month)
                ->whereYear('date_facture', now()->year)
                ->sum('montant_ttc'),
            'devisEnCours' => Devis::whereIn('statut', ['brouillon', 'envoye'])->count(),
        ];

        // Statistiques détaillées par statut
        $statsDetaillees = [
            'pannesParPriorite' => [
                'faible' => Panne::where('priorite', 'faible')->whereIn('statut_demande_id', [1, 2])->count(),
                'moyenne' => Panne::where('priorite', 'moyenne')->whereIn('statut_demande_id', [1, 2])->count(),
                'haute' => Panne::where('priorite', 'haute')->whereIn('statut_demande_id', [1, 2])->count(),
                'urgente' => Panne::where('priorite', 'urgente')->whereIn('statut_demande_id', [1, 2])->count(),
            ],
            'interventionsParStatut' => [
                'programmee' => Intervention::where('statut', 'programmee')->count(),
                'en_cours' => Intervention::where('statut', 'en_cours')->count(),
                'terminee' => Intervention::where('statut', 'terminee')->count(),
                'annulee' => Intervention::where('statut', 'annulee')->count(),
            ],
            'bouteillesParStatut' => [
                'disponible' => BouteilleGaz::whereHas('statutBouteille', function($q) { 
                    $q->where('nom', 'Disponible'); 
                })->count(),
                'en_service' => BouteilleGaz::whereHas('statutBouteille', function($q) { 
                    $q->where('nom', 'En service'); 
                })->count(),
                'vide' => BouteilleGaz::whereHas('statutBouteille', function($q) { 
                    $q->where('nom', 'Vide'); 
                })->count(),
            ],
        ];

        // Activité récente (dernières 7 jours)
        $activiteRecente = [
            'interventions' => Intervention::with(['technicien', 'panne.equipement.site'])
                ->where('created_at', '>=', now()->subDays(7))
                ->latest()
                ->take(5)
                ->get(),
            'pannes' => Panne::with(['equipement.site.client'])
                ->where('created_at', '>=', now()->subDays(7))
                ->latest()
                ->take(5)
                ->get(),
            'devis' => Devis::with('client')
                ->where('created_at', '>=', now()->subDays(7))
                ->latest()
                ->take(5)
                ->get(),
        ];

        // Pannes urgentes avec relations corrigées
        $pannesUrgentes = Panne::with(['equipement.site.client', 'statutDemande'])
            ->where('priorite', 'urgente')
            ->whereIn('statut_demande_id', [1, 2])
            ->latest('date_constat')
            ->take(8)
            ->get();

        // Actions rapides selon le rôle
        $actionsRapides = [];
        if ($isAdmin) {
            $actionsRapides = [
                ['label' => 'Nouveau client', 'route' => 'clients.create', 'icon' => 'UserPlus', 'color' => 'blue'],
                ['label' => 'Nouvel équipement', 'route' => 'equipements.create', 'icon' => 'Plus', 'color' => 'green'],
                ['label' => 'Nouveau devis', 'route' => 'devis.create', 'icon' => 'FileText', 'color' => 'purple'],
                ['label' => 'Nouvelle facture', 'route' => 'factures.create', 'icon' => 'Receipt', 'color' => 'orange'],
            ];
        } elseif ($isTechnicien) {
            $actionsRapides = [
                ['label' => 'Signaler une panne', 'route' => 'pannes.create', 'icon' => 'AlertTriangle', 'color' => 'red'],
                ['label' => 'Nouvelle intervention', 'route' => 'interventions.create', 'icon' => 'Wrench', 'color' => 'blue'],
                ['label' => 'Mes interventions', 'route' => 'interventions.index', 'icon' => 'Calendar', 'color' => 'green'],
            ];
        }

        $data = [
            'stats' => $stats,
            'statsDetaillees' => $statsDetaillees,
            'activiteRecente' => $activiteRecente,
            'pannesUrgentes' => $pannesUrgentes,
            'actionsRapides' => $actionsRapides,
            'isAdmin' => $isAdmin,
            'isTechnicien' => $isTechnicien,
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

            // Évolution du chiffre d'affaires sur 6 mois
            $evolutionCA = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $ca = Facture::where('statut', 'payee')
                    ->whereMonth('date_facture', $date->month)
                    ->whereYear('date_facture', $date->year)
                    ->sum('montant_ttc');
                $evolutionCA[] = [
                    'mois' => $date->format('M Y'),
                    'montant' => $ca
                ];
            }
            $data['evolutionCA'] = $evolutionCA;
        }

        // Données spécifiques aux techniciens
        if ($isTechnicien) {
            $data['mesInterventions'] = Intervention::with(['panne.equipement.site', 'technicien'])
                ->where('technicien_id', $user->id)
                ->whereIn('statut', ['programmee', 'en_cours'])
                ->latest('date_planifiee')
                ->take(5)
                ->get();

            $data['mesStatsTechnicien'] = [
                'interventionsTotal' => Intervention::where('technicien_id', $user->id)->count(),
                'interventionsMois' => Intervention::where('technicien_id', $user->id)
                    ->whereMonth('date_planifiee', now()->month)
                    ->count(),
                'interventionsTerminees' => Intervention::where('technicien_id', $user->id)
                    ->where('statut', 'terminee')
                    ->count(),
                'interventionsEnCours' => Intervention::where('technicien_id', $user->id)
                    ->where('statut', 'en_cours')
                    ->count(),
            ];
        }

        return Inertia::render('Dashboard', $data);
    }
}
