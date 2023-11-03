<?php

namespace App\Http\Requests\Pos;

use App\Helpers\ResponseBuilder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AddCustomerRequest extends FormRequest
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

 
    public function rules()
    {
        return [
            'name' => 'required',
            'phone_code'=>'required',
            'dob'=>'required|date',
            'phone_number' => 'required|unique:users,phone_number',
            'whatsapp_no' => 'required|unique:users,whatsapp_no',
            'address' => 'required',
        ];
        
    }

    public function messages(){
        return [
            'mobile_no.unique'=>'This mobile number is already exist'
        ];
    }

    protected function ValidationException(Validator $validator)
    {
         throw ResponseBuilder::error($validator->errors()->first(),403);
        
    }
}
