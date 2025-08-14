<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->time('time')->default('19:00:00')->after('date');
            $table->string('location')->default('Cancha habitual')->after('time');
            $table->text('notes')->nullable()->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['time', 'location', 'notes']);
        });
    }
};
