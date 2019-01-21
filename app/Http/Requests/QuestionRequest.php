<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;

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
            'title' => 'string|required|max:255',
            'type' => 'string|required|in:text,email,password,hidden,textarea,number,file,url,tel,date,datetime-local,time,checkbox,radio,dropdown',
            'help_text' => 'string|nullable',
            'required' => 'boolean',
            'admin_only' => 'boolean',
            'options' => 'array|required_if:type,checkbox,radio,dropdown'
        ];

        if ($this->has('options')) {
            $options = collect($this->input('options'));

            foreach ($this->get('options') as $key => $option) {
                $rules['options.' . $key . '.id'] = 'nullable';
                $rules['options.' . $key . '.display_value'] = 'required|string';
                $rules['options.' . $key . '.value'] = [
                    'required',
                    'string',
                    $this->hasUniqueValues($options)
                ];
            }
        }

        return $rules;
    }

    /**
     * @return bool
     */
    public function hasVisibilityRequirement(): bool
    {
        return $this->has('required_if') && !is_null($this->input('required_if')['question']);
    }

    /**
     * @param Collection $options
     * @return \Closure
     */
    private function hasUniqueValues(Collection $options): \Closure
    {
        return function ($attribute, $value, $fail) use ($options) {
            if ($options->where('value', $value)->count() > 1) {
                $fail($attribute . ' must have a unique value');
            }
        };
    }
}