<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class DeliveryTime extends BaseModel
{

    use SoftDeletes;
    protected $fillable = [
        'delivery_day_id','to_time','from_time','total_order','status',
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
                        'delivery_day_id'=>'required',
                        'to_time'=>'required',
                        'from_time'=>'required',
                    ];
                    $trans = [];


                    return $page + $trans;

                }
            case 'PUT':
                $page= [
                    'delivery_day_id'=>'required',
                    'to_time'=>'required',
                    'from_time'=>'required',

                ];
                $trans = [];


                return $page + $trans;
            case 'PATCH':
                {
                    $page= [
                        'delivery_day_id'=>'required',
                        'to_time'=>'required',
                        'from_time'=>'required',

                    ];
                    $trans = [];


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
                    $page= [];
                    $trans = [];

                    return $page + $trans;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $page= [];
                    $trans = [];


                    return $page + $trans;
                }
            default:break;
        }
    }

    public function deliveryDay()
    {
        return $this->belongsTo('App\DeliveryDay');
    }
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope());
    }



}
