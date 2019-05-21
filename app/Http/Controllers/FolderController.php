<?php

namespace App\Http\Controllers;

use App\Folder;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class FolderController extends Controller
{
    /**
     * Display a listing of the folders.
     *
     * @return Response
     */
    public function index()
    {
        $folders = Folder::paginate(25);

        foreach ($folders->items() as $folder) {
            $folder->withFormCount();
        }

        return view('folders.index', compact('folders'));
    }

    /**
     * Show the form for creating a new folder.
     *
     * @return View
     */
    public function create()
    {
        return view('folders.create');
    }

    /**
     * Store a newly created folder in storage.
     *
     * @param Request $request
     * @return Folder
     */
    public function store(Request $request)
    {
        $name = $request->validate(['name' => 'string|required'])['name'];

        return Folder::create(compact('name'));
    }

    /**
     * Update the specified folder in storage.
     *
     * @param Request $request
     * @param Folder  $folder
     * @return JsonResponse
     */
    public function update(Request $request, Folder $folder)
    {
        $name = $request->validate(['name' => 'string|required'])['name'];

        $folder->update(compact('name'));

        return response()->json(['success' => 'Folder updated']);
    }

    /**
     * Remove the specified folder from storage.
     *
     * @param Folder $folder
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Folder $folder)
    {
        if ($folder->forms()->count() > 0) {
            return response()->json(['error' => 'Folder still contains forms'], 403);
        }

        $folder->delete();

        return response()->json(['success' => 'Folder deleted']);
    }
}
