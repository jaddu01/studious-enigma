<?php

namespace App;

use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderComments extends BaseModel
{

    use SoftDeletes;
    protected $fillable = [
        'order_id',
        'comment',
        
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
                        'comment' => 'required',
                      
                        
                    ];


                }
            case 'PUT':
                return [
                     'order_id' => 'required',
                        'comment' => 'required',
                ];
            case 'PATCH':
                {
                    return [
                         'order_id' => 'required',
                        'comment' => 'required',
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
                        'order_id.required' => 'this fiels is required',
                        'comment.required' => 'this fiels is required',
                    ];
                }
            case 'PUT': {
                    return [
                        'order_id.required' => 'this fiels is required',
                        'comment.required' => 'this fiels is required',
                    ];
                }
            case 'PATCH':
                {
                    return [
                        'order_id.required' => 'this fiels is required',
                        'comment.required' => 'this fiels is required',
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
