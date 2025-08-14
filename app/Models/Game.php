<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'time',
        'location',
        'notes',
        'match_number',
        'season_year',
        'team_a',
        'team_b',
        'teams_generated'
    ];

    protected $casts = [
        'date' => 'date',
        'team_a' => 'array',
        'team_b' => 'array',
        'teams_generated' => 'boolean'
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'attendances');
    }

    public function getFormattedDateAttribute()
    {
        return $this->date->format('d/m/Y');
    }

    public function getAvailableSlotsAttribute()
    {
        return 12 - $this->attendances()->count();
    }

    public function getMatchTitleAttribute()
    {
        return "Fecha {$this->match_number} • Temporada {$this->season_year}";
    }

    // Generar equipos aleatorios
    public function generateRandomTeams()
    {
        $players = $this->attendances()
            ->orderBy('created_at')
            ->with('player')
            ->get()
            ->map(function($attendance) {
                return $attendance->name;
            })
            ->toArray();

        if (count($players) < 10) {
            return false; // Necesita al menos 10 jugadores
        }

        // Tomar solo los primeros 10 (titulares)
        $titulares = array_slice($players, 0, 10);

        // Mezclar aleatoriamente (crear una copia para evitar problemas) usando generador criptográfico
        $titularesMezclados = $titulares;
        // Cryptographically secure shuffle
        for ($i = count($titularesMezclados) - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            [$titularesMezclados[$i], $titularesMezclados[$j]] = [$titularesMezclados[$j], $titularesMezclados[$i]];
        }

        // Dividir en dos equipos de 5
        $teamA = array_slice($titularesMezclados, 0, 5);
        $teamB = array_slice($titularesMezclados, 5, 5);

        $this->update([
            'team_a' => $teamA,
            'team_b' => $teamB,
            'teams_generated' => true
        ]);

        return true;
    }
}
