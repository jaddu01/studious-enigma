<?php

/**
 * @Author: Abhi Bhatt
 * @Date:   2022-01-02 01:20:57
 * @Last Modified by:   Abhi Bhatt
 * @Last Modified time: 2022-01-02 01:23:12
 */
namespace App\Policies;

use App\Helpers\Helper;
use App\PermissionAccess;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class WalletManagementpolicy
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
        return Helper::hasUserPermission('110');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */

    public function addWallet()
    {
        return Helper::hasUserPermission('111');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */

    public function viewWallet()
    {
        return Helper::hasUserPermission('112');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */

    public function addCoin()
    {
        return Helper::hasUserPermission('113');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */

    public function viewCoin()
    {
        return Helper::hasUserPermission('114');
    }
}