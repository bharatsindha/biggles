<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRole extends FormRequest
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
        return [
            'name'        => 'required|max:255|unique:roles,name'.$id,
            'description' => 'required',
        ];
    }
}
