<?php

namespace App;

use App\Scopes\OrderByScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends BaseModel
{

    protected $fillable = [
        'user_id','zone_id','vendor_product_id','qty'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

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
                        'vendor_product_id'=>'required',

                        'qty'=>'required|integer',
                    ];
                    return $page;


                }
            case 'PUT':
                $page= [
                    'vendor_product_id'=>'required',

                    'qty'=>'required',
                ];
                return $page;
            case 'PATCH':
                {
                    $page= [
                        'vendor_product_id'=>'required',

                        'qty'=>'required',
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
                    $page= [
                        'name.required' => 'Name files is required',
                        'qty.required' => 'qty files is required',
                    ];

                    return $page ;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $page= [
                        'name.required' => 'Name files is required',
                        'qty.required' => 'qty files is required',
                    ];

                    return $page ;
                }
            default:break;
        }
    }

    public function vendorProduct()
    {
        return $this->belongsTo('App\VendorProduct', 'vendor_product_id', 'id');
    }
     public function zone()
    {
        return $this->belongsTo('App\Zone', 'zone_id', 'id');
    }
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrderByScope());
    }
}
