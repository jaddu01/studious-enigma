<?php

namespace App\Http\Controllers\Admin;


use App\City;
use App\Category;
use App\DeliveryDay;
use App\DeliveryTime;
use App\Helpers\Helper;
use App\Notification;
use App\Notifications\OrderStatus;
use App\ProductOrderItem;
use App\Scopes\StatusScope;
use App\ProductOrder;
use App\User;
use App\VendorProduct;
use App\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class NotificationController extends Controller
{
    protected $user;
    protected $notification;
    protected $method;
    function __construct(Request $request,User $user,Notification $notification)
    {
        parent::__construct();
        $this->user=$user;
        $this->notification=$notification;
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        //$user= $this->notification->where('notifiable_id','=',48)->update(['read_at'=> now()]);
        return view('admin/pages/notification/index');
    }
    public function unavailableProductOrders()
    {

       // $user= $this->notification->where('type','=','App\Notifications\ProductStatus')->update(['read_at'=> now()]);
        if ($this->user->can('unavailableProducts', Notification::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/notification/unavailable-products');
    }

     public function updateProducts()
    {

        //$user= $this->notification->whereIn('type',['App\Notifications\ProductUpdate','App\Notifications\ProductOutStockStatus','App\Notifications\NewProduct','App\Notifications\ManageProductUpdate','App\Notifications\ManageOutStock'])->update(['read_at'=> now()]);
        if ($this->user->can('shopperNotification', Notification::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/notification/update-products');
    }
     public function orderStatus()
    {

        //$user= $this->notification->whereIn('type',['App\Notifications\OrderStatus','App\Notifications\AllOrderStatus'])->update(['read_at'=> now()]);
        if ($this->user->can('orderStatusNotification', Notification::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/notification/order-status');
    }
     public function addressUpdate()
    {

        //$user= $this->notification->whereIn('type',['App\Notifications\AddressUpdate'])->update(['read_at'=> now()]);
        if ($this->user->can('driverNotification', Notification::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/notification/address-update');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->user->can('delete', Notification::class)) {
            return abort(403,'not able to access');
        }
        $flight = $this->notification->findOrFail($id);
        $flight->delete();
        if($flight){
            return response()->json([
                'status' => true,
                'message' => 'deleted'
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'some thing is wrong'
            ],400);
        }


    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function anyData(Request $request)
    {   //die('asd');
        $notifications =  $this->notification->selectRaw('*,id as id1')->where('notifiable_id','=','48')->orderBy('created_at','DESC')->get();
        $start = 1;
        return Datatables::of($notifications)
            ->addColumn('Slno',function ($notifications) use(&$start)  {
                return  $start++;
            })
            ->addColumn('heading',function ($notifications){
                $data = json_decode($notifications->data,true);
                return $data['message'];

            })
            ->addColumn('seen',function ($notifications){
                return $notifications->read_at==null ? 'Not Read':'Read';

            })
            ->addColumn('action',function ($notifications){
                $data = json_decode($notifications->data,true);
                return '<a href="'.route('order.show',$data['order_id']).'"  class="btn btn-success">Direct to order</a><a href="'.route('order.show',$data['order_id']).'"  class="btn btn-success">Change Status</a><button type="button" onclick="deleteRow(\''.$notifications->id1.'\')" class="btn btn-danger">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);

    }
    
     public function updateproductData(Request $request)
    {   //die('asd');
    /*$vendortable = VendorProduct::where('user_id',$data['vendor_id'])->where('product_id',$data['product_id'])->select('id')->first();
    $vendortableId =  $vendortable->id;*/
        $notifications =  $this->notification->selectRaw('*,id as id1')->whereIn('type',['App\Notifications\ProductUpdate','App\Notifications\ProductOutStockStatus','App\Notifications\NewProduct','App\Notifications\ManageProductUpdate','App\Notifications\ManageOutStock'])->orderBy('created_at','DESC')->get();
        //return $notifications;
        $start = 1;
        return Datatables::of($notifications)
            ->addColumn('Slno',function ($notifications) use(&$start)  {
                return  $start++;
            })
            ->addColumn('heading',function ($notifications){
                $data = json_decode($notifications->data,true);
                 return isset($data['message']) ? $data['message'] : '';

            })
            ->addColumn('vendor',function ($notifications){
                $data = json_decode($notifications->data,true);
                return isset($data['vendor']) ? $data['vendor'] : '';

            })
            ->addColumn('product_name',function ($notifications){
                $data = json_decode($notifications->data,true);
                return isset($data['product_name']) ? $data['product_name'] : '';
                

            })
            ->addColumn('price',function ($notifications){
                $data = json_decode($notifications->data,true);
                return isset($data['requested_price']) ? $data['requested_price'] : '';

            })
            ->addColumn('offer_price',function ($notifications){
                $data = json_decode($notifications->data,true);
                 return isset($data['requested_offer_price']) ? $data['requested_offer_price'] : '';

            })
            ->addColumn('type',function ($notifications){
                $data = json_decode($notifications->data,true);
                return isset($data['type']) ? $data['type'] : '';

            })
            
            ->addColumn('shopper',function ($notifications){
                $data = json_decode($notifications->data,true);
                return isset($data['shopper']) ? $data['shopper'] : '';

            })
           
                ->addColumn('action',function ($notifications){
                $data = json_decode($notifications->data,true);
                /*if($notifications->type != 'App\Notifications\NewProduct' ){*/
                    /*if( $notifications->type == 'App\Notifications\ManageProductUpdate' || $notifications->type == 'App\Notifications\ManageOutStock'){

                        return '<button type="button" onclick="deleteRow(\''.$notifications->id1.'\')" class="btn btn-danger">Delete</button>';
                    }else{*/
                       /* <a href="'.route("vendor-product.edit",isset($data["vendor_id"]) ? $data["vendor_id"] : "").'" class="btn btn-success">View</a>*/
                       
                         return '<a href="'.route('order.show',isset($data["order_id"]) ? $data["order_id"] : "").'"  class="btn btn-success">Direct to order</a> <a href="javascript:void(0)"  onclick="openOrderAddressModel(\''.$notifications->id1.'\')" class="btn btn-success">View</a><button type="button" onclick="deleteRow(\''.$notifications->id1.'\')" class="btn btn-danger">Delete</button>';
                    //}

                   
              /*  }else{
                     return '<button type="button" onclick="deleteRow(\''.$notifications->id1.'\')" class="btn btn-danger">Delete</button>';

                }*/
             })   
         
            
            ->rawColumns(['action'])
            ->make(true);

    }

     public function unavailableData(Request $request)
    {
        $notifications =  $this->notification->selectRaw('*,id as id1')->where('type','=','App\Notifications\ProductStatus')->orderBy('created_at','DESC')->get();
        $start = 1;
        return Datatables::of($notifications)
            ->addColumn('Slno',function ($notifications) use(&$start)  {
                return  $start++;
            })
            ->addColumn('heading',function ($notifications){
                $data = json_decode($notifications->data,true);
                return $data['message'];

            })
            ->addColumn('order_code',function ($notifications){
                $data = json_decode($notifications->data,true);
                return $data['order_code'];

            })
            ->addColumn('product_name',function ($notifications){
                $data = json_decode($notifications->data,true);
                return isset($data['product_name']) ? $data['product_name'] : '';
                

            })
            ->addColumn('shopper',function ($notifications){
                $data = json_decode($notifications->data,true);
                return isset($data['shopper']) ? $data['shopper'] : '';

            })
            ->addColumn('seen',function ($notifications){
                return $notifications->read_at==null ? 'Not Read':'Read';

            })
            ->addColumn('action',function ($notifications){
                $data = json_decode($notifications->data,true);
                return '<a href="'.route('order.show',$data['order_id']).'"  class="btn btn-success">Direct to order</a><button type="button" onclick="deleteRow(\''.$notifications->id1.'\')" class="btn btn-danger">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);

    }
     public function orderStatusData(Request $request)
    {
        $notifications =  $this->notification->selectRaw('*,id as id1')->whereIn('type',['App\Notifications\OrderStatus','App\Notifications\AllOrderStatus'])->orderBy('created_at','DESC')->get();
        $start = 1;
        return Datatables::of($notifications)
            ->addColumn('Slno',function ($notifications) use(&$start)  {
                return  $start++;
            })
            ->addColumn('heading',function ($notifications){
                $data = json_decode($notifications->data,true);
                return $data['message'];

            })
            ->addColumn('order_code',function ($notifications){
                $data = json_decode($notifications->data,true);
                return $data['order_code'];

            })
            ->addColumn('sendor',function ($notifications){
                //if($notifications->type == 'App\Notifications\AllOrderStatus' ){
                    $data = json_decode($notifications->data,true);
                    return isset($data['sender']) ? $data['sender'] : '';
                //}
                

            })
            
            ->addColumn('action',function ($notifications){
                $data = json_decode($notifications->data,true);
                return '<a href="'.route('order.show',$data['order_id']).'"  class="btn btn-success">Direct to order</a><button type="button" onclick="deleteRow(\''.$notifications->id1.'\')" class="btn btn-danger">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);

    }
      public function addressUpdateData(Request $request)
    {
        $notifications =  $this->notification->selectRaw('*,id as id1')->whereIn('type',['App\Notifications\AddressUpdate'])->orderBy('created_at','DESC')->get();
        //return  $notifications;
        $start = 1;
        return Datatables::of($notifications)
            ->addColumn('Slno',function ($notifications) use(&$start)  {
                return  $start++;
            })
            ->addColumn('notification',function ($notifications){
                return "Address Update";

            })
            ->addColumn('user_name',function ($notifications){
                $data = json_decode($notifications->data,true);
                return isset($data['user_name']) ? $data['user_name'] : '';
                
            })
            ->addColumn('address',function ($notifications){
                $data = json_decode($notifications->data,true);
                return isset($data['address']) ? $data['address'] : '';
                
            })
            ->addColumn('driver_name',function ($notifications){
                $data = json_decode($notifications->data,true);
                return isset($data['driver_name']) ? $data['driver_name'] : '';
                
            })
            ->addColumn('created_at',function ($notifications){
                $data = json_decode($notifications->data,true);
                return isset($data['created_at']['date']) ? $data['created_at']['date'] : '';
                
            })
            ->addColumn('action',function ($notifications){
                $data = json_decode($notifications->data,true);
                return '<a href="'.route('customer.show',$data['customer_id']).'"  class="btn btn-success">Customer</a><a href="javascript:void(0)"  onclick="openOrderAddressModel(\''.$notifications->id1.'\')" class="btn btn-success">View</a><button type="button" onclick="deleteRow(\''.$notifications->id1.'\')" class="btn btn-danger">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);

    }



    public function changeStatus(Request $request){

        $user = $this->notification->where('id','=',$request->id)->update(['read_at'=> now()]);


        if($request->ajax()){
            if(isset($user)){
                return response()->json([
                    'status' => true,
                    'message' => 'successfully updated'
                ],200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'some thing is wrong'
                ],400);
            }
        }
    }

      public function addressDetails(Request $request)
    {
        $notifications =  $this->notification->select('data')->where('id','=',$request->id)->get();
        $data = json_decode($notifications[0]->data,true);
        if($notifications){
            return response()->json([
                'status' => true,
                'data' => $data
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'some thing is wrong'
            ],400);
        }

    }

    

}
