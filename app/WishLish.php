<?php

namespace App;

use App\Scopes\OrderByScope;


class WishLish extends BaseModel
{

    protected $fillable = [
        'user_id','vendor_product_id','zone_id'
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
                    ];
                    return $page;


                }
            case 'PUT':
                $page= [
                    'vendor_product_id'=>'required',
                ];
                return $page;
            case 'PATCH':
                {
                    $page= [
                        'vendor_product_id'=>'required',
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
                    ];

                    return $page ;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $page= [
                        'name.required' => 'Name files is required',
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
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrderByScope());
    }
}
