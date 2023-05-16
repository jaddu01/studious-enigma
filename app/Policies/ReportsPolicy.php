<?php

namespace App\Policies;

use App\Helpers\Helper;
use App\PermissionAccess;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ReportsPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
       if(Auth::guard('admin')->user()->role=='admin'){
           return false;
       }

       if(Auth::guard('admin')->user()->access_user_id=='0'){
           return true;
       }

    }

    public function index(){
        return Helper::hasUserPermission('78');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */

    public function order()
    {
        return Helper::hasUserPermission('79');
    }

    public function customer()
    {
        return Helper::hasUserPermission('80');
    }

   
    public function product()
    {
        return Helper::hasUserPermission('81');
    }

    public function slotTimes()
    {
        return Helper::hasUserPermission('82');
    }
    public function zone()
    {
        return Helper::hasUserPermission('83');
    }
     public function vendor()
    {
        return Helper::hasUserPermission('84');
    }
    public function shopper()
    {
        return Helper::hasUserPermission('85');
    }
    public function driver()
    {
        return Helper::hasUserPermission('86');
    }
     public function site()
    {
        return Helper::hasUserPermission('69');
    }
     public function auth()
    {
        return Helper::hasUserPermission('70');
    }
     public function pagination()
    {
        return Helper::hasUserPermission('71');
    }
     public function slider()
    {
        return Helper::hasUserPermission('72');
    }
     public function user()
    {
        return Helper::hasUserPermission('73');
    }
  

}
