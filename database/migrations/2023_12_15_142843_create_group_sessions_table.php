<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupSessionsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('group_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained('groups')->nullOnDelete()->cascadeOnDelete();
            $table->foreignId('session_id')->constrained('sessions')->nullOnDelete()->cascadeOnDelete();
            // Ajoutez d'autres colonnes si nécessaire
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('group_sessions');
    }
}
