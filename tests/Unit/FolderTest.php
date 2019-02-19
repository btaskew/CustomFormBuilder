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
}
