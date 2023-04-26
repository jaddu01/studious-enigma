<?php

/**
 * @Author: abhi
 * @Date:   2021-10-10 14:13:18
 * @Last Modified by:   abhi
 * @Last Modified time: 2021-10-10 16:02:09
 */
namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class CoinSettings extends BaseModel
{

    use SoftDeletes;
    protected $table = "coin_settings";
    protected $fillable = [
        'from_amount',
        'to_amount',
        'coin',
        'status'
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
                        'from_amount' => 'required',
                        'coin' => 'required',
                    ];


                }
            case 'PUT':
                return [
                        'from_amount' => 'required',
                        'coin' => 'required',
                ];
            case 'PATCH':
                {
                    return [
                       'from_amount' => 'required',
                        'coin' => 'required',
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
                        'from_amount.required' => 'This field is required',
                        'coin.required' => 'This field is required',
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                       'from_amount.required' => 'This field is required',
                        'coin.required' => 'This field is required',
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
