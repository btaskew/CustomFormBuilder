<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('form_id')->nullable();
            $table->string('title');
            $table->enum('type', [
                'text',
                'email',
                'password',
                'textarea',
                'number',
                'file',
                'url',
                'tel',
                'date',
                'datetime-local',
                'time',
                'checkbox',
                'radio',
                'dropdown'
            ]);
            $table->string('help_text')->nullable();
            $table->boolean('required')->default(false);
            $table->boolean('admin_only')->default(false);
            $table->boolean('in_question_bank')->default(false);
            $table->unsignedInteger('order')->nullable();
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
        Schema::dropIfExists('questions');
    }
}
