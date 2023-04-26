<?php

/**
 * @Author: abhi
 * @Date:   2021-09-06 16:13:26
 * @Last Modified by:   abhi
 * @Last Modified time: 2021-09-06 19:53:15
 */
namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class Expenses extends BaseModel {
	//use Translatable;
	protected $fillable = [
        'title','description','price','date'
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
                            'title' => 'required',
                            'description' => 'required',
                            'price' => 'numeric',
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
                            'title' => 'required',
                            'description' => 'required',
                            'price' => 'numeric',
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
                            'title'=> 'required',
                            'description' => 'required',
                            'price' => 'numeric',
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
                    'title.required' => 'this fiels is required',
                    'description.required' => 'this fiels is required',
                    'price.required' => 'this fiels is required',
                    'price.number' => 'insert only number',
                
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'title.required' => 'this fiels is required',
                    'description.required' => 'this fiels is required',
                    'price.required' => 'this fiels is required',
                    'price.number' => 'insert only number',
                ];
            }
            default:break;
        }
    }
    
}