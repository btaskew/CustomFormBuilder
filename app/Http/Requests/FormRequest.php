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
            'open_date' => 'date|nullable',
            'close_date' => 'date|after_or_equal:open_date|nullable',
            'admin_email' => ['string', 'nullable', new EmailList()],
            'active' => 'boolean|required',
            'success_text' => 'string|nullable',
            'response_email' => 'string|nullable',
            'response_email_field' => 'integer|nullable',
            'folder_id' => 'integer|required',
        ];
    }
}
