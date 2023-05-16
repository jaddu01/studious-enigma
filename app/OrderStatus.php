<?php

namespace App;

use App\Scopes\OrderByScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderStatusNew extends BaseModel
{
	protected $table = 'order_status';
    use SoftDeletes;
    protected $fillable = [
        'order_code', 'user_id', 'vendor_product_id', 'order_status', 'shipping_location', 'delivery_time','delivery_time_id','delivery_date', 'payment_mode_id', 'delivery_charge', 'tax', 'total_amount','offer_total', 'transaction_id', 'transaction_status','cart_id','zone_id','vendor_id','shopper_id','driver_id','admin_discount'
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
   



  public function ProductOrder()
    {
        return $this->belongsTo('App\ProductOrder','order_id');
        
    }
  public function User()
    {
        return $this->belongsTo('App\User','user_id');
        
    }


 
 
  
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrderByScope());
    }
}
