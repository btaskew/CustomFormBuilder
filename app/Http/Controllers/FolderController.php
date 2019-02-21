<?php

namespace App\Http\Controllers;

use App\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    /**
     * Display a listing of the folders.
     *
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('folders.create');
    }

    /**
     * Store a newly created folder in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Folder
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'string|required']);

        return Folder::create(['name' => $request->input('name')]);
    }

    /**
     * Display the specified folder.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified folder.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified folder in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Folder                    $folder
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Folder $folder)
    {
        $request->validate(['name' => 'string|required']);

        $folder->update(['name' => $request->input('name')]);

        return response()->json(['success' => 'Folder updated']);
    }

    /**
     * Remove the specified folder from storage.
     *
     * @param Folder $folder
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
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
