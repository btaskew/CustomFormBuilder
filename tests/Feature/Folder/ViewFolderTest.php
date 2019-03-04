<?php

namespace Tests\Feature\Folder;

use App\Folder;
use App\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewFolderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_view_all_folder()
    {
        $this->get('/folders')->assertRedirect('login');
    }

    /** @test */
    public function an_admin_user_can_see_a_list_of_all_folders_with_their_form_counts()
    {
        $folder = create(Folder::class);
        create(Form::class, ['folder_id' => $folder->id], 2);

        $this->loginAdmin()
            ->get('/folders')
            ->assertStatus(200)
            ->assertSee($folder->name)
            ->assertSee(2);
    }

    /** @test */
    public function a_standard_user_cant_see_a_list_of_folders()
    {
        $this->login()
            ->get('/folders')
            ->assertRedirect('login');
    }
}
