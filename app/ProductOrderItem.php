<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOrderItem extends BaseModel
{

    use SoftDeletes;
    protected $fillable = [
        'vendor_product_id','order_id', 'is_offer','offer_value','offer_type','offer_data','price', 'qty','status','data'
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
                        'price'=>'required',
                        'qty'=>'required',
                    ];
                    return $page;


                }
            case 'PUT':
                $page= [
                    'vendor_product_id'=>'required',
                    'price'=>'required',
                    'qty'=>'required',
                ];
                return $page;
            case 'PATCH':
                {
                    $page= [
                        'vendor_product_id'=>'required',
                        'price'=>'required',
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
                        'product_id.required' => 'Name files is required',
                        'qty.required' => 'qty files is required',
                    ];

                    return $page ;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $page= [
                        'product_id.required' => 'Name files is required',
                        'qty.required' => 'qty files is required',
                    ];

                    return $page ;
                }
            default:break;
        }
    }

    public function Product()
    {
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }
    public function vendorProduct()
    {
        return $this->belongsToMany('App\VendorProduct', 'vendor_product_id', 'id');
    }
    public function ProductOrders()
    {
        return $this->belongsTo('App\ProductOrder', 'order_id');
    }
     public function newVendorProduct()
    {
        return $this->belongsTo('App\VendorProduct', 'vendor_product_id', 'id');
    }


}
