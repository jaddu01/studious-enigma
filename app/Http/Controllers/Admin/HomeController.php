<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Helpers\Helper;
use App\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $user;
    protected $productOrder;
    public function __construct(User $user, ProductOrder $productOrder)
    {
        parent::__construct();
        $this->middleware('admin.auth');
        $this->user =$user;
        $this->order =$productOrder;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalUser = 0;
        $totalShopper = 0;
        $totalDriver = 0;
        $todayUser = 0;
        $totalOrder = 0;
        $todayOrder = 0;
        $userData = $this->user->whereIN('user_type',['shoper','driver','user'])
        ->where('id','!=','1')->get();
        
        if(isset($userData)){
            $totalUser = $userData->where('user_type','=','user')->count();
            $totalShopper = $userData->where('user_type','=','shoper')->count();
            $totalDriver = $userData->where('user_type','=','driver')->count();
        }

       
        $todayUserData = $this->user->whereIN('user_type',['user'])->where('id','!=','1')->whereDate('created_at','=',date('Y-m-d'))->get();
        if(isset($todayUserData)){
             $todayUser = $todayUserData->count();
        }
       
        $orderData = $this->order->select('user_id','shopper_id','driver_id','id')->get();
         if(isset($orderData)){
            $totalOrder = $orderData->count();
        }
        
        $todayOrderData = $this->order->select('user_id','shopper_id','driver_id','id','created_at')->whereDate('created_at','=',date('Y-m-d'))->get();
         if(isset($todayOrderData)){
            $todayOrder = $todayOrderData->count();
        }
        
        //return $todayOrderData;
        //return  'total-'.$totalUser.',today-'.$todayUser;
        return view('admin.dashboard')->with('totalUser',$totalUser)->with('todayUser',$todayUser)->with('totalOrder',$totalOrder)->with('totalDriver',$totalDriver)->with('todayOrder',$todayOrder)->with('totalShopper',$totalShopper)->with('totalShopper',$totalShopper);
    }
    public function profile($id=null)
    {
        $validator = JsValidatorFacade::make($this->user->rules('POST'));
        $user = User::findOrFail(Auth::guard('admin')->user()->id);

        return view('admin.profile',compact(['user','validator']));
    }




    /**
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function profileUpdate(Request $request, $id)
    {


        $input = $request->all();
        $validator = Validator::make($request->all(), $this->user->rules('PUT',$id),$this->user->messages("PUT"));

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }else{
            if($request->has('password')){
                $input['password'] = bcrypt($input['password']);
            }
            if($request->hasFile('image')){
                    $imageName = Helper::fileUpload($request->file('image'),false);
                    $input['image'] = $imageName;
            }
            $user= $this->user->FindOrFail($id)->fill($input)->save();

            return redirect()->back()->with('success','User update successful');
        }
    }
    public function userCount()
    {
        $userData = $this->user->whereIN('user_type',['shoper','driver'])->select('user_type','id')->get();
        $totalUser = $userData->count();
        $todayUser = $userData->whereDATE('created_at','=',date('Y-m-d'))->select('user_type','id')->count();
        $orderData = $this->order->select('user_id','shoper_id','driver_id','order_id')->get();
        $totalOrder = $orderData->count();
        return $totalOrder;
        //return  'total-'.$totalUser.',today-'.$todayUser;
    }

}
