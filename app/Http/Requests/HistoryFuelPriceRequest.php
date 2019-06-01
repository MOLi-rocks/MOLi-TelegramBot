<?php

namespace MOLiBot\Http\Requests;

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
}
