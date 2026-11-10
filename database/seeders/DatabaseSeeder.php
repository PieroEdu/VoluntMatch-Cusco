<?php

namespace Database\Seeders;

use App\Models\Oportunidad;
use App\Models\Organizacion;
use App\Models\Postulacion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear usuario voluntario por defecto
        $user = User::updateOrCreate(
            ['email' => 'voluntario@continental.edu.pe'],
            [
                'name' => 'Luis Piero Dueñas Alcca',
                'password' => Hash::make('password123'),
            ]
        );

        // Cuenta de acceso para la ONG Hanpinawasi Cusco
        $userHanpinawasi = User::updateOrCreate(
            ['email' => 'ong@hanpinawasitravel.com'],
            [
                'name' => 'Asociación Civil Hanpinawasi Cusco',
                'password' => Hash::make('password123'),
            ]
        );

        // Cuenta de acceso para la ONG Hogar Infantil Azul Wasi
        $userAzulWasi = User::updateOrCreate(
            ['email' => 'contacto@azulwasi.org'],
            [
                'name' => 'Hogar Infantil Azul Wasi',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Crear Organizaciones de Cusco
        $org1 = Organizacion::updateOrCreate(
            ['nombre' => 'Hogar Infantil Azul Wasi'],
            [
                'ruc' => '20601234567',
                'distrito' => 'San Jerónimo',
                'direccion' => 'Valle Sur, Oropesa - San Jerónimo',
                'telefono' => '984123456',
                'correo' => 'contacto@azulwasi.org',
                'tipo' => 'Albergue Infantil',
                'verificada' => true,
            ]
        );

        $org2 = Organizacion::updateOrCreate(
            ['nombre' => 'Asociación Verde Cusco'],
            [
                'ruc' => '20609876543',
                'distrito' => 'Wanchaq',
                'direccion' => 'Av. de la Cultura 720',
                'telefono' => '984765432',
                'correo' => 'contacto@verde.cusco.pe',
                'tipo' => 'ONG Ambiental',
                'verificada' => true,
            ]
        );

        $org3 = Organizacion::updateOrCreate(
            ['nombre' => 'Colectivo Cusco Sostenible'],
            [
                'ruc' => '20605551234',
                'distrito' => 'Santiago',
                'direccion' => 'Calle Zarumilla 304',
                'telefono' => '984333222',
                'correo' => 'info@cuscosostenible.org',
                'tipo' => 'Colectivo Juvenil',
                'verificada' => true,
            ]
        );

        $org4 = Organizacion::updateOrCreate(
            ['nombre' => 'Colectivo Cusco Solidario'],
            [
                'ruc' => '20604449876',
                'distrito' => 'Cusco Centro',
                'direccion' => 'Plaza San Francisco 112',
                'telefono' => '984999888',
                'correo' => 'abrigarte@cuscosolidario.pe',
                'tipo' => 'Colectivo Social',
                'verificada' => true,
            ]
        );

        $org5 = Organizacion::updateOrCreate(
            ['nombre' => 'Asociación Yachay Wasi'],
            [
                'ruc' => '20603334445',
                'distrito' => 'San Jerónimo',
                'direccion' => 'Av. Manco Cápac 415',
                'telefono' => '984111333',
                'correo' => 'yachaywasi@gmail.com',
                'tipo' => 'Asociación Educativa',
                'verificada' => true,
            ]
        );

        $org6 = Organizacion::updateOrCreate(
            ['nombre' => 'Red Social Cusco Activo'],
            [
                'ruc' => '20602223331',
                'distrito' => 'Cusco Centro',
                'direccion' => 'Calle Maruri 220',
                'telefono' => '984444555',
                'correo' => 'red@cuscoactivo.pe',
                'tipo' => 'Red Comunitaria',
                'verificada' => true,
            ]
        );

        $orgHanpinawasi = Organizacion::updateOrCreate(
            ['nombre' => 'Asociación Civil Hanpinawasi Cusco'],
            [
                'ruc' => '20608912345',
                'distrito' => 'Cusco Centro',
                'direccion' => 'Calle Plateros 312, Centro Histórico, Cusco',
                'telefono' => '930782790',
                'correo' => 'ong@hanpinawasitravel.com',
                'tipo' => 'Asociación Civil / ONG',
                'verificada' => true,
            ]
        );

        // 3. Crear Oportunidades
        $op1 = Oportunidad::updateOrCreate(
            ['titulo' => 'Tutorías y Reforzamiento Escolar'],
            [
                'organizacion_id' => $org1->id,
                'ong' => 'Hogar Infantil Azul Wasi',
                'distrito' => 'San Jerónimo',
                'categoria' => 'Educación',
                'clase_insignia' => 'badge-educacion',
                'modalidad' => 'Presencial',
                'horario' => 'Sábados 9:00 AM - 1:00 PM',
                'cupos' => 4,
                'cupos_restantes' => 2,
                'imagen' => 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=600&q=80',
                'descripcion' => 'Apoyo pedagógico y tutorías dinámicas en matemáticas y lectura para niños y adolescentes en situación de acogimiento en el valle sur de Cusco.',
                'habilidades' => ['Docencia básica', 'Paciencia', 'Dinámicas grupales', 'Empatía'],
                'destacada' => true,
            ]
        );

        $op2 = Oportunidad::updateOrCreate(
            ['titulo' => 'Digitalización e Inventario de Donaciones'],
            [
                'organizacion_id' => $org2->id,
                'ong' => 'Asociación Verde Cusco',
                'distrito' => 'Wanchaq',
                'categoria' => 'Sistemas',
                'clase_insignia' => 'badge-sistemas',
                'modalidad' => 'Híbrido',
                'horario' => 'Lunes y Miércoles 3:00 PM - 6:00 PM',
                'cupos' => 2,
                'cupos_restantes' => 1,
                'imagen' => 'https://images.unsplash.com/photo-1531403009284-440f080d1e12?auto=format&fit=crop&w=600&q=80',
                'descripcion' => 'Diseño e implementación de una base de datos ligera y módulo web para clasificar donaciones de ropa y materiales ecológicos en Wanchaq.',
                'habilidades' => ['Excel avanzado', 'HTML/CSS', 'Bases de datos', 'Organización'],
                'destacada' => true,
            ]
        );

        $op3 = Oportunidad::updateOrCreate(
            ['titulo' => 'Campaña de Reforestación y Concientización'],
            [
                'organizacion_id' => $org3->id,
                'ong' => 'Colectivo Cusco Sostenible',
                'distrito' => 'Santiago',
                'categoria' => 'Medio Ambiente',
                'clase_insignia' => 'badge-ambiente',
                'modalidad' => 'Presencial',
                'horario' => 'Domingos 7:30 AM - 12:30 PM',
                'cupos' => 8,
                'cupos_restantes' => 6,
                'imagen' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=600&q=80',
                'descripcion' => 'Jornadas comunitarias de plantación de especies nativas (queñua y chachacomo) y talleres de reciclaje en zonas altas del distrito de Santiago.',
                'habilidades' => ['Trabajo en equipo', 'Resistencia física', 'Educación ambiental'],
                'destacada' => true,
            ]
        );

        $op4 = Oportunidad::updateOrCreate(
            ['titulo' => 'Clasificación y Logística de Abrigarte Cusco'],
            [
                'organizacion_id' => $org4->id,
                'ong' => 'Colectivo Cusco Solidario',
                'distrito' => 'Cusco Centro',
                'categoria' => 'Logística',
                'clase_insignia' => 'badge-logistica',
                'modalidad' => 'Presencial',
                'horario' => 'Viernes 2:00 PM - 6:00 PM',
                'cupos' => 6,
                'cupos_restantes' => 2,
                'imagen' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=600&q=80',
                'descripcion' => 'Recepción, selección y empaquetado de frazadas y prendas abrigadoras para comunidades afectadas por heladas en la provincia de Cusco.',
                'habilidades' => ['Orden', 'Logística básica', 'Proactividad'],
                'destacada' => false,
            ]
        );

        $op5 = Oportunidad::updateOrCreate(
            ['titulo' => 'Talleres de Alfabetización Digital para Adultos'],
            [
                'organizacion_id' => $org5->id,
                'ong' => 'Asociación Yachay Wasi',
                'distrito' => 'San Jerónimo',
                'categoria' => 'Educación',
                'clase_insignia' => 'badge-educacion',
                'modalidad' => 'Presencial',
                'horario' => 'Martes y Jueves 4:00 PM - 6:00 PM',
                'cupos' => 3,
                'cupos_restantes' => 3,
                'imagen' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=600&q=80',
                'descripcion' => 'Enseñanza de uso básico de computadoras, trámites en línea y prevención de estafas digitales a madres líderes de comedores populares.',
                'habilidades' => ['Habilidades comunicativas', 'Manejo de internet', 'Didáctica'],
                'destacada' => false,
            ]
        );

        $op6 = Oportunidad::updateOrCreate(
            ['titulo' => 'Soporte y Mantenimiento Web Comunitario'],
            [
                'organizacion_id' => $org6->id,
                'ong' => 'Red Social Cusco Activo',
                'distrito' => 'Cusco Centro',
                'categoria' => 'Sistemas',
                'clase_insignia' => 'badge-sistemas',
                'modalidad' => 'Virtual',
                'horario' => 'Flexible (4 horas semanales)',
                'cupos' => 2,
                'cupos_restantes' => 2,
                'imagen' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=600&q=80',
                'descripcion' => 'Actualización de contenidos, soporte a servidores web y mejora de accesibilidad en el portal informativo de la red de colectivos de Cusco.',
                'habilidades' => ['HTML5', 'CSS3', 'JavaScript', 'GitHub'],
                'destacada' => false,
            ]
        );

        $opHanpinawasi = Oportunidad::updateOrCreate(
            ['titulo' => 'Reforestación de Queñuales y Ayni Comunitario'],
            [
                'organizacion_id' => $orgHanpinawasi->id,
                'ong' => 'Asociación Civil Hanpinawasi Cusco',
                'distrito' => 'Cusco Centro',
                'latitud' => -13.5167,
                'longitud' => -71.9789,
                'categoria' => 'Medio Ambiente',
                'clase_insignia' => 'badge-ambiente',
                'modalidad' => 'Presencial',
                'horario' => 'Sábados y Domingos 7:00 AM - 1:00 PM',
                'cupos' => 8,
                'cupos_restantes' => 8,
                'imagen' => 'https://images.unsplash.com/photo-1589802829985-817e51171b92?auto=format&fit=crop&w=1200&h=630&q=85',
                'descripcion' => 'Iniciativa socioambiental de la ONG Hanpinawasi vinculada a la Nación Q\'ero y comunidades de los Andes cusqueños. Apoya en la reforestación de queñuales nativos (Polylepis), jornadas de concientización ecológica, salud básica preventiva y transmisión de saberes ancestrales bajo el principio sagrado del Ayni.',
                'habilidades' => ['Reforestación', 'Educación ambiental', 'Trabajo en equipo', 'Resistencia física', 'Sensibilidad intercultural'],
                'destacada' => true,
            ]
        );

        // 4. Crear postulaciones de ejemplo
        Postulacion::updateOrCreate(
            [
                'oportunidad_id' => $op1->id,
                'correo' => $user->email,
            ],
            [
                'user_id' => $user->id,
                'estudiante' => $user->name,
                'telefono' => '984123987',
                'carrera' => 'Ingeniería de Sistemas e Informática',
                'universidad' => 'Universidad Continental (Sede Cusco)',
                'mensaje_motivacion' => 'Tengo experiencia en docencia extracurricular y deseo apoyar en el desarrollo de competencias lógicas en niños del valle sur.',
                'estado' => 'Aceptado',
                'horas_acumuladas' => 8,
                'fecha' => '2026-10-04',
            ]
        );

        Postulacion::updateOrCreate(
            [
                'oportunidad_id' => $opHanpinawasi->id,
                'correo' => 'voluntario.ambiental@continental.edu.pe',
            ],
            [
                'user_id' => null,
                'estudiante' => 'Patrick Choquemamani',
                'telefono' => '984777123',
                'carrera' => 'Ingeniería Ambiental',
                'universidad' => 'Universidad Continental (Sede Cusco)',
                'mensaje_motivacion' => 'Deseo participar en las jornadas de reforestación de queñuales y aprender sobre las prácticas ancestrales de la Nación Q\'ero.',
                'estado' => 'Pendiente',
                'horas_acumuladas' => 0,
                'fecha' => '2026-10-15',
            ]
        );
    }
}
