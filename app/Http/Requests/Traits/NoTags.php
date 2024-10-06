<?php

namespace App\Http\Requests\Traits;

use Illuminate\Validation\Validator;

trait NoTags {
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
