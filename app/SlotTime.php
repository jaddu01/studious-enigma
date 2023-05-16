<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class SlotTime extends BaseModel
{

    use SoftDeletes;
    protected $fillable = [
        'to_time', 'from_time', 'lock_time', 'total_order', 'status'
    ];
    protected $appends=['name'];

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
                        'to_time'=>'required',
                        'from_time'=>'required',
                        'total_order'=>'required|numeric|min:1',
                        'lock_time'=>'required',
                    ];
                    $trans = [];
                    return $page + $trans;

                }
            case 'PUT':
                $page= [
                    'to_time'=>'required',
                    'from_time'=>'required',
                    'total_order'=>'required|numeric|min:1',
                    'lock_time'=>'required',

                ];
                $trans = [];


                return $page + $trans;
            case 'PATCH':
                {
                    $page= [
                        'to_time'=>'required',
                        'from_time'=>'required',
                        'total_order'=>'required|numeric|min:1',
                        'lock_time'=>'required',

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

    public function getNameAttribute()
    {
       // return $this->to_time . '-' . $this->from_time .' ('.$this->lock_time. ')('.$this->total_order .')';
        return $this->from_time . '-' . $this->to_time .' ('.$this->lock_time. ')('.$this->total_order .')';
    }

    protected static function boot()
    {
        parent::boot();

    }



}
