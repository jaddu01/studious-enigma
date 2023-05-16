<?php

namespace App;

use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserMembership extends BaseModel
{

    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'membership_id',
        'transaction_id',
        'start_date',
        'end_date'
        
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
                        'user_id' => 'required',
                        'membership_id' => 'required',
                        'start_date' => 'required',
                        'end_date' => 'required',
                    ];


                }
            case 'PUT':
                return [
                        'user_id' => 'required',
                        'membership_id' => 'required',
                        'start_date' => 'required',
                        'end_date' => 'required',
                ];
            case 'PATCH':
                {
                    return [
                       'user_id' => 'required',
                        'membership_id' => 'required',
                        'start_date' => 'required',
                        'end_date' => 'required',
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
                'membership_id.required' => 'Membership ID is required',
                'start_date.required' => 'Dtart Date is required',
                'end_date.required' => 'End Date is required',
                        
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                       'user_id.required' => 'User ID is required',
                'membership_id.required' => 'Membership ID is required',
                'start_date.required' => 'Dtart Date is required',
                'end_date.required' => 'End Date is required',
                    ];
                }
            default:break;
        }
    }

   public function User()
    {
        return $this->belongsTo('App\User');
    }
    public function Membership()
    {
        return $this->belongsTo('App\Membership');
    }

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StatusScope());
    }

}
