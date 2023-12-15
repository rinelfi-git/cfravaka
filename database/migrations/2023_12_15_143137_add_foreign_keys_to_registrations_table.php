<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRegistrationsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('registrations', function (Blueprint $table) {
            $table->foreignId('session_id')->constrained('sessions');
            $table->foreignId('student_id')->constrained('students');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropForeign(['session_id']);
            $table->dropForeign(['student_id']);
            $table->dropColumn('session_id');
            $table->dropColumn('student_id');
        });
    }
}
