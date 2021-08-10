<?php

namespace App\Http\Requests;

use App\Facades\General;
use Illuminate\Foundation\Http\FormRequest;

class StoreMove extends FormRequest
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
            'customer_id'   => 'required',
            'stage'         => 'required|numeric',
            'type'          => 'required|numeric',
            'status'        => 'required|numeric',
            'total_price'   => 'required|numeric',
            'amount_due'    => 'required|numeric',
            'deposit'       => 'required|numeric',
            'fee'           => 'required|numeric',
            'dwelling_type' => 'required|numeric',
            'dwelling_size' => 'required|numeric',
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
            'total_price' => General::removeSpecialCharacter($this->request->get('total_price')),
            'amount_due'  => General::removeSpecialCharacter($this->request->get('amount_due')),
            'deposit'     => General::removeSpecialCharacter($this->request->get('deposit')),
            'fee'         => General::removeSpecialCharacter($this->request->get('fee'))
        ]);
    }
}
