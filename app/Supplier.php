<?php

/**
 * @Author: abhi
 * @Date:   2021-09-13 23:21:01
 * @Last Modified by:   abhi
 * @Last Modified time: 2021-09-23 01:38:16
 */
namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class Supplier extends BaseModel {
	use SoftDeletes;
	//use Translatable;
	protected $fillable = [
        'company_name','email','contact_person','contact_number','bank_name','bank_account_number','bank_ifsc_code','address','city','state','pin_code','country','phone_number','pan_number','gstin_number','tax_state','opening_balance','account_type','remark','status'
    ];

    public function rules($method)
    {
        $locales = config('translatable.locales');
        switch($method)
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                $page= [

                ];
                $trans = [];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'company_name' => 'required',
                            //'email'=>'email',
                            //'city' => 'required',
                            //'state' => 'required',
                            //'tax_state' => 'required',
                            //'contact_number' => 'required',
                        ];
                }
                return $page + $trans;

            }
            case 'PUT':
                $page= [

                ];
                $trans = [];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'company_name' => 'required',
                            //'email'=>'email',
                            //'city' => 'required',
                            //'state' => 'required',
                            //'tax_state' => 'required',
                            //'contact_number' => 'required',
                        ];
                }
                return $page + $trans;
            case 'PATCH':
            {
                $page= [

                ];
                $trans = [];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'company_name' => 'required',
                            //'email'=>'email',
                            //'city' => 'required',
                            //'state' => 'required',
                            //'tax_state' => 'required',
                            //'contact_number' => 'required',
                        ];
                }
                return $page + $trans;
            }
            default:break;
        }
    }


    public function messages($method)
    {
        /*$user = User::find($this->users);*/

        switch($method)
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'company_name.required' => 'this fiels is required',
                    //'email.email'=>'insert correct email',
                    //'city.required' => 'this fiels is required',
                    //'state.required' => 'this fiels is required',
                    //'tax_state.required' => 'this fiels is required',
                    //'contact_number.required' => 'this fiels is required',
                
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'company_name.required' => 'this fiels is required',
                    //'email.email'=>'insert correct email',
                    //'city.required' => 'this fiels is required',
                    //'state.required' => 'this fiels is required',
                    //'tax_state.required' => 'this fiels is required',
                    //'contact_number.required' => 'this fiels is required',
                ];
            }
            default:break;
        }
    }
    
}