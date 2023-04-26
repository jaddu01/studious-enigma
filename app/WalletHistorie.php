<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class WalletHistorie extends BaseModel
{

    protected $table = "wallet_histories";
    protected $fillable = [
        'customer_id',
        'transaction_id',
        'amount',
        'description'
    ];
  /**
     * @param $method
     * @return array
     */
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
                        'customer_id' => 'required',
                        'transaction_id' => 'required',
                        'amount' => 'required',
                        'description' => 'required',
                    ];


                }
            case 'PUT':
                return [
                        'customer_id' => 'required',
                        'transaction_id' => 'required',
                        'amount' => 'required',
                        'description' => 'required',
                ];
            case 'PATCH':
                {
                    return [
                       'customer_id' => 'required',
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
                        'customer_id.required' => 'User ID is required',
                        'transaction_id.required' => 'Dtart Date is required',
                        'amount.required' => 'Amount is required',
                        'description.required' => 'Description is required',
                        
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                       'customer_id.required' => 'User ID is required',
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
