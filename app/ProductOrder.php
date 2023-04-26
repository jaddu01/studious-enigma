<?php

namespace App;

use App\Scopes\OrderByScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductOrder extends BaseModel
{

    use SoftDeletes;
    protected $fillable = [
        'order_code', 'user_id', 'vendor_product_id', 'order_status', 'shipping_location', 'delivery_time','delivery_time_id','delivery_date', 'payment_mode_id', 'delivery_charge', 'tax', 'total_amount','offer_total', 'transaction_id', 'transaction_status','cart_id','zone_id','vendor_id','shopper_id','driver_id','admin_discount','promo_discount','coupon_code','coupon_amount','online_payment','wallet_payment','coin_payment','is_membership','order_type_id','delivery_boy_tip'
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
                        'shipping_location_id'=>'required',
                        'delivery_time_id'=>'required',
                        'payment_mode_id'=>'required',
                        'cart_id'=>'required',
                    ];
                    return $page;


                }
            case 'PUT':
                $page= [
                    'shipping_location_id'=>'required',
                    'delivery_time_id'=>'required',
                    'payment_mode_id'=>'required',
                    'cart_id'=>'required',
                ];
                return $page;
            case 'PATCH':
                {
                    $page= [
                        'shipping_location_id'=>'required',
                        'delivery_time_id'=>'required',
                        'payment_mode_id'=>'required',
                        'cart_id'=>'required',
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

    public function setVendorIdAttribute($value)
    {

        if(!empty($value)){
            $this->attributes['vendor_id'] = $value;
        }else{
            $vendor = User::where(['user_type'=>'vendor','role'=>'user'])->whereRaw('FIND_IN_SET('.$this->zone_id.', zone_id) ')->first();
            if($vendor){
                $this->attributes['vendor_id'] = $vendor->id;
            }else{
                $this->attributes['vendor_id'] = null;
            }
        }



    }
    public function setDriverIdAttribute($value)
    {
        if(!empty($value)){
            $this->attributes['driver_id'] = $value;
        }else{
            $vendor = User::where(['user_type'=>'driver','role'=>'user'])->whereRaw('FIND_IN_SET('.$this->zone_id.', zone_id) ')->first();
            if($vendor){
                $this->attributes['driver_id'] = $vendor->id;
            }else{
                $this->attributes['driver_id'] = null;
            }
        }


    }
    public function setShopperIdAttribute($value)
    {
        if(!empty($value)){
            $this->attributes['shopper_id'] = $value;
        }else{
            $vendor = User::where(['user_type'=>'shoper','role'=>'user'])->whereRaw('FIND_IN_SET('.$this->zone_id.', zone_id) ')->first();
            if($vendor){
                $this->attributes['shopper_id'] = $vendor->id;
            }else{
                $this->attributes['shopper_id'] = null;
            }
        }


    }

    public function getShippingLocationAttribute($value)
    {
        if(!empty($value)){
            return json_decode($value);
        }

    }
    public function getDeliveryTimeAttribute($value)
    {
        if(!empty($value)){
            return json_decode($value);
        }

    }
    public function User()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
    public function shopper()
    {
        return $this->belongsTo('App\User', 'shopper_id', 'id');
    }
    public function vendor()
    {
        return $this->belongsTo('App\User', 'vendor_id', 'id');
    }
    public function driver()
    {
        return $this->belongsTo('App\User', 'driver_id', 'id');
    }
    public function Shipping()
    {
        return $this->belongsTo('App\Shipping', 'shipping_id', 'id');
    }
    public function VendorCommission()
    {
        $this->belongsTo('App\VendorCommission', 'vendor_id', 'vendor_id');
    }
      public function revenue()
    {
        return $this->hasOne('App\Revenue', 'order_id', 'id');
    }
     public function OrderComments()
    {
        return $this->hasOne('App\OrderComments', 'order_id', 'id');
    }
 
    public function zone()
    {
        return $this->belongsTo('App\Zone');
    }
    public function OrderStatusNew()
    {
        return $this->belongsTo('App\OrderStatusNew');
    }

    public function VendorProduct()
    {
        return $this->belongsTo('App\VendorProduct', 'payment_mode_id', 'id');
    }
    
     public function VendorProductNew()
    {
        return $this->belongsTo('App\VendorProduct', 'vendor_product_id', 'id');
    }
    public function PaymentMode()
    {
        return $this->belongsTo('App\PaymentMode', 'payment_mode_id', 'id');
    }

    public function ProductOrderItem()
    {
        return $this->hasMany('App\ProductOrderItem', 'order_id', 'id');
    }
   
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrderByScope());
    }
}
