<?php

namespace App\Http\Requests;

use App\Helpers\ResponseBuilder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;


class AddNewProductRequest extends FormRequest
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
        return [
            'category_id' => 'required',
            'measurement_class' => 'required',
            'measurement_value' => 'required',/*
            'image' => "required|array|min:1",
            'image.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg,WebP',*/
            'image' => "required|array|min:1",
            //'brand_id' =>'required',
            'gst' =>'required',
            'name:en' => 'required',
            'description:en' => 'required',
            'keywords:en' => 'required',
        ];
    }

    protected function ValidationException(Validator $validator)
    {
         throw ResponseBuilder::error($validator->errors()->first(),403);
    }
}
