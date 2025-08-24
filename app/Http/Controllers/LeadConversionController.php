<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeadConversionController extends Controller
{
    public function convert (Lead $lead)
    {
        // 1. Seguridad
        if (Auth::user()->id !== $lead->user_id) {
            abort(404);
        }

        // 2. Lógica de negocio
        if ($lead->status !== 'calificado') {
            return redirect()->route('leads.index')->with('error', '¡Solo se pueden convertir leads calificados!');
        }

        // 3. ¡La Magia en una Transacción!
        DB::transaction(function () use ($lead) {
            $client = Client::create([
                'name' => $lead->company ?? $lead->name,
                'company' => $lead->company,
                'email' => $lead->email,
                'phone' => $lead->phone,
                'user_id' => $lead->user_id,
            ]);

            $client->contacts()->create([
                'name' => $lead->name,
                'email' => $lead->email,
                'phone' => $lead->phone,
            ]);

            $client->deals()->create([
                'name' => 'Oportunidad para ' . $client->name,
                'user_id' => $lead->user_id,
                'deal_stage_id' => 1,
                'status' => 'open',
            ]);

            $lead->update(['status' => 'convertido']);
        });

        // 4. Redirigir al pipeline para ver el nuevo deal
        return redirect()->route('deals.index')->with('success', '¡Lead convertido con éxito! Nueva oportunidad creada.');
    }
}  