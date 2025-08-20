<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Game;
use App\Models\Player;
use App\Models\Attendance;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('components.layouts.app')]
#[Title('Fútbol Jueves - Registro')]
class PublicRegistration extends Component
{
    public $name = '';
    public $suggestions = [];
    public $message = '';
    public $error = '';
    public $game;
    public $attendees = [];

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'showMessage' => 'showMessage'
    ];

    public function mount()
    {
        try {
            $this->game = $this->getNextAvailableGame();
            $this->fetchAttendees();
            $this->calculateMatchNumber();
        } catch (\Exception $e) {
            \Log::error('Error in PublicRegistration mount: ' . $e->getMessage());
            $this->error = 'Error al cargar el sistema. Por favor recarga la página.';
        }
    }

    private function getNextAvailableGame()
    {
        $now = Carbon::now();

        // Si es jueves antes de medianoche (viernes 12:00 AM), mostrar el partido de hoy
        if ($now->dayOfWeekIso == 4) {
            $gameDate = $now->toDateString();
        }
        // Si es viernes antes de las 12:00 AM, mostrar el partido del jueves anterior
        elseif ($now->dayOfWeekIso == 5 && $now->hour < 24) {
            $yesterday = $now->subDay();
            $gameDate = $yesterday->toDateString();
        }
        else {
            $nextThursday = $now->next(Carbon::THURSDAY);
            $gameDate = $nextThursday->toDateString();
        }

        $game = Game::where('date', $gameDate)->first();

        // Si no existe el juego, buscar el próximo disponible
        if (!$game) {
            $game = Game::where('date', '>', $now->toDateString())
                ->orderBy('date')
                ->first();
        }

        return $game;
    }

    private function calculateMatchNumber()
    {
        if (!$this->game || $this->game->match_number) {
            return; // Ya tiene número asignado
        }

        try {
            $currentYear = Carbon::now()->year;
            $startDate = Carbon::create($currentYear, 1, 2)->next(Carbon::THURSDAY);

            // Contar partidos desde el inicio del año hasta este
            $matchNumber = Game::where('date', '>=', $startDate->toDateString())
                ->where('date', '<=', $this->game->date)
                ->whereYear('date', $currentYear)
                ->count();

            $this->game->update([
                'match_number' => $matchNumber,
                'season_year' => $currentYear
            ]);
        } catch (\Exception $e) {
            \Log::error('Error calculating match number: ' . $e->getMessage());
        }
    }

    public function updatedName($value)
    {
        try {
            if (strlen($value) >= 2) {
                $this->suggestions = Player::where('name', 'like', '%' . $value . '%')
                    ->limit(5)
                    ->pluck('name')
                    ->toArray();
            } else {
                $this->suggestions = [];
            }
        } catch (\Exception $e) {
            \Log::error('Error updating name suggestions: ' . $e->getMessage());
            $this->suggestions = [];
        }
    }

    public function selectSuggestion($name)
    {
        $this->name = $name;
        $this->suggestions = [];
    }

    public function register()
    {
        $this->reset(['message', 'error']);
        $name = trim($this->name);

        try {
            if (!$this->isRegistrationOpen()) {
                $this->error = "⛔ El registro está cerrado. Se habilita los martes a jueves antes de las 9:00 PM.";
                return;
            }

            if (strlen($name) < 3) {
                $this->error = '❌ El nombre debe tener al menos 3 caracteres.';
                return;
            }

            $exists = Attendance::where('game_id', $this->game->id)
                ->where('name', 'like', '%' . $name . '%')
                ->exists();

            if ($exists) {
                $this->error = '⚠️ Ya estás inscrito para este partido.';
                return;
            }

            $total = Attendance::where('game_id', $this->game->id)->count();
            if ($total >= 12) {
                $this->error = '⛔ El cupo ya está lleno (12/12 jugadores).';
                return;
            }

            $player = Player::firstOrCreate(['name' => $name]);

            Attendance::create([
                'game_id' => $this->game->id,
                'player_id' => $player->id,
                'name' => $name,
            ]);

            $position = $total + 1;
            $status = $position <= 10 ? 'titular' : 'suplente';

            $this->message = "✅ ¡Inscripción exitosa! Eres el #{$position} (" . ($status == 'titular' ? 'Titular' : 'Suplente') . ")";
            $this->reset(['name', 'suggestions']);
            $this->fetchAttendees();

            // Generar equipos automáticamente si hay 10+ jugadores
            if ($position >= 10 && !$this->game->teams_generated) {
                $this->generateTeams();
            }

            // Dispatch browser event for success
            $this->dispatch('registration-success', message: $this->message);

        } catch (\Exception $e) {
            \Log::error('Error in registration: ' . $e->getMessage());
            $this->error = '❌ Error al registrar. Por favor intenta nuevamente.';
        }
    }

    public function generateTeams()
    {
        try {
            if ($this->game->generateRandomTeams()) {
                $this->game->refresh(); // Recargar datos
                session()->flash('teams_generated', true);
                $this->dispatch('teams-generated');
            }
        } catch (\Exception $e) {
            \Log::error('Error generating teams: ' . $e->getMessage());
            $this->error = '❌ Error al generar equipos.';
        }
    }

    public function regenerateTeams()
    {
        try {
            // Solo permitir regenerar si hay al menos 10 jugadores
            if (count($this->attendees) >= 10) {
                $this->game->update(['teams_generated' => false]);
                $this->generateTeams();
                $this->message = '🔄 ¡Equipos regenerados!';
            }
        } catch (\Exception $e) {
            \Log::error('Error regenerating teams: ' . $e->getMessage());
            $this->error = '❌ Error al regenerar equipos.';
        }
    }

    public function fetchAttendees()
    {
        try {
            if (!$this->game) {
                $this->attendees = [];
                return;
            }

            $this->attendees = Attendance::where('game_id', $this->game->id)
                ->orderBy('created_at')
                ->pluck('name')
                ->toArray();
        } catch (\Exception $e) {
            \Log::error('Error fetching attendees: ' . $e->getMessage());
            $this->attendees = [];
        }
    }

    public function isRegistrationOpen()
    {
        if (!$this->game) return false;

        try {
            $now = Carbon::now();
            $dayOfWeek = $now->dayOfWeekIso;
            $hour = $now->hour;

            // Registro cerrado después de las 9 PM del jueves (21:00)
            if ($dayOfWeek == 4 && $hour >= 21) {
                return false;
            }

            // El viernes ya no se puede registrar para el partido del jueves anterior
            if ($dayOfWeek == 5) {
                return false;
            }

            $isValidDay = in_array($dayOfWeek, [2, 3, 4]);
            $isValidTime = ($dayOfWeek < 4) || ($dayOfWeek == 4 && $hour < 21);

            $gameDate = Carbon::parse($this->game->date);
            $isNotPast = $gameDate->greaterThanOrEqualTo($now->toDateString());

            return $isValidDay && $isValidTime && $isNotPast;
        } catch (\Exception $e) {
            \Log::error('Error checking registration status: ' . $e->getMessage());
            return false;
        }
    }

    public function shouldShowGameInfo()
    {
        if (!$this->game) return false;

        try {
            $now = Carbon::now();
            $gameDate = Carbon::parse($this->game->date);

            // Si es el día del partido (jueves) después de las 9:00 PM, mostrar info
            if ($now->dayOfWeekIso == 4 && $now->hour >= 21 && $now->toDateString() == $gameDate->toDateString()) {
                return true;
            }

            // Si es viernes después del juego del jueves
            if ($now->dayOfWeekIso == 5) {
                $yesterday = $now->copy()->subDay();
                if ($yesterday->toDateString() == $gameDate->toDateString()) {
                    return true;
                }
            }

            // Si es sábado antes de medianoche y el juego fue el jueves anterior
            if ($now->dayOfWeekIso == 6 && $now->hour < 24) {
                $thursday = $now->copy()->subDays(2); // Jueves anterior
                if ($thursday->toDateString() == $gameDate->toDateString()) {
                    return true;
                }
            }

            // Si el registro está abierto, también mostrar
            if ($this->isRegistrationOpen()) {
                return true;
            }

            return false;
        } catch (\Exception $e) {
            \Log::error('Error checking game info display: ' . $e->getMessage());
            return false;
        }
    }

    public function getSystemStatus()
    {
        try {
            $now = Carbon::now();
            $dayOfWeek = $now->dayOfWeekIso;
            $hour = $now->hour;

            if (!$this->game) {
                return [
                    'status' => 'no_game',
                    'message' => 'No hay partidos programados'
                ];
            }

            $gameDate = Carbon::parse($this->game->date);
            $isToday = $now->toDateString() == $gameDate->toDateString();

            // Jueves después de las 9:00 PM (día del partido)
            if ($dayOfWeek == 4 && $hour >= 21 && $isToday) {
                return [
                    'status' => 'game_day_closed',
                    'message' => '⚽ ¡Hoy es el partido! El registro cerró a las 9:00 PM'
                ];
            }

            // Viernes (después del partido)
            if ($dayOfWeek == 5 && $now->copy()->subDay()->toDateString() == $gameDate->toDateString()) {
                return [
                    'status' => 'post_game',
                    'message' => '📊 Resumen del partido de ayer'
                ];
            }

            // Registro abierto
            if ($this->isRegistrationOpen()) {
                $total = count($this->attendees);
                if ($total >= 12) {
                    return [
                        'status' => 'full',
                        'message' => '🎉 ¡Cupo completo! Ya están listos los 12 jugadores'
                    ];
                }
                return [
                    'status' => 'open',
                    'message' => null
                ];
            }

            // Registro cerrado (fuera de horarios válidos)
            return [
                'status' => 'closed',
                'message' => '⛔ Registro cerrado. Se abre de martes a jueves antes de las 9:00 PM'
            ];
        } catch (\Exception $e) {
            \Log::error('Error getting system status: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error del sistema. Por favor recarga la página.'
            ];
        }
    }

    public function showMessage($message, $type = 'info')
    {
        if ($type === 'error') {
            $this->error = $message;
        } else {
            $this->message = $message;
        }
    }

    public function render()
    {
        try {
            $status = $this->getSystemStatus();
            $showGameInfo = $this->shouldShowGameInfo();

            return view('livewire.public-registration', [
                'game' => $this->game,
                'attendees' => $this->attendees,
                'registrationOpen' => $this->isRegistrationOpen(),
                'total' => count($this->attendees),
                'systemStatus' => $status,
                'showGameInfo' => $showGameInfo,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in render method: ' . $e->getMessage());
            return view('livewire.public-registration', [
                'game' => null,
                'attendees' => [],
                'registrationOpen' => false,
                'total' => 0,
                'systemStatus' => ['status' => 'error', 'message' => 'Error del sistema'],
                'showGameInfo' => false,
            ]);
        }
    }
}
