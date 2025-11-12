@extends('layouts.app')

@section('title', 'Mapa de Establecimiento')

@section('content')
<div class="max-w-2xl mx-auto mt-8">
    <x-card>
        <x-slot name="header">
            <div class="flex items-center space-x-3">
                <i class="fas fa-map-marker-alt text-xl text-aurora-cyan"></i>
                <h3 class="font-headings text-xl text-light-text">{{ $establishment->name }} - Mapa</h3>
            </div>
        </x-slot>
        <div class="mb-4">
            <div id="map" style="height:70vh; width:100%; border-radius:12px; overflow:hidden;"></div>
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var map = L.map('map').setView([{{ $establishment->latitude }}, {{ $establishment->longitude }}], 16);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);
                    L.marker([{{ $establishment->latitude }}, {{ $establishment->longitude }}]).addTo(map)
                        .bindPopup("{{ $establishment->name }}").openPopup();
                });
            </script>
        </div>
        <div class="text-sm text-light-text-muted">
            <strong>Dirección:</strong> {{ $establishment->address_street }}<br>
            <strong>Ciudad:</strong> {{ $establishment->address_city }}<br>
            <strong>Provincia:</strong> {{ $establishment->address_state }}<br>
            <strong>País:</strong> {{ $establishment->address_country }}<br>
            <strong>Latitud:</strong> {{ $establishment->latitude }}<br>
            <strong>Longitud:</strong> {{ $establishment->longitude }}<br>
            @if($establishment->notes)
                <div class="mt-2 text-xs"><strong>Notas:</strong> {{ $establishment->notes }}</div>
            @endif
        </div>
        @php
            $contacto = $establishment->contacts->first();
        @endphp
        @if($contacto)
            <div class="mt-4 bg-gray-900/60 rounded-lg p-3 w-56">
                <p class="font-semibold text-light-text">Contacto principal</p>
                <p class="text-sm text-light-text-muted">{{ $contacto->name }}</p>
                @if($contacto->email)
                    <p class="text-xs text-light-text-muted">{{ $contacto->email }}</p>
                @endif
                @if($contacto->phone)
                    <p class="text-xs text-light-text-muted">{{ $contacto->phone }}</p>
                @endif
            </div>
        @endif
    </x-card>
</div>
@endsection
