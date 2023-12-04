<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormationSubCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formation_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->string('modality');
            $table->string('formula');
            $table->string('convenience');
            $table->integer('price');
            $table->integer('time_range');
            $table->boolean('is_monthly');
            $table->boolean('is_editable');
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
        Schema::dropIfExists('formation_sub_categories');
    }
}
