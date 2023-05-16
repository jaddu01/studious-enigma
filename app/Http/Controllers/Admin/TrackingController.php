<?php

namespace App\Http\Controllers\Admin;


use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class TrackingController extends Controller
{

    protected $user;
    protected $method;
    function __construct(Request $request,User $user)
    {
        parent::__construct();
        $this->user=$user;
        $this->method=$request->method();
    }

      public function driverTracking()
        {
            if ($this->user->can('view', Zone::class)) {
                return abort(403,'not able to access');
            }
            $zones=User::where('status','=','1')->where('user_type','driver')->select('current_lat','current_lng','name','id')->get();
            //return $zones;
            return view('admin/pages/tracking/view-tracking-driver')->with('zones',$zones);
        }

        public function shopperTracking()
        {
            if ($this->user->can('view', Zone::class)) {
                return abort(403,'not able to access');
            }
            $zones=User::where('status','=','1')->where('user_type','shoper')->select('current_lat','current_lng','name','id')->get();
            //return $zones;
            return view('admin/pages/tracking/view-tracking-shopper')->with('zones',$zones);
        }



}