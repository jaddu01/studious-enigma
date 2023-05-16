<?php

namespace App;

use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Revenue extends BaseModel
{

    use SoftDeletes;
    protected $fillable = [
        'order_id',
        'vendor_invoice',
        'verience_revenue',
        
    ];
   
    /**
     * @param $method
     * @return array
     */
    public function rules($method)
    {
        /*$user = User::find($this->users);*/
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
                        'order_id' => 'required',
                        'vendor_invoice' => 'required',
                        'verience_revenue' => 'required',
                        
                    ];


                }
            case 'PUT':
                return [
                    'order_id' => 'required',
                        'vendor_invoice' => 'required',
                        'verience_revenue' => 'required',
                ];
            case 'PATCH':
                {
                    return [
                        'order_id' => 'required',
                        'vendor_invoice' => 'required',
                        'verience_revenue' => 'required',
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
                        'vendor_id.required' => 'this fiels is required',
                        'vendor_invoice.required' => 'this fiels is required',
                        'verience_revenue.required' => 'this fiels is required',
                        
                     
                    ];
                }
            case 'PUT': {
                    return [
                        'vendor_id.required' => 'this fiels is required',
                        'vendor_invoice.required' => 'this fiels is required',
                        'verience_revenue.required' => 'this fiels is required',
                        
                    ];
                }
            case 'PATCH':
                {
                    return [
                        'vendor_id.required' => 'this fiels is required',
                        'vendor_invoice.required' => 'this fiels is required',
                        'verience_revenue.required' => 'this fiels is required',
                        
                    ];
                }
            default:break;
        }
    }

    public function productOrder()
    {
        return $this->hasOne('App\productOrder','order_id');
    }
  
    
  
 

   

}
