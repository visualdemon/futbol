<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Game;
use Carbon\Carbon;

class CalculateMatchNumbers extends Command
{
    protected $signature = 'games:calculate-numbers {--season=2025}';
    protected $description = 'Calcula automáticamente los números de partido para la temporada';

    public function handle()
    {
        $season = $this->option('season');

        // Fecha de inicio de temporada (primer jueves después del 2 de enero)
        $startDate = Carbon::create($season, 1, 2)->next(Carbon::THURSDAY);

        $games = Game::where('date', '>=', $startDate->toDateString())
            ->whereYear('date', $season)
            ->orderBy('date')
            ->get();

        foreach ($games as $index => $game) {
            $game->update([
                'match_number' => $index + 1,
                'season_year' => $season
            ]);
        }

        $this->info("Se actualizaron {$games->count()} partidos para la temporada {$season}");
        return 0;
    }
}
