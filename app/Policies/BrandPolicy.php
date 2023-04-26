<?php

/**
 * @Author: abhi
 * @Date:   2021-09-23 01:42:52
 * @Last Modified by:   abhi
 * @Last Modified time: 2021-09-23 01:43:43
 */
namespace App\Policies;

use App\Helpers\Helper;
use App\PermissionAccess;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class BrandPolicy
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
        return Helper::hasUserPermission('107');
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
        return Helper::hasUserPermission('109');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create()
    {
        return Helper::hasUserPermission('108');
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
}
