<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\NoTags;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class UpdateCommentRequest extends FormRequest
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
            'content' => 'required|max:65000',
        ];
    }

    public function after()
    {
        return [
            function (Validator $validator) {
                $this->noTagsAllowed('content', $validator);
            }
        ];
    }
}
