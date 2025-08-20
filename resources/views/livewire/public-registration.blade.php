<div class="min-h-screen bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 py-4 px-4 sm:py-8">
    <div class="container mx-auto max-w-4xl">
        <!-- Header con información del partido -->
        <div class="text-center mb-6 sm:mb-8">
            @if($game && $game->match_number)
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-full text-sm font-semibold mb-4 shadow-lg animate-pulse">
                    <span class="w-2 h-2 bg-white rounded-full"></span>
                    Fecha {{ $game->match_number }} • Temporada {{ $game->season_year }}
                </div>
            @endif

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2">
                <span class="text-3xl sm:text-4xl">⚽</span>
                Fútbol Jueves
            </h1>

            <p class="text-lg sm:text-xl text-blue-100 font-medium">
                {{ $game ? \Carbon\Carbon::parse($game->date)->format('d/m/Y') : 'Próximo partido' }}
            </p>
        </div>

        <!-- Mensaje de estado del sistema -->
        @if($systemStatus['message'])
            <div class="mb-6">
                <div class="p-4 rounded-xl border-l-4 shadow-lg
                    @switch($systemStatus['status'])
                        @case('success') bg-green-50 border-green-500 text-green-800 @break
                        @case('error') bg-red-50 border-red-500 text-red-800 @break
                        @case('warning') bg-yellow-50 border-yellow-500 text-yellow-800 @break
                        @default bg-blue-50 border-blue-500 text-blue-800
                    @endswitch
                ">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            @switch($systemStatus['status'])
                                @case('success') <span class="text-xl">✅</span> @break
                                @case('error') <span class="text-xl">❌</span> @break
                                @case('warning') <span class="text-xl">⚠️</span> @break
                                @default <span class="text-xl">ℹ️</span>
                            @endswitch
                        </div>
                        <p class="font-medium">{{ $systemStatus['message'] }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Columna principal (formulario y lista) -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Reglas del juego -->
                @if($systemStatus['status'] != 'post_game')
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20 shadow-xl">
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                            <span class="text-2xl">📋</span>
                            Reglas de inscripción
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 text-blue-100">
                                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        10
                                    </div>
                                    <span class="font-medium">Titulares</span>
                                </div>

                                <div class="flex items-center gap-3 text-blue-100">
                                    <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                        2
                                    </div>
                                    <span class="font-medium">Suplentes</span>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center gap-3 text-blue-100">
                                    <span class="text-xl">🕐</span>
                                    <span class="font-medium">Martes - Jueves 9:00 PM</span>
                                </div>

                                <div class="flex items-center gap-3 text-blue-100">
                                    <span class="text-xl">🤝</span>
                                    <span class="font-medium">Compromiso de asistencia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Formulario de registro -->
                @if ($registrationOpen && $total < 12)
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20 shadow-xl">
                        <form wire:submit.prevent="register" class="space-y-4">
                            <div class="relative">
                                <label class="block text-white font-semibold mb-2">
                                    Confirma tu asistencia
                                </label>

                                <div class="relative">
                                    <input
                                        type="text"
                                        wire:model.live.debounce.300ms="name"
                                        placeholder="Escribe tu nombre completo..."
                                        autocomplete="off"
                                        maxlength="100"
                                        required
                                        class="w-full px-4 py-4 text-lg bg-white text-gray-800 border-2 border-transparent rounded-xl focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-400/20 transition-all duration-200 placeholder-gray-400"
                                    >

                                    <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                        <span class="text-2xl">👤</span>
                                    </div>
                                </div>

                                @if($suggestions && $name)
                                    <div class="absolute z-50 w-full mt-1 bg-white rounded-xl shadow-xl border border-gray-200 max-h-60 overflow-y-auto">
                                        @foreach($suggestions as $suggest)
                                            <button
                                                type="button"
                                                wire:click="selectSuggestion('{{ $suggest }}')"
                                                class="w-full text-left px-4 py-3 hover:bg-blue-50 transition-colors duration-150 border-b border-gray-100 last:border-b-0 text-gray-800"
                                            >
                                                {{ $suggest }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <button
                                type="submit"
                                {{ strlen($name) < 3 ? 'disabled' : '' }}
                                class="w-full py-4 px-6 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 disabled:from-gray-400 disabled:to-gray-500 text-white font-bold rounded-xl text-lg transition-all duration-200 transform hover:scale-[1.02] hover:shadow-lg disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-3"
                            >
                                <span class="text-xl">⚽</span>
                                Confirmar asistencia
                            </button>
                        </form>
                    </div>
                @endif

                <!-- Mensajes de respuesta -->
                @if ($message)
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-lg">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">✅</span>
                            <p class="text-green-800 font-medium">{{ $message }}</p>
                        </div>
                    </div>
                @endif

                @if ($error)
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-lg">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">❌</span>
                            <p class="text-red-800 font-medium">{{ $error }}</p>
                        </div>
                    </div>
                @endif

                <!-- Lista de jugadores -->
                @if($showGameInfo)
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20 shadow-xl">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                                @if($systemStatus['status'] == 'post_game')
                                    <span class="text-2xl">📊</span> Jugadores que participaron
                                @elseif($systemStatus['status'] == 'game_day_closed')
                                    <span class="text-2xl">⚽</span> Lista final para el partido
                                @else
                                    <span class="text-2xl">👥</span> Jugadores confirmados
                                @endif
                            </h2>

                            <div class="flex items-center gap-2 bg-white/20 rounded-full px-4 py-2">
                                <span class="text-white font-bold">{{ $total }}/12</span>
                                <div class="w-20 bg-white/30 rounded-full h-2">
                                    <div class="h-2 bg-gradient-to-r from-green-400 to-green-500 rounded-full transition-all duration-500" style="width: {{ ($total/12)*100 }}%"></div>
                                </div>
                            </div>
                        </div>

                        @if(count($attendees) > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($attendees as $i => $nombre)
                                    <div class="flex items-center gap-3 p-4 rounded-xl border-l-4 transition-all duration-200 hover:transform hover:scale-[1.02]
                                        {{ $i < 10 ? 'bg-green-50 border-green-500 hover:bg-green-100' : 'bg-orange-50 border-orange-500 hover:bg-orange-100' }}
                                    ">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center font-bold text-white text-sm
                                            {{ $i < 10 ? 'bg-green-500' : 'bg-orange-500' }}
                                        ">
                                            {{ $i + 1 }}
                                        </div>

                                        <div class="flex-1">
                                            <span class="font-medium {{ $i < 10 ? 'text-green-800' : 'text-orange-800' }}">
                                                {{ $nombre }}
                                            </span>
                                        </div>

                                        <div class="flex-shrink-0">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                                {{ $i < 10 ? 'bg-green-500 text-white' : 'bg-orange-500 text-white' }}
                                            ">
                                                {{ $i < 10 ? '⚽ Titular' : '🔄 Suplente' }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <div class="text-6xl mb-4">🤔</div>
                                <p class="text-blue-100 text-lg font-medium">Aún no hay jugadores inscritos</p>
                                <p class="text-blue-200 text-sm">¡Sé el primero en confirmar tu asistencia!</p>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Columna lateral (equipos) -->
            <div class="lg:col-span-1">
                @if($game && $game->teams_generated && count($attendees) >= 10)
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20 shadow-xl">
                        <div class="flex flex-col sm:flex-row lg:flex-col xl:flex-row items-start sm:items-center lg:items-start xl:items-center justify-between gap-4 mb-6">
                            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                                @if($systemStatus['status'] == 'post_game')
                                    <span class="text-2xl">📊</span> Equipos
                                @else
                                    <span class="text-2xl">⚽</span> Equipos
                                @endif
                            </h2>

                            @if($registrationOpen && count($attendees) >= 10)
                                <button
                                    wire:click="regenerateTeams"
                                    class="px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold rounded-lg text-sm transition-all duration-200 transform hover:scale-105 flex items-center gap-2"
                                >
                                    <span class="text-sm">🔄</span> Regenerar
                                </button>
                            @endif
                        </div>

                        <div class="space-y-6">
                            <!-- Equipo A -->
                            <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4">
                                <h3 class="text-lg font-bold text-red-800 mb-3 flex items-center gap-2">
                                    <span class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-xs">A</span>
                                    </span>
                                    Equipo A
                                </h3>
                                <div class="space-y-2">
                                    @foreach($game->team_a ?? [] as $player)
                                        <div class="bg-white/80 px-3 py-2 rounded-lg text-red-800 font-medium text-center">
                                            {{ $player }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Equipo B -->
                            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4">
                                <h3 class="text-lg font-bold text-blue-800 mb-3 flex items-center gap-2">
                                    <span class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-bold text-xs">B</span>
                                    </span>
                                    Equipo B
                                </h3>
                                <div class="space-y-2">
                                    @foreach($game->team_b ?? [] as $player)
                                        <div class="bg-white/80 px-3 py-2 rounded-lg text-blue-800 font-medium text-center">
                                            {{ $player }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Suplentes -->
                            @if(count($attendees) > 10)
                                <div class="bg-orange-50 border-2 border-orange-200 rounded-xl p-4">
                                    <h4 class="text-lg font-bold text-orange-800 mb-3 flex items-center gap-2">
                                        <span class="text-xl">🔄</span> Suplentes
                                    </h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(array_slice($attendees, 10) as $suplente)
                                            <span class="bg-orange-200 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                                                {{ $suplente }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($registrationOpen && count($attendees) >= 10 && !$game->teams_generated)
                    <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-6 border border-white/20 shadow-xl">
                        <div class="text-center">
                            <div class="text-4xl mb-4">⚽</div>
                            <h3 class="text-lg font-bold text-white mb-4">¡Listos para formar equipos!</h3>
                            <button
                                wire:click="generateTeams"
                                class="w-full py-3 px-6 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold rounded-xl transition-all duration-200 transform hover:scale-[1.02] hover:shadow-lg flex items-center justify-center gap-3"
                            >
                                <span class="text-xl">🎲</span>
                                Generar equipos aleatorios
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Footer con última actualización -->
        @if($total > 0 && $showGameInfo)
            <div class="mt-8 text-center">
                <div class="inline-flex items-center gap-2 text-blue-200 text-sm">
                    <span class="text-base">🕒</span>
                    Actualizado: {{ now()->format('d/m/Y H:i') }}
                </div>
            </div>
        @endif

        <!-- Información cuando no hay contenido -->
        @if(!$showGameInfo && !$registrationOpen)
            <div class="text-center py-12">
                <div class="text-6xl mb-6">📅</div>
                <div class="bg-white/10 backdrop-blur-lg rounded-2xl p-8 border border-white/20 shadow-xl max-w-md mx-auto">
                    <h3 class="text-xl font-bold text-white mb-3">Próxima inscripción</h3>
                    @php
                        $now = \Carbon\Carbon::now();
                        $nextTuesday = $now->next(\Carbon\Carbon::TUESDAY);
                    @endphp
                    <p class="text-blue-100">
                        Se abrirá el <strong>{{ $nextTuesday->format('d/m/Y') }}</strong>
                    </p>
                    <p class="text-blue-200 text-sm mt-2">
                        (Martes)
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
