<?php

// archivo que carga datos iniciales
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// esta clase carga datos iniciales
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    // carga datos de inicio

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Usuario de prueba',
            'email' => 'test@example.com',
        ]);
    }
}
