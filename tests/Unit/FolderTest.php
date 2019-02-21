<?php

namespace Tests\Unit;

use App\Folder;
use App\Form;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FolderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_folder_has_many_forms()
    {
        $folder = create(Folder::class);
        $form = create(Form::class, ['folder_id' => $folder->id]);

        $this->assertTrue($folder->forms->first()->is($form));
    }

    /** @test */
    public function a_folder_can_be_fetched_with_its_number_of_forms()
    {
        $folder = create(Folder::class);
        create(Form::class, ['folder_id' => $folder->id], 2);

        $this->assertEquals(2, $folder->withFormCount()->formCount);
    }
}
