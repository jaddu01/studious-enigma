<?php

namespace App;

use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FirstOrder extends BaseModel
{

    protected $table = "first_order";
    protected $fillable = [
        'free_product',
        'status'
    ];
   
    /**
     * @param $method
     * @return array
     */
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
                    return [
                        'free_product' => 'required',
                        
                    ];


                }
            case 'PUT':
                return [
                    'free_product' => 'required',
                ];
            case 'PATCH':
                {
                    return [
                        'free_product' => 'required',
                    ];
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
                        'free_product.required' => 'this fiels is required',
                        
                     
                    ];
                }
            case 'PUT': {
                    return [
                        'free_product.required' => 'this fiels is required',
                    ];
                }
            case 'PATCH':
                {
                    return [
                        'free_product.required' => 'this fiels is required',
                        
                    ];
                }
            default:break;
        }
    }

    public function Product()
    {
        return $this->hasMany('App\Product', 'free_product', 'id');
    }
   public function getFreeProductAttribute($value)
    {
      
        $value = explode(',',$value);
        return $value;
    }
     

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StatusScope());
    }
  
 

   

}
