<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->integer('match_number')->nullable()->after('date');
            $table->integer('season_year')->default(2025)->after('match_number');
            $table->json('team_a')->nullable()->after('notes'); // Equipo A
            $table->json('team_b')->nullable()->after('team_a'); // Equipo B
            $table->boolean('teams_generated')->default(false)->after('team_b');
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['match_number', 'season_year', 'team_a', 'team_b', 'teams_generated']);
        });
    }
};
