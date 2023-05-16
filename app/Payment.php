<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    protected $fillable = [
        'active_payment_page', 'cash_on_delivery', 'wallet', 'credit_card', 'paypal', 'stripe_secret_key', 'stripe_public_key', 'paypal_account_email', 'paypal_currency'
    ];

    /**
     * @param $method
     * @return array
     */
    public function rules($method)
    {
        /*$user = User::find($this->users);*/

        switch ($method) {
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


                    ];
                }
            default:
                break;
        }
    }
}
