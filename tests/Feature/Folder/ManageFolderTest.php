<?php

namespace Tests\Feature\Folder;

use App\Folder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManageFolderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_guest_cant_create_a_folder()
    {
        $this->post('/folders', ['name' => 'New folder'])->assertRedirect('login');
    }

    /** @test */
    public function an_admin_user_can_create_a_new_folder()
    {
        $this->login('admin')
            ->post('/folders', ['name' => 'New folder'])
            ->assertStatus(201);

        $this->assertDatabaseHas('folders', ['name' => 'New folder']);
    }

    /** @test */
    public function a_standard_user_cant_create_a_folder()
    {
        $this->login('standard_user')
            ->post('/folders', ['name' => 'New folder'])
            ->assertStatus(403);
    }

    /** @test */
    public function a_name_is_required_when_creating_a_folder()
    {
        $this->login('admin')
            ->json('post', '/folders', [])
            ->assertStatus(422)
            ->assertSee('name');
    }


    /** @test */
    public function a_guest_cant_update_a_folder()
    {
        $folder = create(Folder::class, ['name' => 'Old name']);

        $this->patch('/folders/' . $folder->id, ['name' => 'New folder'])->assertRedirect('login');

        $this->assertEquals('Old name', $folder->fresh()->name);
    }

    /** @test */
    public function an_admin_user_can_update_a_folder()
    {
        $folder = create(Folder::class, ['name' => 'Old name']);

        $this->login('admin')
            ->patch('/folders/' . $folder->id, ['name' => 'New name'])
            ->assertStatus(200);

        $this->assertEquals('New name', $folder->fresh()->name);
    }

    /** @test */
    public function a_standard_user_cant_update_a_folder()
    {
        $folder = create(Folder::class, ['name' => 'Old name']);

        $this->login('standard_user')
            ->patch('/folders/' . $folder->id, ['name' => 'New name'])
            ->assertStatus(403);

        $this->assertEquals('Old name', $folder->fresh()->name);
    }

    /** @test */
    public function a_name_is_required_when_updating_a_folder()
    {
        $folder = create(Folder::class, ['name' => 'Old name']);

        $this->login('admin')
            ->json('patch', '/folders/' . $folder->id, [])
            ->assertStatus(422)
            ->assertSee('name');

        $this->assertEquals('Old name', $folder->fresh()->name);
    }


    /** @test */
    public function a_guest_cant_delete_a_folder()
    {
        $folder = create(Folder::class, ['name' => 'Old name']);

        $this->delete('/folders/' . $folder->id)->assertRedirect('login');

        $this->assertDatabaseHas('folders', ['id' => $folder->id]);
    }

    /** @test */
    public function an_admin_user_can_delete_a_folder()
    {
        $folder = create(Folder::class);

        $this->login('admin')
            ->delete('/folders/' . $folder->id)
            ->assertStatus(200);

        $this->assertDatabaseMissing('folders', ['id' => $folder->id]);
    }

    /** @test */
    public function a_standard_user_cant_delete_a_folder()
    {
        $folder = create(Folder::class);

        $this->login('standard_user')
            ->delete('/folders/' . $folder->id)
            ->assertStatus(403);

        $this->assertDatabaseHas('folders', ['id' => $folder->id]);
    }
}
