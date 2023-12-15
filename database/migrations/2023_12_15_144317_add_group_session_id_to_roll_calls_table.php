<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGroupSessionIdToRollCallsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('roll_calls', function (Blueprint $table) {
            $table->foreignId('group_session_id')->constrained('group_sessions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('roll_calls', function (Blueprint $table) {
            $table->dropForeign(['group_session_id']);
            $table->dropColumn('group_session_id');
        });
    }
}
