<?php

namespace App\Policies;

use App\Helpers\Helper;
use App\PermissionAccess;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class LanguageManagementPolicy
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
    

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */

    public function site()
    {
        return Helper::hasUserPermission('69');
    }

    /**
     * Determine whether the user can create auth language.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function authlang()
    {
        return Helper::hasUserPermission('70');
    }

    /**
     * Determine whether the user can see pagination the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function pagination()
    {
        return Helper::hasUserPermission('71');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function slider()
    {
        return Helper::hasUserPermission('72');
    }

    public function user()
    {
        return Helper::hasUserPermission('72');
    }

}
