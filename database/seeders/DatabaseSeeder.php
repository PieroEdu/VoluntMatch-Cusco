<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Esto creará los roles automáticamente cada vez que reinicies la base de datos[cite: 3]
        Role::firstOrCreate(['name' => 'Voluntario']);
        Role::firstOrCreate(['name' => 'Organizacion']);
        Role::firstOrCreate(['name' => 'Admin']);

        // Crear o actualizar el usuario Administrador por defecto
        User::updateOrCreate(
            ['email' => 'admin@voluntmatch.edu.pe'],
            [
                'name' => 'Coordinación Proyección Social UC',
                'password' => Hash::make('admin12345'),
                'tipo_usuario' => 'admin',
            ]
        );
    }
}