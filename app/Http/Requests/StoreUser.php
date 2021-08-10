<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUser extends FormRequest
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
            'name'              => 'required|max:255',
            'email'             => 'required|max:255|unique:users,email'.$id,
        ];

        if($this->segment(2) < 1){
            $rules['password'] = 'required|confirmed';
        }else {
            $rules['password'] = 'confirmed';
        }
        return  $rules;

    }
}
