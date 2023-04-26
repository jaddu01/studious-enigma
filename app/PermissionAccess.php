<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class PermissionAccess extends BaseModel
{

    use SoftDeletes;
    protected $fillable = [
        'access_level_id', 'permission_modal_id', 'type',
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

                ];
                $trans = [];


                return $page + $trans;

            }
            case 'PUT':
                $page= [

                ];
                $trans = [];

                return $page + $trans;
            case 'PATCH':
            {
                $page= [

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

    public function AccessLevel()
    {
        return $this->belongsTo('App\AccessLevel', 'access_level_id', 'id');
    }

    public function PermissionModal()
    {
        return $this->belongsTo('App\PermissionModal', 'permission_modal_id', 'id');
    }


}
