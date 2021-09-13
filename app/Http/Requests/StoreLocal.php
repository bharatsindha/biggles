<?php

namespace App\Http\Requests;

use App\Facades\General;
use Illuminate\Foundation\Http\FormRequest;

class StoreLocal extends FormRequest
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
            'depot_id'           => 'required',
            'radius'             => 'required',
            'price_per'          => 'required|numeric',
            'extra_person_price' => 'required|numeric',
        ];

        return $rules;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'price_per'          => General::removeSpecialCharacter($this->request->get('price_per')),
            'min_price'          => General::removeSpecialCharacter($this->request->get('min_price')),
            'extra_person_price' => General::removeSpecialCharacter($this->request->get('extra_person_price'))
        ]);
    }
}
