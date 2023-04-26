<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; 

class Variant extends BaseModel
{ 
    use SoftDeletes;
    protected $fillable = [
        'product_id','color','size','measurement','qty',
    ]; 
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes); 
    }
    /**
     * @param $method
     * @return array
     */
    public function rules($method)
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
                $page= [
                    'product_id'=>'required'
                ]; 
                 
                return $page;

            }
            case 'PUT':
                $page= [
                    'product_id'=>'required'
                ];
                 
                return $page;
            case 'PATCH':
            {
                $page= [
                    'product_id'=>'required',
                ];
                 
                return $page;
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
                $page= [];
                
                return $page;
            }
            case 'PUT':
            case 'PATCH':
            {
                $page= [];
                 
                return $page;
            }
            default:break;
        }
    }

     

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope());
    }



}
