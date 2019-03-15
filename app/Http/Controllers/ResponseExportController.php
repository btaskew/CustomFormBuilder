<?php

namespace App\Http\Controllers;

use App\Contracts\ResponseFormatter;
use App\Exports\ResponsesExport;
use App\Form;
use Maatwebsite\Excel\Facades\Excel;

class ResponseExportController extends Controller
{
    /**
     * @var ResponseFormatter
     */
    private $responseFormatter;

    /**
     * @param ResponseFormatter $responseFormatter
     */
    public function __construct(ResponseFormatter $responseFormatter)
    {
        $this->responseFormatter = $responseFormatter;
    }

    /**
     * @param Form $form
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Form $form)
    {
        $this->authorize('view', $form);

        return Excel::download(new ResponsesExport($form, $this->responseFormatter), 'responses.xlsx');
    }
}