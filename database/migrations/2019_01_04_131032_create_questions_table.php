<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedInteger('order');
            $table->unsignedInteger('form_id');
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
