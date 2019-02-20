<?php

namespace Tests\Feature;

use App\Form;
use App\FormUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ExportResponsesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_export_the_results_of_their_form()
    {
        Excel::fake();

        $form = $this->loginUserWithForm();

        $this->get(formPath($form) . '/responses/export')->assertStatus(200);

        Excel::assertDownloaded('responses.xlsx');
    }

    /** @test */
    public function a_user_cant_export_the_results_of_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 9999]);

        $this->login()->get(formPath($form) . '/responses/export')->assertStatus(403);
    }

    /** @test */
    public function a_user_can_export_the_results_of_a_form_they_have_view_access_to()
    {
        Excel::fake();

        $this->login();
        $form = create(Form::class, ['user_id' => 999]);
        create(FormUser::class, [
            'user_id' => auth()->user()->id,
            'form_id' => $form->id,
            'access' => 'view'
        ]);

        $this->get(formPath($form) . '/responses/export')->assertStatus(200);
    }

    /** @test */
    public function a_guest_cant_export_a_forms_result()
    {
        $this->withExceptionHandling();

        $form = create(Form::class);

        $this->get(formPath($form) . '/responses/export')->assertRedirect('login');
    }
}