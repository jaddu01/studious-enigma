<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use App\Scopes\OrderByScope;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable,SoftDeletes;

    protected $appends = ['full_name','phone'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_type', 'zone_id','name','image', 'email', 'phone_code','phone_number', 'gender',  'address', 'password', 'remember_token', 'device_id','device_token', 'device_type', 'language', 'user_type', 'role', 'status','lng','lat','dob','access_user_id','otp','membership','membership_to','referral','referred_by','referral_code','wallet_amount','coin_amount','referred_by_amount','referral_amount','referral_used'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];
    public function rules($method,$id=0)
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
                    'user_type' => 'sometimes|required',
                    'name' => 'required',
                    'email' => 'sometimes|required|email|unique:users,email,NULL,id,deleted_at,NULL',
                    'phone_number' => 'required|numeric|digits:10|unique:users,phone_number,NULL,id,deleted_at,NULL',
                    'password' => 'required|string|min:6|confirmed',
                    'address' => 'sometimes|required',
                    'phone_code' => 'sometimes|required',
                ];
            }
            case 'PUT':{
                return [
                    'user_type' => 'sometimes|required',
                    'image' => 'image|mimes:jpg,png,jpeg',
                    'name' => 'sometimes|required',
                    'email' => 'sometimes|required|email|unique:users,email,'.$id.',id,deleted_at,NULL',
                    'phone_number' => 'sometimes|numeric|required|digits:10|unique:users,phone_number,'.$id.',id,deleted_at,NULL',
                    'password' => 'sometimes|required|string|min:6|confirmed',
                    'address' => 'sometimes|required',
                    'phone_code' => 'sometimes|required',
                ];
            }
            case 'PATCH':
            {
                return [
                    'user_type' => 'sometimes|required',
                    'name' => 'sometimes|required',
                    'email' => 'sometimes|required|email|unique:users,email,'.$id.',id,deleted_at,NULL',
                    'phone_number' => 'sometimes|required|numeric|digits:10|unique:users,phone_number,'.$id.',id,deleted_at,NULL',
                    'password' => 'sometimes|required|string|min:6|confirmed',
                    'address' => 'sometimes|required',
                    'phone_code' => 'sometimes|required',

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

                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name.required' => 'This field is required',
                    'image.image' => 'Please upload a valid image file',
                ];
            }
            default:break;
        }
    }

    /*public function setZoneIdAttribute($value)
    {

    }*/

    public function getZoneIdAttribute($value)
    {
        if($this->user_type=='user'){
            return (int)$value;
        }
        $value = explode(',',$value);

        return $value;
    }

    public function getFullNameAttribute($value)
    {
        return  "{$this->name}";
    }

    public function getPhoneAttribute($value)
    {

        return $this->phone_code . "-" . $this->phone_number;

    }

    /**
     * @param $value
     * @return null|string
     */
    public function getImageAttribute($value)
    {
        return Helper::hasImage($value);
    }

    public function accessLevel()
    {
        return $this->belongsTo('App\AccessLevel');
    }

    public function zone()
    {
        return $this->belongsTo('App\Zone');
    }


    public function deliveryLocation()
    {
        return $this->hasMany('App\DeliveryLocation');
    }

    public function products(){
        return $this->hasMany('App\Product', 'vendor_id')->select('*', 'per_order AS max_per_order_qty');
    }

    public function vendorProduct()
    {
        return $this->hasMany('App\VendorProduct')->select(array('*', 'per_order AS max_per_order_qty'));
    }
      public function newVendorProduct()
    {
        return $this->hasMany('App\VendorProduct')->select(array('*', 'per_order AS max_per_order_qty'))->where('qty','!=',0);
    }
    public function productOrder()
    {
        return $this->hasMany('App\ProductOrder');
    }
    public function totalOrder()
    {
        return $this->hasMany('App\ProductOrder')->count();
    }
    public function deliveredOrder()
    {
        return $this->hasMany('App\ProductOrder')->where('order_status','=','D')->count();
    }
    public function totalAmount()
    {
        return $this->hasMany('App\ProductOrder')->where('order_status','=','D')->sum('total_amount');
    }

    public function cart(){
        return $this->hasMany('App\Cart');
    }
    public function wishlist(){
        return $this->hasMany(WishLish::class,'user_id','id');
    }

    //membership
    public function membership(){
        return $this->belongsTo('App\Membership','membership');
    }
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrderByScope());
    }

    //wallet history
    public function walletHistory(){
        return $this->hasMany('App\UserWallet');
    }
   
    /* public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomPasswordReset($token));
    }*/

    //notify me
    public function notifyMe()
    {
        return $this->belongsToMany('App\VendorProduct','notify_me','user_id','product_id');
    }

}
