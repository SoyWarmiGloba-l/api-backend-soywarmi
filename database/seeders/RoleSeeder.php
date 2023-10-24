<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'Administrador',
            'description' => 'Administrador del sistema',
        ]);
        Role::create([
            'name' => 'Medico',
            'description' => 'Medico del sistema',
        ]);
        Role::create([
            'name' => 'Publico General',
            'description' => 'Publico General del sistema',
        ]);
        Role::create([
            'name' => 'Voluntario',
            'description' => 'Voluntario',
        ]);
    }
}
