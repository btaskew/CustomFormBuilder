<?php

use App\Folder;
use App\Form;
use App\FormUser;
use App\Question;
use App\SelectOption;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $folders = factory(Folder::class, 3)->create();

        $forms = factory(Form::class, 2)->create([
            'user_id' => 1,
            'folder_id' => $folders[0]->id
        ]);

        factory(Form::class)->create([
            'user_id' => 1,
            'folder_id' => $folders[1]->id
        ]);

        $forms->each(function (Form $form) {
            factory(Question::class, 4)->create([
                'form_id' => $form->id
            ]);
        });

        // Question bank
        factory(Question::class)->create([
            'form_id' => null,
            'order' => 0,
            'in_question_bank' => true,
            'type' => 'text',
            'title' => 'Name',
            'help_text' => 'Please enter your full name'
        ]);
        factory(Question::class)->create([
            'form_id' => null,
            'order' => 0,
            'in_question_bank' => true,
            'type' => 'email',
            'title' => 'Email address',
            'help_text' => null
        ]);
        factory(Question::class)->create([
            'form_id' => null,
            'order' => 0,
            'in_question_bank' => true,
            'type' => 'date',
            'title' => 'Date of birth',
            'help_text' => null
        ]);
        $genderQuestion = factory(Question::class)->create([
            'form_id' => null,
            'order' => 0,
            'in_question_bank' => true,
            'type' => 'radio',
            'title' => 'Gender',
            'help_text' => null
        ]);
        factory(SelectOption::class)->create([
            'question_id' => $genderQuestion->id,
            'value' => 'male',
            'display_value' => 'Male'
        ]);
        factory(SelectOption::class)->create([
            'question_id' => $genderQuestion->id,
            'value' => 'female',
            'display_value' => 'Female'
        ]);
        factory(SelectOption::class)->create([
            'question_id' => $genderQuestion->id,
            'value' => 'other',
            'display_value' => 'Identify as other'
        ]);
        factory(SelectOption::class)->create([
            'question_id' => $genderQuestion->id,
            'value' => 'undisclosed',
            'display_value' => 'Prefer not to say'
        ]);

        factory(FormUser::class)->create([
            'user_id' => 2,
            'form_id' => $forms[0]->id,
            'access' => 'view'
        ]);
    }
}
