<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVisibilityRequirementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visibility_requirements', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('required_question_id');
            $table->string('required_value');
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
        Schema::dropIfExists('visibility_requirements');
    }
}
