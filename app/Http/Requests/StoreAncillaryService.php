<?php

namespace App\Http\Requests;

use App\Facades\General;
use Illuminate\Foundation\Http\FormRequest;

class StoreAncillaryService extends FormRequest
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
            'type'             => 'required|numeric',
            /*'premium'          => 'required|numeric',
            'basis'            => 'required|numeric',
            'valued_inventory' => 'required|numeric',
            'pickup_toggle'    => 'required|numeric',
            'pickup_depot'     => 'required|numeric',
            'delivery_toggle'  => 'required|numeric',
            'delivery_depot'   => 'required|numeric',
            'car_type'         => 'required|numeric',*/
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

            'valued_inventory' => General::removeSpecialCharacter($this->request->get('valued_inventory'))
        ]);
    }
}
