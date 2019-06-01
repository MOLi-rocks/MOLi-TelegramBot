<?php

namespace MOLiBot\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class HistoryFuelPriceRequest extends Request
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
            'prodid' => 'required|integer|min:1|max:6'
        ];
    }

    /**
     * Customize Failed Validation Response
     *
     * @throws  HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        $messages = $validator->errors()->all();
        
        throw new HttpResponseException(response()->json(compact('messages'), 400));
    }
}
