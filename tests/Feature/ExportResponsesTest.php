<?php

namespace Tests\Feature;

use App\Form;
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

        $this->get('/forms/' . $form->id . '/responses/export')->assertStatus(200);

        Excel::assertDownloaded('responses.xlsx');
    }

    /** @test */
    public function a_user_cant_export_the_results_of_another_users_form()
    {
        $this->withExceptionHandling();

        $form = create(Form::class, ['user_id' => 9999]);

        $this->login()->get('/forms/' . $form->id . '/responses/export')->assertStatus(403);
    }

    /** @test */
    public function a_guest_cant_export_a_forms_result()
    {
        $this->withExceptionHandling();

        $form = create(Form::class);

        $this->get('/forms/' . $form->id . '/responses/export')->assertRedirect('login');
    }
}