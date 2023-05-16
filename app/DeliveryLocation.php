<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class DeliveryLocation extends BaseModel
{
    use SoftDeletes;
    protected $fillable = [
        'user_id','name','address','lat','lng','region_id','description','address_as','city','area',
    ];

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
                    return [
                        'name'=>'nullable',
                        'lat'=>'required',
                        'lng'=>'required',
                    ];

                }
            case 'PUT':

                return [
                    'name'=>'nullable',
                    'lat'=>'required',
                    'lng'=>'required',
                ];
            case 'PATCH':
                {

                    return [
                        'name'=>'nullable',
                        'lat'=>'required',
                        'lng'=>'required',
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
                    return [];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [];
                }
            default:break;
        }
    }



    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function region()
    {
        return $this->belongsTo('App\Region');
    }


    protected static function boot()
    {
        parent::boot();

    }



}
