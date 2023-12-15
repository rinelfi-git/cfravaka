<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeySessionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('session_groups', function (Blueprint $table) {
            $table->foreignId('group_id')->constrained('groups')->nullOnDelete();
            $table->foreignId('session_id')->constrained('sessions')->nullOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('session_groups', function (Blueprint $table) {
            $table->dropForeign('group_id');
            $table->dropForeign('session_id');
            $table->dropForeign('teacher_id');
        });
    }
}
