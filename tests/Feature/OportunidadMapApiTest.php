<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Oportunidad;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OportunidadMapApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_map_api_returns_active_opportunities_with_coordinates()
    {
        $oportunidad = Oportunidad::create([
            'titulo' => 'Reforestación en San Jerónimo',
            'ong' => 'Verde Cusco',
            'distrito' => 'San Jerónimo',
            'categoria' => 'Medio Ambiente',
            'clase_insignia' => 'badge-ambiente',
            'modalidad' => 'Presencial',
            'horario' => 'Sábados',
            'cupos' => 10,
            'cupos_restantes' => 10,
            'descripcion' => 'Actividad ambiental comunitaria.',
            'latitud' => -13.545914,
            'longitud' => -71.890327,
            'destacada' => true,
        ]);

        $response = $this->getJson(route('api.oportunidades.mapa'));

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'total',
                     'centro_cusco' => ['lat', 'lng'],
                     'oportunidades' => [
                         '*' => [
                             'id',
                             'titulo',
                             'ong',
                             'distrito',
                             'categoria',
                             'modalidad',
                             'lat',
                             'lng',
                             'url_detalle',
                         ]
                     ]
                 ]);

        $this->assertEquals(1, $response->json('total'));
        $this->assertEquals(-13.545914, $response->json('oportunidades.0.lat'));
        $this->assertEquals(-71.890327, $response->json('oportunidades.0.lng'));
    }

    public function test_catalog_view_renders_map_toggle_and_container()
    {
        $response = $this->get(route('oportunidades'));

        $response->assertStatus(200);
        $response->assertSee('contenedor-mapa', false);
        $response->assertSee('btn-vista-mapa', false);
    }
}
