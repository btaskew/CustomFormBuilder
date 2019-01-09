<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
        $rules = [
            'title' => 'string|required',
            'type' => 'string|required|in:text,email,password,hidden,textarea,number,file,url,tel,date,datetime-local,time,checkbox,radio,dropdown',
            'help_text' => 'string',
            'required' => 'boolean',
            'admin_only' => 'boolean',
            'order' => 'numeric|required',
            'options' => 'array'
        ];

        if ($this->has('options')) {
            foreach ($this->get('options') as $key => $option) {
                $rules['options.' . $key . '.value'] = 'required|string';
                $rules['options.' . $key . '.display_value'] = 'required|string';
                $rules['options.' . $key . '.id'] = 'nullable';
            }
        }

        return $rules;
    }
}