<?php

namespace Tests\Feature\Folder;

use App\Folder;
use App\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageFolderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_view_the_create_folder_page()
    {
        $this->get('/admin/folders/create')->assertRedirect('login');
    }

    /** @test */
    public function a_standard_user_cant_view_the_create_folder_page()
    {
        $this->login()->get('/admin/folders/create')->assertStatus(403);
    }

    /** @test */
    public function an_admin_user_can_view_the_create_folder_page()
    {
        $this->loginAdmin()->get('/admin/folders/create')->assertSee('create-folder-form');
    }

    /** @test */
    public function a_guest_cant_create_a_folder()
    {
        $this->post('/admin/folders', ['name' => 'New folder'])->assertRedirect('login');
    }

    /** @test */
    public function an_admin_user_can_create_a_new_folder()
    {
        $this->loginAdmin()
            ->post('/admin/folders', ['name' => 'New folder'])
            ->assertStatus(201);

        $this->assertDatabaseHas('folders', ['name' => 'New folder']);
    }

    /** @test */
    public function a_standard_user_cant_create_a_folder()
    {
        $this->login()
            ->post('/admin/folders', ['name' => 'New folder'])
            ->assertStatus(403);
    }

    /** @test */
    public function a_name_is_required_when_creating_a_folder()
    {
        $this->loginAdmin()
            ->json('post', '/admin/folders', [])
            ->assertStatus(422)
            ->assertSee('name');
    }


    /** @test */
    public function a_guest_cant_update_a_folder()
    {
        $folder = create(Folder::class, ['name' => 'Old name']);

        $this->patch('/admin/folders/' . $folder->id, ['name' => 'New folder'])->assertRedirect('login');

        $this->assertEquals('Old name', $folder->fresh()->name);
    }

    /** @test */
    public function an_admin_user_can_update_a_folder()
    {
        $folder = create(Folder::class, ['name' => 'Old name']);

        $this->loginAdmin()
            ->patch('/admin/folders/' . $folder->id, ['name' => 'New name'])
            ->assertStatus(200);

        $this->assertEquals('New name', $folder->fresh()->name);
    }

    /** @test */
    public function a_standard_user_cant_update_a_folder()
    {
        $folder = create(Folder::class, ['name' => 'Old name']);

        $this->login()
            ->patch('/admin/folders/' . $folder->id, ['name' => 'New name'])
            ->assertStatus(403);

        $this->assertEquals('Old name', $folder->fresh()->name);
    }

    /** @test */
    public function a_name_is_required_when_updating_a_folder()
    {
        $folder = create(Folder::class, ['name' => 'Old name']);

        $this->loginAdmin()
            ->json('patch', '/admin/folders/' . $folder->id, [])
            ->assertStatus(422)
            ->assertSee('name');

        $this->assertEquals('Old name', $folder->fresh()->name);
    }


    /** @test */
    public function a_guest_cant_delete_a_folder()
    {
        $folder = create(Folder::class, ['name' => 'Old name']);

        $this->delete('/admin/folders/' . $folder->id)->assertRedirect('login');

        $this->assertDatabaseHas('folders', ['id' => $folder->id]);
    }

    /** @test */
    public function an_admin_user_can_delete_a_folder()
    {
        $folder = create(Folder::class);

        $this->loginAdmin()
            ->delete('/admin/folders/' . $folder->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('folders', ['id' => $folder->id]);
    }

    /** @test */
    public function a_folder_cant_be_deleted_if_it_has_forms_in()
    {
        $folder = create(Folder::class);
        create(Form::class, ['folder_id' => $folder->id]);

        $this->loginAdmin()
            ->delete('/admin/folders/' . $folder->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('folders', ['id' => $folder->id]);
    }

    /** @test */
    public function a_standard_user_cant_delete_a_folder()
    {
        $folder = create(Folder::class);

        $this->login()
            ->delete('/admin/folders/' . $folder->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('folders', ['id' => $folder->id]);
    }
}
