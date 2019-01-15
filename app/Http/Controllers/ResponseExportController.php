<?php

namespace App\Http\Controllers;

use App\Exports\ResponsesExport;
use App\Form;
use Maatwebsite\Excel\Facades\Excel;

class ResponseExportController extends Controller
{
    /**
     * @param Form $form
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Form $form)
    {
        $this->authorize('update', $form);

        return Excel::download(new ResponsesExport($form), 'responses.xlsx');
    }
}