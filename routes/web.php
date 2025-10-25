<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DealController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeadConversionController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityController; 
use App\Http\Controllers\SequenceController;
use App\Http\Controllers\SequenceStepController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\SalesReportController;
use App\Models\Step;
use App\Http\Controllers\Api\ChartController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\TaskController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Rutas de Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Rutas de Configuración de correo electrónico
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');

    // Rutas para el Calendario
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

    // Ruta para editar Tareas (usada por el calendario)
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');

    // Rutas para Tareas (CRUD completo)
    Route::resource('tasks', TaskController::class);
});



    // Rutas para Clientes
    Route::resource('clients', ClientController::class);

    // Rutas para Leads
    Route::resource('leads', LeadController::class);

    // Rutas para Deals (Pipeline y CRUD)
        Route::resource('deals', DealController::class); // Usamos resource para todo el CRUD
    Route::patch('/deals/{deal}/update-stage', [DealController::class, 'updateStage'])->name('deals.updateStage'); // Mantenemos esta para las flechas
    Route::patch('/deals/{deal}/win', [DealController::class, 'markAsWon'])->name('deals.win');
    Route::patch('/deals/{deal}/lose', [DealController::class, 'markAsLost'])->name('deals.lost');
   
    Route::post('/leads/{lead}/convert', [LeadConversionController::class, 'convert'])->name('leads.convert');
    Route::patch('/leads/{lead}/update-status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');

    Route::resource('clients.contacts', ContactController::class)->scoped()->except(['index', 'show']);
    Route::get('/clients/{client}/deals/create', [DealController::class, 'create'])->name('clients.deals.create');
    Route::post('/clients/{client}/deals', [DealController::class, 'store'])->name('clients.deals.store');
    Route::post('/clients/{client}/activities', [ActivityController::class, 'storeForClient'])->name('clients.activities.store');
    Route::post('/deals/{deal}/activities', [ActivityController::class, 'storeForDeal'])->name('deals.activities.store');


    Route::resource('sequences', SequenceController::class);
    Route::resource('sequences.steps', SequenceStepController::class)->scoped();

    // --- BLOQUE PARA LA INSCRIPCIÓN! ---
    Route::get('/contacts/{contact}/enroll', [EnrollmentController::class, 'create'])->name('contacts.enroll.create');
    Route::post('/contacts/{contact}/enroll', [EnrollmentController::class, 'store'])->name('contacts.enroll.store');

    Route::get('/reports/sales', [SalesReportController::class, 'index'])->name('reports.sales');

    Route::get('/charts/pipeline', [DashboardController::class, 'getPipelineData'])->name('charts.pipeline');
  
require __DIR__.'/auth.php';


