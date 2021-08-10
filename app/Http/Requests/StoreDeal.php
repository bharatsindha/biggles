<?php

namespace App\Http\Requests;

use App\Facades\General;
use Illuminate\Foundation\Http\FormRequest;

class StoreDeal extends FormRequest
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
            'move_id'     => 'required',
            'company_id'  => 'required',
            'total_price' => 'required|numeric',
            'deposit'     => 'required|numeric',
            'fee'         => 'required|numeric',
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
            'deposit'     => General::removeSpecialCharacter($this->request->get('deposit')),
            'fee'         => General::removeSpecialCharacter($this->request->get('fee'))
        ]);
    }
}
