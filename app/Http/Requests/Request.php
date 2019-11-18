<?php

namespace MOLiBot\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use MOLiBot\Http\Responses\Response;

abstract class Request extends FormRequest
{
    /**
     * Customize Failed Validation Response
     *
     * @param Validator $validator
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors()->all();

        $response = new Response();

        $resBag = $response->jsonResponse(400, -1, 'validation_failed', $errors);

        throw new ValidationException($validator, $resBag);
    }
}
