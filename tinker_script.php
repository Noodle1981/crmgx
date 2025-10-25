<?php
// Conteo de Contactos
$totalContacts = App\Models\Contact::count();
$contactsInSequence = App\Models\Contact::whereHas('sequenceEnrollments', function ($query) {
    $query->where('status', 'active');
})->count();

// Cálculo del porcentaje de contactos en secuencias
$percentageInSequence = ($totalContacts > 0) ? ($contactsInSequence / $totalContacts) * 100 : 0;

// Conteo de Leads
$totalLeads = App\Models\Lead::count();

// Conteo de Leads "Fríos"
// Definición: Status 'Rechazado' o 'No Calificado' Y sin actividad en los últimos 30 días.
$coldLeads = App\Models\Lead::whereIn('status', ['Rechazado', 'No Calificado'])
    ->whereDoesntHave('activities', function ($query) {
        $query->where('created_at', '>=', now()->subDays(30));
    })
    ->count();

// Cálculo de la tasa de leads fríos
$coldLeadRate = ($totalLeads > 0) ? ($coldLeads / $totalLeads) * 100 : 0;

echo "--- Reporte de Nutrición y Seguimiento ---\\n";
echo "Contactos Inscritos en Secuencias:\n";
echo "Total de Contactos: " . $totalContacts . "\n";
echo "Contactos en Secuencia Activa: " . $contactsInSequence . "\n";
echo "Porcentaje: " . number_format($percentageInSequence, 2) . " %\n\n";

echo "Análisis de Leads Fríos:\n";
echo "Total de Leads: " . $totalLeads . "\n";
echo "Leads Considerados Fríos: " . $coldLeads . "\n";
echo "Tasa de Leads Fríos: " . number_format($coldLeadRate, 2) . " %\n";
?>
