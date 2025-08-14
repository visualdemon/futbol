<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Game;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        // Fecha inicial: 28 de agosto de 2025
        $startDate = new \DateTime('2025-08-28');
        // Fecha final: 25 de diciembre de 2025 (puedes ajustar si quieres más o menos)
        $endDate = new \DateTime('2025-12-31');

        while ($startDate <= $endDate) {
            // Guarda el juego solo si no existe ya para esa fecha
            Game::firstOrCreate([
                'date' => $startDate->format('Y-m-d')
            ]);
            // Avanza 7 días al siguiente jueves
            $startDate->modify('+7 days');
        }
    }
}
