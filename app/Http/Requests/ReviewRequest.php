<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            //
            'description' => 'required',
            'summary' => 'required',
            'product_id' => 'required',
            'price_rate' => 'required|min:1|max:5',
            'value_rate' => 'required|min:1|max:5',
            'quality_rate' => 'required|min:1|max:5',
        ];
    }
}
