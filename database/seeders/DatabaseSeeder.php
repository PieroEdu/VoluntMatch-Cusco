<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Esto creará los roles automáticamente cada vez que reinicies la base de datos
        Role::firstOrCreate(['name' => 'Voluntario']);
        Role::firstOrCreate(['name' => 'Organizacion']);
        Role::firstOrCreate(['name' => 'Admin']);
    }
}