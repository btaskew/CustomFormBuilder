<?php

namespace App\Http\Requests;

use App\Rules\EmailList;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;

class FormRequest extends LaravelFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'string|required|max:255',
            'description' => 'string|nullable',
            'open_date' => 'date',
            'close_date' => 'date',
            'admin_email' => ['string', new EmailList()],
            'active' => 'boolean',
            'success_text' => 'string|nullable',
            'response_email' => 'string|nullable',
        ];
    }
}