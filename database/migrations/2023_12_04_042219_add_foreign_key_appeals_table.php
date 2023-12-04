<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyAppealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appeals', function (Blueprint $table) {
            $table->foreignId('student_id')->constrained('students')->nullOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->nullOnDelete();
            $table->foreignId('session_group_id')->constrained('session_groups')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appeals', function (Blueprint $table) {
            $table->dropForeign('student_id');
            $table->dropForeign('teacher_id');
            $table->dropForeign('session_group_id');
        });
    }
}
