<?php

namespace App\Http\Controllers;

use App\Contracts\ResponseFormatter;
use App\Exports\ResponsesExport;
use App\Form;
use Illuminate\Auth\Access\AuthorizationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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
     * @return BinaryFileResponse
     * @throws AuthorizationException
     */
    public function index(Form $form)
    {
        $this->authorize('view', $form);

        return Excel::download(new ResponsesExport($form, $this->responseFormatter), 'responses.xlsx');
    }
}
