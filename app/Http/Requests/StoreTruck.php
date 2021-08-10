<?php

namespace App\Http\Requests;

use App\Facades\General;
use Illuminate\Foundation\Http\FormRequest;

class StoreTruck extends FormRequest
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
            #'company_id'  => 'required',
            'name'        => 'required',
            'description' => 'required',
            'capacity'    => 'required',
        ];

        return $rules;
    }
}
