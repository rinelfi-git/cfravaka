<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingTypeRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('training_type_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_type_id')->constrained('training_types');
            $table->foreignId('registration_id')->constrained('registrations');
            // Ajoutez d'autres colonnes si nécessaire
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('training_type_registrations');
    }
}
