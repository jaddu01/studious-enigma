<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserWallet extends BaseModel
{

    protected $table = "user_wallets";
    protected $fillable = [
        'user_id',
        'transaction_id',
        'amount',
        'transaction_type',
        'status',
        'type',
        'description' ,
        'wallet_type',
        'created_at'
    ];
  /**
     * @param $method
     * @return array
     */
public static $staticMakeVisible = 'created_at';
   public function __construct($attributes = array())
    {
      parent::__construct($attributes);

      if (isset(self::$staticMakeVisible)){
          $this->makeVisible(self::$staticMakeVisible);
      }
   }
    public function rules($method)
    {
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
                        'user_id' => 'required',
                        'transaction_id' => 'required',
                        'amount' => 'required',
                        'description' => 'required',
                    ];


                }
            case 'PUT':
                return [
                        'user_id' => 'required',
                        'transaction_id' => 'required',
                        'amount' => 'required',
                        'description' => 'required',
                ];
            case 'PATCH':
                {
                    return [
                       'user_id' => 'required',
                        'transaction_id' => 'required',
                        'amount' => 'required',
                        'description' => 'required',
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
                        'user_id.required' => 'User ID is required',
                        'transaction_id.required' => 'Dtart Date is required',
                        'amount.required' => 'Amount is required',
                        'description.required' => 'Description is required',
                        
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                       'user_id.required' => 'User ID is required',
                        'transaction_id.required' => 'Dtart Date is required',
                        'amount.required' => 'Amount is required',
                        'description.required' => 'Description is required',
                    ];
                }
            default:break;
        }
    }

    protected static function boot()
    {
        parent::boot();
       static::addGlobalScope(new StatusScope());
    }

}
