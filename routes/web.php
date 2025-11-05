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
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeadReminderController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    // Redirigir a administradores a su panel
    if (auth()->check() && auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return app(DashboardController::class)->index();
})->middleware(['auth', 'verified', 'user'])->name('dashboard');

Route::middleware(['auth', 'user'])->group(function () {
    // Estadísticas personales se manejan en el DashboardController
    Route::get('/my-stats', [DashboardController::class, 'personalStats'])->name('user.stats');
    Route::get('/my-pipeline', [DashboardController::class, 'personalPipeline'])->name('user.pipeline');
    Route::get('/my-month', [DashboardController::class, 'currentMonth'])->name('user.month');

    // Rutas de Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rutas de Configuración personal
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');    // Rutas para el Calendario
    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar.index');
    Route::get('/calendar/events', [CalendarController::class, 'events'])->name('calendar.events');

    // Ruta para editar Tareas (usada por el calendario)
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');

    // Rutas para Tareas (CRUD completo)
    Route::resource('tasks', TaskController::class);

    // Rutas para Clientes y sus establecimientos
    Route::resource('clients', ClientController::class);
    Route::get('/clients/{client}/data', [ClientController::class, 'data'])->name('clients.data');
    Route::resource('clients.establishments', EstablishmentController::class)->scoped();
    Route::get('/establishments', [EstablishmentController::class, 'indexAll'])->name('establishments.indexAll');

    // Rutas para Leads
    Route::resource('leads', LeadController::class);
    Route::post('/leads/{lead}/convert', [LeadConversionController::class, 'convert'])->name('leads.convert');
    Route::patch('/leads/{lead}/update-status', [LeadController::class, 'updateStatus'])->name('leads.updateStatus');

    // Rutas para Recordatorios de Leads
    Route::post('/lead-reminders', [LeadReminderController::class, 'store'])->name('lead-reminders.store');
    Route::patch('/lead-reminders/{reminder}/complete', [LeadReminderController::class, 'complete'])->name('lead-reminders.complete');
    Route::delete('/lead-reminders/{reminder}', [LeadReminderController::class, 'destroy'])->name('lead-reminders.destroy');


    // Rutas para Deals (Pipeline y CRUD)
    Route::resource('deals', DealController::class); // Usamos resource para todo el CRUD
    Route::patch('/deals/{deal}/update-stage', [DealController::class, 'updateStage'])->name('deals.updateStage'); // Mantenemos esta para las flechas
    Route::patch('/deals/{deal}/win', [DealController::class, 'markAsWon'])->name('deals.win');
    Route::patch('/deals/{deal}/lose', [DealController::class, 'markAsLost'])->name('deals.lost');

    Route::resource('clients.contacts', ContactController::class)->scoped()->except(['index', 'show']);
    Route::get('/contacts/{contact}', [ContactController::class, 'show'])->name('contacts.show');
    Route::get('/clients/{client}/deals/create', [DealController::class, 'create'])->name('clients.deals.create');
    Route::post('/clients/{client}/deals', [DealController::class, 'store'])->name('clients.deals.store');
    Route::post('/clients/{client}/activities', [ActivityController::class, 'storeForClient'])->name('clients.activities.store');
    Route::post('/deals/{deal}/activities', [ActivityController::class, 'storeForDeal'])->name('deals.activities.store');


    Route::resource('sequences', SequenceController::class);
    Route::resource('sequences.steps', SequenceStepController::class)->scoped();

    // --- BLOQUE PARA LA INSCRIPCIÓN! ---
    Route::get('/enrollments', [EnrollmentController::class, 'index'])->name('enrollments.index');
    Route::delete('/enrollments/{enrollment}', [EnrollmentController::class, 'destroy'])->name('enrollments.destroy');
    Route::patch('/enrollments/{enrollment}/complete-step', [EnrollmentController::class, 'completeStep'])->name('enrollments.completeStep');
    Route::get('/contacts/{contact}/enroll', [EnrollmentController::class, 'create'])->name('contacts.enroll.create');
    Route::post('/contacts/{contact}/enroll', [EnrollmentController::class, 'store'])->name('contacts.enroll.store');

    Route::get('/reports/sales', [SalesReportController::class, 'index'])->name('reports.sales');

    Route::get('/charts/pipeline', [DashboardController::class, 'getPipelineData'])->name('charts.pipeline');
    Route::get('/dashboard/user', [UserController::class, 'showCurrentUser'])->middleware(['auth'])->name('dashboard.user');

});


    Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Panel de control del administrador
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/stats', [App\Http\Controllers\Admin\DashboardController::class, 'stats'])->name('stats');
    
    // Gestión de usuarios
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    
    // Métricas de rendimiento
    Route::get('/performance', [App\Http\Controllers\Admin\PerformanceController::class, 'index'])->name('performance.index');
    Route::get('/performance/export', [App\Http\Controllers\Admin\PerformanceController::class, 'export'])->name('performance.export');
    
    // Logs y mantenimiento
    Route::get('/system-logs', [App\Http\Controllers\Admin\DashboardController::class, 'logs'])->name('logs');
    Route::get('/maintenance', [App\Http\Controllers\Admin\MaintenanceController::class, 'index'])->name('maintenance');
    Route::post('/maintenance/backup', [App\Http\Controllers\Admin\MaintenanceController::class, 'backup'])->name('maintenance.backup');
    Route::post('/maintenance/optimize', [App\Http\Controllers\Admin\MaintenanceController::class, 'optimize'])->name('maintenance.optimize');
    Route::post('/maintenance/clear-cache', [App\Http\Controllers\Admin\MaintenanceController::class, 'clearCache'])->name('maintenance.clear-cache');
    Route::post('/maintenance/clear-views', [App\Http\Controllers\Admin\MaintenanceController::class, 'clearViews'])->name('maintenance.clear-views');
    Route::post('/maintenance/clean-logs', [App\Http\Controllers\Admin\MaintenanceController::class, 'cleanLogs'])->name('maintenance.clean-logs');
    Route::post('/maintenance/clean-sessions', [App\Http\Controllers\Admin\MaintenanceController::class, 'cleanSessions'])->name('maintenance.clean-sessions');
    
    // Configuraciones del sistema
    Route::get('/settings/email', [App\Http\Controllers\Admin\SettingsController::class, 'emailConfig'])->name('settings.email');
    Route::post('/settings/email', [App\Http\Controllers\Admin\SettingsController::class, 'updateEmailConfig'])->name('settings.email.update');
});
require __DIR__.'/auth.php';