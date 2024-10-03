<?php


namespace App\Http\Requests;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

use Illuminate\Foundation\Http\FormRequest;

class FailedValidation extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
//            $errors = (new ValidationException($validator))->errors();
            throw new HttpResponseException(
                response()->json($validator->errors(), 422)
            );
        }

        parent::failedValidation($validator);
    }

}
