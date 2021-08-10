<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompany extends FormRequest
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
        $id = ( $this->segment(2) > 0 ) ? ',' . $this->segment(2) : '';
        $rules = [
            'name'                => 'required|max:255',
            'email'               => 'required|max:255|unique:companies,email'.$id,
            'phone'               => 'required|max:255',
            'hosted_phone'        => 'required|max:255',
            'address'             => 'required|max:255',
            'website'             => 'required',
            'min_price'                => 'required|numeric',
            'stairs'                   => 'required|numeric',
            'elevator'                 => 'required|numeric',
            'long_driveway'            => 'required|numeric',
            'ferry_vehicle'            => 'required|numeric',
            'heavy_items'              => 'required|numeric',
            'extra_kms'                => 'required|numeric',
            'packing'                  => 'required|numeric',
            'storage'                  => 'required|numeric',
        ];

        return  $rules;
    }
}
