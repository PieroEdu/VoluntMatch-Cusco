<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\GeocodingService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GeocodingServiceTest extends TestCase
{
    public function test_geocoding_service_resolves_coordinates_via_api()
    {
        Http::fake([
            'nominatim.openstreetmap.org/*' => Http::response([
                ['lat' => '-13.51', 'lon' => '-71.97']
            ], 200),
        ]);

        $service = new GeocodingService();
        $coords = $service->geocode('Wanchaq');

        $this->assertEquals(-13.51, $coords['lat']);
        $this->assertEquals(-71.97, $coords['lng']);
        $this->assertEquals('nominatim_api', $coords['source']);
    }

    public function test_geocoding_service_uses_district_fallback_on_api_failure()
    {
        Http::fake([
            'nominatim.openstreetmap.org/*' => Http::response([], 500),
        ]);

        $service = new GeocodingService();
        $coords = $service->geocode('Wanchaq');

        // Wanchaq fallback: -13.526315, -71.956621
        $this->assertEquals(-13.526315, $coords['lat']);
        $this->assertEquals(-71.956621, $coords['lng']);
        $this->assertEquals('district_fallback', $coords['source']);
    }
}
