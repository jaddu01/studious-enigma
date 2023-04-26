<?php

/**
 * @Author: abhi
 * @Date:   2021-09-06 22:52:18
 * @Last Modified by:   Abhi Bhatt
 * @Last Modified time: 2022-01-29 01:04:38
 */
namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class Purchase extends BaseModel {
	//use Translatable;
    use SoftDeletes;
	protected $fillable = [
        'supplier_id','vendor_id','brand_id','product_id','quantity','price','date','invoice_no','gst','total_amount','payment_mode','payment_status'
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
                            'vendor_id' => 'required',
                            'brand_id' => 'required',
                            'product_id' => 'required',
                            'quantity' => 'required',
                            //'quantity' => 'number',
                            //'price' => 'numeric',
                            'price' => 'required',
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
                        'vendor_id' => 'required',
                            'brand_id' => 'required',
                            'product_id' => 'required',
                            'quantity' => 'required',
                            //'quantity' => 'number',
                            //'price' => 'numeric',
                            'price' => 'required',
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
                        'vendor_id' => 'required',
                            'brand_id' => 'required',
                            'product_id' => 'required',
                            'quantity' => 'required',
                            //'quantity' => 'number',
                            //'price' => 'numeric',
                            'price' => 'required',
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
                    'vendor_id.required' => 'this fiels is required',
                    'brand_id.required' => 'this fiels is required',
                    'product_id.required' => 'this fiels is required',
                    'price.required' => 'this fiels is required',
                    //'price.number' => 'insert only number',
                    'quantity.required' => 'this fiels is required',
                    //'quantity.number' => 'insert only number',
                
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'vendor_id.required' => 'this fiels is required',
                    'brand_id.required' => 'this fiels is required',
                    'product_id.required' => 'this fiels is required',
                    'price.required' => 'this fiels is required',
                    //'price.number' => 'insert only number',
                    'quantity.required' => 'this fiels is required',
                    //'quantity.number' => 'insert only number',
                ];
            }
            default:break;
        }
    }
    
}