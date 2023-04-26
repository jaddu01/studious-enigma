<?php

namespace App\Policies;

use App\Helpers\Helper;
use App\PermissionAccess;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class NotificationsPolicy
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
        return Helper::hasUserPermission('66');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */

    public function view()
    {
        return Helper::hasUserPermission('100');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create()
    {
        return Helper::hasUserPermission('99');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update()
    {
        return Helper::hasUserPermission('3');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete()
    {
        return Helper::hasUserPermission('3');
    }
    public function driverNotification()
    {
        return Helper::hasUserPermission('101');
    }
     public function shopperNotification()
    {
        return Helper::hasUserPermission('67');
    }
    public function orderStatusNotification()
    {
        return Helper::hasUserPermission('103');
    }
    public function unavailableProducts()
    {
        return Helper::hasUserPermission('102');
    }

}
