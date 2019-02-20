<?php

use App\Folder;
use App\Form;
use App\FormUser;
use App\Question;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $folders = factory(Folder::class, 3)->create();

        $forms = factory(Form::class, 3)->create([
            'user_id' => 1,
            'folder_id' => $folders[0]->id
        ]);

        $forms->each(function (Form $form) {
            factory(Question::class, 4)->create([
                'form_id' => $form->id
            ]);
        });

        // Question bank
        factory(Question::class, 3)->create([
            'form_id' => null,
            'order' => 0,
            'in_question_bank' => true
        ]);

        factory(FormUser::class)->create([
            'user_id' => 2,
            'form_id' => $forms[0]->id,
            'view'
        ]);
    }
}
