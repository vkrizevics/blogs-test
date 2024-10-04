<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|max:255',
            'content' => 'required|max:65000',
        ];
    }

    public function after()
    {
        return [
            function (Validator $validator) {
                $this->noTagsAllowed('title', $validator);
            },
            function (Validator $validator) {
                $this->noTagsAllowed('content', $validator);
            }
        ];
    }

    protected function noTagsAllowed(string $field_name, Validator $validator)
    {
        $field = $this->input($field_name);
        if (isset($field) && strip_tags($field) !== $field) {
            $validator->errors()->add(
                $field_name,
                'No tags allowed'
            );
        }
    }
}
