<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrip extends FormRequest
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
            'company_id'          => 'required',
            'start_addr'          => 'required|max:255',
            'start_lat'           => 'required',
            'start_lng'           => 'required',
            'start_city'          => 'required',
            'start_postcode'      => 'required',
            'start_date'          => 'required',
            'end_addr'            => 'required',
            'end_lat'             => 'required',
            'end_lng'             => 'required',
            'end_city'            => 'required',
            'end_postcode'        => 'required',
            #'route'               => 'required',
            'transit_time'        => 'required',
            'pickup_notice'       => 'required',
            'price_per'           => 'required',
            'min_price'           => 'required',
            #'discount'           => 'required',
            'capacity'            => 'required',
            'transport'           => 'required'
        ];

        return  $rules;
    }
}
