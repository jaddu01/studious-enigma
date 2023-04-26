<?php

namespace App;

use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorCommission extends BaseModel
{

    use SoftDeletes;
    protected $fillable = [
        'vendor_id',
        'percent'
        
    ];
   
    /**
     * @param $method
     * @return array
     */
    public function rules($method,$id=0)
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
                        'vendor_id' => 'required|unique:vendor_commissions,vendor_id',
                        'percent' => 'required',
                        
                    ];


                }
            case 'PUT':
                return [
                     'vendor_id' => 'required|unique:vendor_commissions,vendor_id,'.$id,
                      'percent' => 'required',
                ];
            case 'PATCH':
                {
                    return [
                         'vendor_id' => 'required|unique:vendor_commissions,vendor_id,'.$id,
                        'percent' => 'required',
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
                        'percent.required' => 'this fiels is required',
                     
                    ];
                }
            case 'PUT': {
                    return [
                        'vendor_id.required' => 'this fiels is required',
                        'percent.required' => 'this fiels is required',
                        
                    ];
                }
            case 'PATCH':
                {
                    return [
                        'vendor_id.required' => 'this fiels is required',
                        'percent.required' => 'this fiels is required',
                        
                    ];
                }
            default:break;
        }
    }


    public function User()
    {
        return $this->belongsTo('App\User','vendor_id');
    }
  
    


   

}
