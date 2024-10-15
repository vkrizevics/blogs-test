<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\NoTags;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class UpdatePostRequest extends FormRequest
{
    use NoTags;
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
            'categories' => 'required'
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
            },
            function (Validator $validator) {
                $fields = $this->input('categories', []);
                foreach ($fields as $field) {
                    if (isset($field) && strip_tags($field) !== $field) {
                        $validator->errors()->add(
                            'categories',
                            'No tags allowed'
                        );
                    }
                }
            }
        ];
    }
}
