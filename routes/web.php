<?php

use App\Http\Controllers\BouteilleGazController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\EquipementController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\ModeleController;
use App\Http\Controllers\ModeleReleveController;
use App\Http\Controllers\MouvementGazController;
use App\Http\Controllers\PanneController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\VehiculeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Routes pour les clients
Route::resource('clients', ClientController::class)->middleware(['auth', 'verified']);

// Routes pour les sites
Route::resource('sites', SiteController::class)->middleware(['auth', 'verified']);

// Routes pour les modèles
Route::resource('modeles', ModeleController::class)->middleware(['auth', 'verified']);

// Routes pour les modèles de relevés (Administration)
Route::resource('modeles-releves', ModeleReleveController::class)->middleware(['auth', 'verified']);

// Routes pour les équipements
Route::resource('equipements', EquipementController::class)->middleware(['auth', 'verified']);

// Routes pour les véhicules
Route::resource('vehicules', VehiculeController::class)->middleware(['auth', 'verified']);

// Routes pour les bouteilles de gaz
Route::resource('bouteilles-gaz', BouteilleGazController::class)->middleware(['auth', 'verified']);

// Routes pour les mouvements de gaz
Route::resource('mouvements-gaz', MouvementGazController::class)->middleware(['auth', 'verified']);

// Routes pour les pannes
Route::resource('pannes', PanneController::class)->middleware(['auth', 'verified']);

// Routes pour les interventions
Route::resource('interventions', InterventionController::class)->middleware(['auth', 'verified']);

// Routes pour les devis
Route::resource('devis', DevisController::class)->middleware(['auth', 'verified']);
Route::patch('devis/{devis}/accepter', [DevisController::class, 'accepter'])->name('devis.accepter')->middleware(['auth', 'verified']);
Route::patch('devis/{devis}/refuser', [DevisController::class, 'refuser'])->name('devis.refuser')->middleware(['auth', 'verified']);

// Routes pour les factures
Route::resource('factures', FactureController::class)->middleware(['auth', 'verified']);
Route::patch('factures/{facture}/marquer-payee', [FactureController::class, 'marquerPayee'])->name('factures.marquer-payee')->middleware(['auth', 'verified']);
Route::post('devis/{devis}/generer-facture', [FactureController::class, 'genererDepuisDevis'])->name('devis.generer-facture')->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
