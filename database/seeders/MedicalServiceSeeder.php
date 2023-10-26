<?php

namespace Database\Seeders;

use App\Models\MedicalService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicalServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MedicalService::factory()->count(10)->create();
    }
}
