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

        if(request()->type == 21){
            $rules = $rules + [
                'premium'          => 'required',
                'basis'            => 'required' 


            ];
        }

        if(request()->type == 22){
            $rules = $rules + [
                'pickup_toggle'           => 'required',
                'pickup_depot'            => 'required',
                'delivery_toggle'         => 'required',
                'delivery_depot'          => 'required' 
            ];
        }

       
        if(request()->type == 23){
            $rules = $rules + [
                'boxes'            => 'required', 
                'large_boxes'      => 'required', 
                'paper'            => 'required', 
                'tape'             => 'required'
                
            ];
        }

        if(request()->type == 24){
            $rules = $rules + [
                'boxes'            => 'required', 
                'large_boxes'      => 'required', 
                'paper'            => 'required', 
                'tape'             => 'required'
                
            ];
        }

        if(request()->type == 25){
            $rules = $rules + [
                'cleaning_options'   => 'required',
                'carpet_area'        => 'required',
                'curtains'           => 'required',
                'blinds'             => 'required' 
            ];
        }

        // dd($rules, request()->all());

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
