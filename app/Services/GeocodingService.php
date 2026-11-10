<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeocodingService
{
    /**
     * Coordenadas centrales de distritos en Cusco para fallback inmediato.
     */
    protected array $districtFallbacks = [
        'wanchaq'       => ['lat' => -13.526315, 'lng' => -71.956621],
        'san sebastián' => ['lat' => -13.530669, 'lng' => -71.928731],
        'san sebastian' => ['lat' => -13.530669, 'lng' => -71.928731],
        'san jerónimo'  => ['lat' => -13.545914, 'lng' => -71.890327],
        'san jeronimo'  => ['lat' => -13.545914, 'lng' => -71.890327],
        'santiago'      => ['lat' => -13.528489, 'lng' => -71.986877],
        'saylla'        => ['lat' => -13.565882, 'lng' => -71.828629],
        'poroy'         => ['lat' => -13.497523, 'lng' => -72.039641],
        'ccorca'        => ['lat' => -13.583333, 'lng' => -72.033333],
        'cusco'         => ['lat' => -13.517088, 'lng' => -71.978535],
    ];

    /**
     * Resuelve las coordenadas (lat, lng) para un distrito o dirección en Cusco.
     * Utiliza la API pública de Nominatim OpenStreetMap con fallback y caché local.
     */
    public function geocode(string $distrito, ?string $direccion = null): array
    {
        $distritoLimpio = trim($distrito);
        $busqueda = $direccion ? "{$direccion}, {$distritoLimpio}, Cusco, Peru" : "{$distritoLimpio}, Cusco, Peru";
        $cacheKey = 'geo_' . md5(strtolower($busqueda));

        return Cache::remember($cacheKey, now()->addDays(7), function () use ($busqueda, $distritoLimpio) {
            try {
                $response = Http::withHeaders([
                    'User-Agent' => 'VoluntMatch-Cusco/1.0 (contacto@voluntmatch.org)',
                    'Accept'     => 'application/json',
                ])->timeout(5)->get('https://nominatim.openstreetmap.org/search', [
                    'q'      => $busqueda,
                    'format' => 'json',
                    'limit'  => 1,
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                        return [
                            'lat'    => (float) $data[0]['lat'],
                            'lng'    => (float) $data[0]['lon'],
                            'source' => 'nominatim_api',
                        ];
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Error consultando Nominatim Geocoding API: ' . $e->getMessage());
            }

            // Fallback a las coordenadas predeterminadas del distrito
            return $this->getDistrictFallback($distritoLimpio);
        });
    }

    /**
     * Retorna coordenadas de respaldo basadas en el distrito de Cusco.
     */
    public function getDistrictFallback(string $distrito): array
    {
        $key = strtolower(trim($distrito));

        if (isset($this->districtFallbacks[$key])) {
            return array_merge($this->districtFallbacks[$key], ['source' => 'district_fallback']);
        }

        // Si no coincide con ninguno, centro de Cusco
        return [
            'lat'    => -13.517088,
            'lng'    => -71.978535,
            'source' => 'default_cusco',
        ];
    }
}
