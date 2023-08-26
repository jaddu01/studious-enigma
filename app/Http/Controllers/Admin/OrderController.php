<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Category;
use App\DeliveryDay;
use App\DeliveryTime;
use App\Helpers\Helper;
use App\OrderStatusNew;
use App\DeliveryLocation;
use App\ProductOrderItem;
use App\Scopes\StatusScope;
use App\ProductOrder;
use App\Notifications\OrderStatus;
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
use PDF;
use Illuminate\Support\Facades\View;
use Anam\PhantomMagick\Converter;
use App\SiteSetting;
use Imagick;
use Storage;
use redirect;
use App\UserWallet;
use Auth;


use GuzzleHttp\Client;
use Log;

class OrderController extends Controller
{
    protected $user;
    protected $order;
    protected $productOrderItem;
    protected $method;


    function __construct(Request $request,User $user, ProductOrder $order,ProductOrderItem $productOrderItem,OrderStatusNew $orderstatusnew,UserWallet $user_wallet,VendorProduct $vendorProduct)
    {
        parent::__construct();
        $this->user=$user;
        // $this->imagick = $imagick;
        $this->order=$order;
        $this->productOrderItem=$productOrderItem;
        $this->orderstatusnew=$orderstatusnew;
        $this->user_wallet = $user_wallet;
        $this->vendorProduct=$vendorProduct;
        $this->method=$request->method();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($this->user->can('view', ProductOrder::class)) {
          return abort(403,'not able to access');
        }
        $zones = Zone::get()->pluck('name','id');
        $vandors=$this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');
        $shoper=$this->user->where(['user_type'=>'shoper','role'=>'user'])->get()->pluck('full_name','id');
        $driver=$this->user->where(['user_type'=>'driver','role'=>'user'])->get()->pluck('full_name','id');
        $order_type = 'all';
        if (isset($request->order_type)) {
            $order_type = $request->order_type;
        }
        return view('admin/pages/order/index',compact(['zones','vandors','driver','shoper','order_type']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', ProductOrder::class)) {
            return abort(403,'not able to access');
        }

        $validator = JsValidatorFacade::make($this->offer->rules('POST'));
        $vandors=$this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');
        return view('admin/pages/offer/add')->with('vandors',$vandors)->with('validator',$validator);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $validator = JsValidatorFacade::make($this->offer->rules('PUT'));
        $offer=$this->offer->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $vandors=$this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');
        return view('admin/pages/offer/edit')->with('offer',$offer)->with('vandors',$vandors)->with('validator',$validator);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $flight = $this->offer->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $flight->delete();
        $flight->deleteTranslations();
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
     * @return mixed
     */
   public function anyData(Request $request)
    {   

        $auth_user = Auth::guard('admin')->user();
        $auth_zone_ids = $auth_user->zone_id;
        $auth_zone_ids = explode(',',$auth_zone_ids);
        $auth_user_role = $auth_user->role;
        $orders = $this->order->with(['User','zone','shopper','driver'])->select('*');
        if($auth_user_role!='admin') {
            if(isset($auth_zone_ids) && !empty($auth_zone_ids)) {
                for ($i=0; $i < count($auth_zone_ids); $i++) { 
                    $orders->where('zone_id', '=', $auth_zone_ids[$i]);
                    if($i>0) {
                        $orders->orWhere('zone_id', '=', $auth_zone_ids[$i]);
                    }
                }
            }
        }
        
        if($request->has('order_status') and !empty($request->order_status)){

            $orders->where(['order_status'=>$request->order_status]);
        }
        if($request->has('order_type') and $request->order_type=='today'){

            $orders->whereDate('created_at','=',date('Y-m-d'));
        }
        if($request->has('zone_id') and !empty($request->zone_id)){

            $orders->where(['zone_id'=>$request->zone_id]);
        }
        if($request->has('vendor_id') and !empty($request->vendor_id)){

            $orders->where(['vendor_id'=>$request->vendor_id]);
        }
        if($request->has('shopper_id') and !empty($request->shopper_id)){

            $orders->where(['shopper_id'=>$request->shopper_id]);
        }
        if($request->has('driver_id') and !empty($request->driver_id)){

            $orders->where(['driver_id'=>$request->driver_id]);
        }
        if ($request->has('delivery_from_date') and !empty($request->delivery_from_date)) {
            $orders->whereDate('delivery_date','>=',$request->delivery_from_date);
        }
        if ($request->has('delivery_to_date') and !empty($request->delivery_to_date)) {
            $orders->whereDate('delivery_date','<=',$request->delivery_to_date);
        }
        if ($request->has('created_from_date') and !empty($request->created_from_date)) {
            $orders->whereDate('created_at','>=',$request->created_from_date." 00:00:00");
        }
        if ($request->has('created_to_date') and !empty($request->created_to_date)) {
            $orders->whereDate('created_at','<=',$request->created_to_date." 23:59:59");
        }
        if ($request->has('total_amount_from') and !empty($request->total_amount_from)) {
            $orders->where('total_amount','>=',$request->total_amount_from);
        }
        if ($request->has('total_amount_to') and !empty($request->total_amount_to)) {
            $orders->where('total_amount','<=',$request->total_amount_to);
        }
        if($request->has('cust_type') and $request->cust_type=='today'){
              $orders->whereDate('created_at','=',date('Y-m-d'));
        }
        $orders->where('order_type_id','!=',2);
        $orders->get();
        //$order =contact::query();
        return Datatables::of($orders)
             ->addColumn('order_status',function ($orders){
              if(array_key_exists($orders->order_status, Helper::$order_status)){
                return Helper::$order_status[$orders->order_status];
              }else{
                return "";
              }
                 
            })
           ->addColumn('delivery_date',function ($user){
                return date('d/m/Y',strtotime($user->delivery_date));
            })
            ->addColumn('time_slot',function ($orders){
                if(!empty( $orders->delivery_time)){
                    return $orders->delivery_time->from_time.'-'.$orders->delivery_time->to_time;
                }else{
                    if($orders->delivery_type === 'in_store_pickup'){
                        return 'In Store Pickup';
                    }else if($orders->delivery_type === 'standard_delivery'){
                        return 'Standard Delivery';
                    }else{
                        return 'Fast Delivery';
                    }
                }
            })

            ->editColumn('total_amount',function ($orders){
              return isset($orders->offer_total) ? $orders->offer_total : '';

            })
            ->editColumn('shopper',function ($orders){
              return isset($orders->shopper) ? $orders->shopper->name : '';

            })
            ->editColumn('driver',function ($orders){
              return isset($orders->driver) ? $orders->driver->name : '';

            })

            ->addColumn('address',function ($orders){
              return isset($orders->shipping_location->address) ? $orders->shipping_location->address : '';
                //return $orders->shipping_location->address;

            })
              ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($orders){
               if($orders->order_status == 'S'){
                return '<a href="'.route('order.show',$orders->id).'"  class="btn btn-success">Show</a><a href="'.route('order.invoice',$orders->id).'"  class="btn btn-success">Invoice</a><a href="'.route('order.track',$orders->id).'"  class="btn btn-success">Track Order</a><a    onclick="makeAcall('.$orders->id.')"      class="btn btn-success">Call</a><a href="'.route('order.statuslist',$orders->id).'"  class="btn btn-success">Check Status</a>';
              }

              if($orders->order_status == 'N' || $orders->order_status == 'CF' || $orders->order_status == 'UP'){
               return '<a href="'.route('order.show',$orders->id).'"  class="btn btn-success">Show</a><a href="'.route('order.show',$orders->id).'"  class="btn btn-success">Edit</a><a href="'.route('order.invoice',$orders->id).'"  class="btn btn-success">Invoice</a><a    onclick="makeAcall('.$orders->id.')"      class="btn btn-success">Call</a><a  onclick="popupchangestatus('.$orders->id.',\''.$orders->order_status.'\')" class="btn btn-success">Change Status</a><a href="'.route('order.statuslist',$orders->id).'"  class="btn btn-success">Check Status</a>';
              }else{
                  return '<a href="'.route('order.show',$orders->id).'"  class="btn btn-success">Show</a><a href="'.route('order.invoice',$orders->id).'"  class="btn btn-success">Invoice</a><a    onclick="makeAcall('.$orders->id.')"      class="btn btn-success">Call</a><a href="'.route('order.statuslist',$orders->id).'"  class="btn btn-success">Check Status</a>';
              }
            })
            ->filterColumn('user_phone', function($query, $keyword) {
                $sql = "exists (select * from `users` where `product_orders`.`user_id` = `users`.`id` and CONCAT(users.phone_code,'-',users.phone_number)  like ? and `users`.`phone_number` is not null order by `updated_at` desc) ";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('users.name', function($query, $keyword) {
               $sql = "exists (select * from `users` where `product_orders`.`user_id` = `users`.`id` and `users`.`name` like ? ) ";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('shopper', function($query, $keyword) {
               $sql = "exists (select * from `users` where `product_orders`.`shopper_id` = `users`.`id` and `users`.`name` like ? ) ";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('driver', function($query, $keyword) {
               $sql = "exists (select * from `users` where `product_orders`.`driver_id` = `users`.`id` and `users`.`name` like ? ) ";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            ->filterColumn('zone.name', function($query, $keyword) {
              $sql = "exists (select * from `zone_translations` where `product_orders`.`zone_id` = `zone_translations`.`zone_id` and `zone_translations`.`name` like ?  and `zone_translations`.`locale` = 'en'  order by `updated_at` desc) ";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
            /*->filterColumn('order_status', function($query, $keyword) {
              $sql = "exists (select * from `product_orders` where `product_orders`.`order_status`  like ? ) ";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })*/
             /*->filterColumn('user_name', function($query, $keyword) {
            $query->havingRaw('LOWER(user.full_name) LIKE ?', ["%{$keyword}%"]);
            })*/
            
            ->rawColumns(['image','action'])
            ->make(true);
            
           // $category = $this->offer->with('User')->get();
          
        //$start = $request->start;
        
    }
    
    
    
    
       public function anyDataOrderStatus(Request $request)
    {   //die('asd');
       
     $orders = $this->orderstatusnew->with(["ProductOrder",'User'])->where('order_id', '=', $request->order_id)->orderBy('order_id', 'DESC')->get();
  
       
      //echo "<pre>"; print_r($orders->toArray());
      // die;
       
        //$order =contact::query();
        $datatable = Datatables::of($orders);
        
        foreach($orders as $orderlist){

                     $datatable->addColumn('order_code', function($orderlist){
						 return $orderlist->ProductOrder->order_code;
                  

                 });
                     $datatable->addColumn('message', function($orderlist){

                     return Helper::$order_status[$orderlist->status]; 

                 });
                     $datatable->addColumn('name', function($orderlist){

					return $orderlist->user->name; 

                 });
                 $datatable->addColumn('created_at',function ($orderlist){
					return $orderlist->created_at;

            });

             }

    
            return $datatable->make(true);;
            
           // $category = $this->offer->with('User')->get();
          
        //$start = $request->start;
        
    }
    
    
    
    
    public function show($id)
    {
        $orders_details = $this->order->with(['ProductOrderItem','zone'])->findOrFail($id);
    // echo "<pre>"; print_r($orders_details->toArray());die;
      //  print_r($orders_details->toArray());
        return view('admin/pages/order/show')->with('orders_details',$orders_details);
    }
    
    
    public function statuslist($id)
    {
        $orders_details = $this->order->with(['ProductOrderItem','zone',"OrderStatusNew"])->findOrFail($id);
				// echo "<pre>";print_r($orders_details->toArray());
 
      return view('admin/pages/order/statuslist')->with('orders_details',$orders_details)->with('orderID',$id);
    }
    
    
    
    
    public function editQty(Request $request,$id)
    {

        $productOrderItem = $this->productOrderItem->findOrFail($id);

        if($request->isMethod('post')){

            $validator = Validator::make($request->all(),['qty'=>'required|integer|min:1']);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            DB::beginTransaction();

            try {

                $venderProduct = VendorProduct::where(['id' => $productOrderItem->vendor_product_id])->firstOrFail();
                $item_price = $productOrderItem->price / $productOrderItem->qty;
                if ($productOrderItem->qty >= $request->qty) {

                    VendorProduct::where(['id' => $productOrderItem->vendor_product_id])->increment('qty', ($productOrderItem->qty - $request->qty));

                    $this->order->where(['id' => $productOrderItem->order_id])->decrement('total_amount', $productOrderItem->price - $item_price * $request->qty);
                    
                    $this->order->where(['id' => $productOrderItem->order_id])->decrement('offer_total', $productOrderItem->price - $item_price * $request->qty);

                    $productOrderItem->update(['qty' => $request->qty, 'price' => $item_price * $request->qty]);
                    Session::flash('success', trans('order.item_remove_create_success'));
                } else {
                    if ($venderProduct->qty >= $request->qty) {

                        VendorProduct::where(['id' => $productOrderItem->vendor_product_id])->decrement('qty', ($request->qty - $productOrderItem->qty));

                        $this->order->where(['id' => $productOrderItem->order_id])->increment('total_amount', $item_price * $request->qty - $productOrderItem->price);
                        
                        $this->order->where(['id' => $productOrderItem->order_id])->increment('offer_total', $item_price * $request->qty - $productOrderItem->price);

                        $productOrderItem->update(['qty' => $request->qty, 'price' => $item_price * $request->qty]);
                        Session::flash('success', trans('order.item_remove_create_success'));
                    } else {
                        Session::flash('danger', trans('order.out_of_stock'));
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
                DB::rollBack();
            }

        }
        return view('admin/pages/order/edit_qty')->with('ProductOrderItem',$productOrderItem);

    }
    public function removeOrderItem(Request $request,$id)
    {
        DB::beginTransaction();
        try {
            $productOrderItem = $this->productOrderItem->findOrFail($id);

            VendorProduct::where(['id'=>$productOrderItem->vendor_product_id])->increment('qty',$productOrderItem->qty);

            $this->order->where(['id'=>$productOrderItem->order_id])->decrement('total_amount',$productOrderItem->price);
             $this->order->where(['id'=>$productOrderItem->order_id])->decrement('offer_total',$productOrderItem->price);
            $productOrderItem->delete();
            Session::flash('success','Deleted successfully');
            DB::commit();
        } catch (\Exception $e) {
            Session::flash('danger',$e->getMessage());
            DB::rollBack();
        }
        //

        return redirect()->back();

    }

    public function addProduct(Request $request,$id){
    $order =  $this->order->findOrFail($id);
    //return 'hi';
    if($order->order_status == 'N' || $order->order_status == 'CF' || $order->order_status == 'UP'){
      $venderProducts = VendorProduct::where(['user_id'=>$order->vendor_id])->with(['Product.image','Product'=>function($q){
          $q->listsTranslations('name');
      }])->get();
   
     
      if($request->isMethod('post')){
		
          DB::beginTransaction();
		
          try {
              $venderProduct = VendorProduct::with(['Product.image','offer','Product.MeasurementClass'])->findOrFail($request->vendor_product_id);

              $is_offer = 'no';
              $offer_value = 0;
              $offer_type = null;
              $offer_data = json_encode(array());
              $productData = json_encode(['vendor_product'=>$venderProduct]);
              if ($venderProduct->is_offer) {
                  $offer_value = $venderProduct->offer->offer_value;
                  $offer_type = $venderProduct->offer->offer_type;
                  $offer_data = json_encode($venderProduct->offer);
                  $is_offer = 'yes';

              }
              $request->request->add([
                  'vendor_product_id' => $request->vendor_product_id,
                  'order_id' => $id,
                  'price' => $venderProduct->offer_price * $request->qty,
                  'qty' => $request->qty,
                  'is_offer' => $is_offer,
                  'offer_value' => $offer_value,
                  'offer_type' => $offer_type,
                  'offer_data' => $offer_data,
                  'data' => $productData,

              ]);

              $this->productOrderItem->fill($request->all())->save();
              $venderProduct->decrement('qty',$request->qty);
              $offer_total = $venderProduct->offer_price * $request->qty;
              $offer_value = $offer_value * $request->qty;
              $order->increment('total_amount',$offer_total);
             // $order->increment('offer_total',$offer_value);
              $order->increment('offer_total',$offer_total);
              if($order->order_status == 'N' || $order->order_status == 'CF' || $order->order_status == 'UP'){
                    $order->update(['order_status'=>'UP']);
                     $shopper_id = $order->shopper_id;
                    $driver_id  =  $order->driver_id;
                    $shopperData = User::whereIn('id', [$shopper_id, $driver_id])->select('id','device_type','device_token')->get();
                   
                    $shopper_id_array = collect($shopperData)->where('id', $shopper_id)->pluck('device_token');
                    $driver_id_array = collect($shopperData)->where('id', $driver_id)->pluck('device_token');
                    $shopper_device_type_array = $shopperData->where('id', $shopper_id)->pluck('device_type');
                    $shopper_device_type=$shopper_device_type_array[0];

                    $driver_device_type_array = $shopperData->where('id', $driver_id)->pluck('device_type');
                    $driver_device_type=$driver_device_type_array[0];
                    //echo "<pre>"; print_r($user_id_array); die;
                    $shopperArray = [];
                    $shopperArray['type'] = 'Updated';
                    $shopperArray['title'] = 'Order updated';
                    $shopperArray['body'] = $order->order_code.' '.trans('order.update_success');
                    

                   // print_r($driver_device_type);
                   // die();
                    //shopper notifiction
                    Helper::sendNotification($shopper_id_array ,$shopperArray, $shopper_device_type);
                    //driver notifiction
                    Helper::sendNotification($driver_id_array ,$shopperArray, $driver_device_type);
                }
              Session::flash('success',trans('order.item_remove_create_success'));
              DB::commit();
          } catch (\Exception $e) {
              Session::flash('danger',$e->getMessage());
              DB::rollBack();
          }
      }
        return view('admin/pages/order/add_item',compact(['order','venderProducts']));
      }else{
        return redirect()->back();
      }

    }


    public function modifyAddress(Request $request,$id){
      $order =  $this->order->with(['User.deliveryLocation'])->findOrFail($id);
        
      if($order->order_status == 'N' || $order->order_status == 'CF' || $order->order_status == 'UP'){
        $deliveryLocationArray = collect($order->user->deliveryLocation)->pluck('name','id');
        //return $deliveryLocationArray;
       

       
        $shipping_location = collect($order->shipping_location)->toArray();
        $deliveryLocationArray[0] = 'New Address';
       
        //return $deliveryLocationArray;
        //return $order->shipping_location;
        if($request->isMethod('post')){
            try {
                 $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'address' => 'required',
                    'lat' => 'required',
                    'lng' => 'required'
                    ]);
                    
                   if ($validator->fails()) {
                        Session::flash('danger', $validator->errors()->first());
                         return redirect()->back()->withInput();
                   }
            
                $input = $request->all();
                //return  $input;
                if($request->shipping_location == 0){
                  $input['user_id'] = $order->user_id;
                  $DeliveryLocation = new DeliveryLocation;
                  $id = $DeliveryLocation->create($input)->id;
                  //$id = $savedData->id;
                }else{
                  $DeliveryLocation = DeliveryLocation::find($input['shipping_location']);
                  $DeliveryLocation->update($input);
                }
                
                if($request->shipping_location == 0){
                    $shipping_location['id'] = $id;
                }else{
                    $shipping_location['id'] = $request->shipping_location;
                }
               
                /*$shipping_location['id'] = $request->shipping_location;*/
                $shipping_location['name'] = $request->name;
                 if($request->has('description') && $input['description'] != ''){
                    $shipping_location['description'] = $request->input('description');
                }else{
                    $shipping_location['description'] = $request->input('address');
                }
                $shipping_location['address'] = $request->address;
                $shipping_location['lat'] = $request->lat;
                $shipping_location['lng'] = $request->lng;
              
                $order->update(['shipping_location' => json_encode($shipping_location, true)]);
                if($order->order_status == 'N' || $order->order_status == 'CF' || $order->order_status == 'UP'){
                    $order->update(['order_status'=>'UP']);
                    /*send notification to driver n shopper*/
                    $shopper_id = $order->shopper_id;
                    $driver_id  =  $order->driver_id;
                    $shopperData = User::whereIn('id', [$shopper_id, $driver_id])->select('id','device_type','device_token')->get();
                   
                    $shopper_id_array = collect($shopperData)->where('id', $shopper_id)->pluck('device_token');
                    $driver_id_array = collect($shopperData)->where('id', $driver_id)->pluck('device_token');
                    $shopper_device_type_array = $shopperData->where('id', $shopper_id)->pluck('device_type');
                    $shopper_device_type=$shopper_device_type_array[0];

                    $driver_device_type_array = $shopperData->where('id', $driver_id)->pluck('device_type');
                    $driver_device_type=$driver_device_type_array[0];
                    //echo "<pre>"; print_r($user_id_array); die;
                    $shopperArray = [];
                    $shopperArray['type'] = 'Updated';
                    $shopperArray['title'] = 'Order updated';
                    $shopperArray['body'] = $order->order_code.' '.trans('order.update_success');
                    

                   // print_r($driver_device_type);
                   // die();
                    //shopper notifiction
                    Helper::sendNotification($shopper_id_array ,$shopperArray, $shopper_device_type);
                    //driver notifiction
                    Helper::sendNotification($driver_id_array ,$shopperArray, $driver_device_type);
                   
                }
                //$order =  $this->order->with(['User.deliveryLocation'])->findOrFail($id);
                return redirect()->back()->with('success', trans('order.modify_address_success'));
                //Session::flash('success',trans('order.item_remove_create_success'));

            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());

            }
        }
        return view('admin/pages/order/modify-address',compact(['order','deliveryLocationArray']));
      }else{
         return redirect()->back();
      }

    }

    function modifyDeliveryDateOrSlot(Request $request,$id){
        $order =  $this->order->with(['User'])->find($id);
        if($order->order_status == 'N' || $order->order_status == 'CF' || $order->order_status == 'UP'){
        if($request->ajax()){

            $delivery_date = $request->delivery_date;
            $day = Carbon::createFromFormat('Y-m-d',$request->delivery_date)->format('l');

            $delivaryDay =  DeliveryDay::whereTranslation('name',$day)->with(['deliveryTime'])->first();

            $data =   view('admin/pages/order/ajax/modify-delivery-date-or-slot',compact(['order','day','delivaryDay','delivery_date']))->render();

          return response()->json([
              'data'=>$data
          ],200);
        }
        if($request->isMethod('post')){
            $validator = Validator::make($request->all(),[
                'delivery_date'=>'required',
                'delivery_time_id'=>'required'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            try {
                $deliveryTime = DeliveryTime::find($request->delivery_time_id);
                $order->update(['delivery_date' => $request->delivery_date, 'delivery_time_id' => $request->delivery_time_id, 'delivery_time' => json_encode($deliveryTime, true)]);
                if($order->order_status == 'N' || $order->order_status == 'CF' || $order->order_status == 'UP'){
                    $order->update(['order_status'=>'UP']);
                     $shopper_id = $order->shopper_id;
                    $driver_id  =  $order->driver_id;
                    $shopperData = User::whereIn('id', [$shopper_id, $driver_id])->select('id','device_type','device_token')->get();
                   
                    $shopper_id_array = collect($shopperData)->where('id', $shopper_id)->pluck('device_token');
                    $driver_id_array = collect($shopperData)->where('id', $driver_id)->pluck('device_token');
                    $shopper_device_type_array = $shopperData->where('id', $shopper_id)->pluck('device_type');
                    $shopper_device_type=$shopper_device_type_array[0];

                    $driver_device_type_array = $shopperData->where('id', $driver_id)->pluck('device_type');
                    $driver_device_type=$driver_device_type_array[0];
                    //echo "<pre>"; print_r($user_id_array); die;
                    $shopperArray = [];
                    $shopperArray['type'] = 'Updated';
                    $shopperArray['title'] = 'Order updated';
                    $shopperArray['body'] = $order->order_code.' '.trans('order.update_success');
                    

                   // print_r($driver_device_type);
                   // die();
                    //shopper notifiction
                    Helper::sendNotification($shopper_id_array ,$shopperArray, $shopper_device_type);
                    //driver notifiction
                    Helper::sendNotification($driver_id_array ,$shopperArray, $driver_device_type);
                }
                Session::flash('success',trans('order.item_remove_create_success'));

            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());

            }
        }
        return view('admin/pages/order/modify-delivery-date-or-slot',compact(['order']));
        }else{
         return redirect()->back();
      }

    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function changeShopperAndDriver(Request $request,$id){

        $order =  $this->order->findOrFail($id);
      if($order->order_status == 'N' || $order->order_status == 'CF' || $order->order_status == 'UP'){
        $shoper=$this->user->where(['user_type'=>'shoper','role'=>'user'])->get()->pluck('full_name','id');
        $driver=$this->user->where(['user_type'=>'driver','role'=>'user'])->get()->pluck('full_name','id');
        if($request->isMethod('post')){

            try {
                $order->fill(['driver_id' => $request->driver_id,'shopper_id'=>$request->shopper_id])->update();
                 if($order->order_status == 'N' || $order->order_status == 'CF' || $order->order_status == 'UP'){
                    $order->update(['order_status'=>'UP']);
                     $shopper_id = $order->shopper_id;
                    $driver_id  =  $order->driver_id;
                    $shopperData = User::whereIn('id', [$shopper_id, $driver_id])->select('id','device_type','device_token')->get();
                   
                    $shopper_id_array = collect($shopperData)->where('id', $shopper_id)->pluck('device_token');
                    $driver_id_array = collect($shopperData)->where('id', $driver_id)->pluck('device_token');
                    $shopper_device_type_array = $shopperData->where('id', $shopper_id)->pluck('device_type');
                    $shopper_device_type=$shopper_device_type_array[0];

                    $driver_device_type_array = $shopperData->where('id', $driver_id)->pluck('device_type');
                    $driver_device_type=$driver_device_type_array[0];
                    //echo "<pre>"; print_r($user_id_array); die;
                    $shopperArray = [];
                    $shopperArray['type'] = 'Updated';
                    $shopperArray['title'] = 'Order updated';
                    $shopperArray['body'] = $order->order_code.' '.trans('order.update_success');
                    

                   // print_r($driver_device_type);
                   // die();
                    //shopper notifiction
                    Helper::sendNotification($shopper_id_array ,$shopperArray, $shopper_device_type);
                    //driver notifiction
                    Helper::sendNotification($driver_id_array ,$shopperArray, $driver_device_type);
                }
                Session::flash('success',trans('order.item_remove_create_success'));
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());

            }
        }
        return view('admin/pages/order/change-shopper-and-driver',compact(['shoper','driver','order']));
        }else{
        return redirect()->back();
      }

    }

    public function addDiscount(Request $request,$id){

        $order =  $this->order->findOrFail($id);
        if($order->order_status == 'N' || $order->order_status == 'CF' || $order->order_status == 'UP'){
        if($request->isMethod('post')){
           //return 'hi';
            try {
                $order->update(['admin_discount' => $request->admin_discount/*,
                'total_amount' => ($order->total_amount + $order->admin_discount) - $request->admin_discount*/]);
               /* $order->update(['admin_discount' => $request->admin_discount,
                'total_amount' => ($order->total_amount + $order->admin_discount) - $request->admin_discount]);*/
                if($order->order_status == 'N' || $order->order_status == 'CF' || $order->order_status == 'UP'){
                    $order->update(['order_status'=>'UP']);
                     $shopper_id = $order->shopper_id;
                    $driver_id  =  $order->driver_id;
                    $shopperData = User::whereIn('id', [$shopper_id, $driver_id])->select('id','device_type','device_token')->get();
                   
                    $shopper_id_array = collect($shopperData)->where('id', $shopper_id)->pluck('device_token');
                    $driver_id_array = collect($shopperData)->where('id', $driver_id)->pluck('device_token');
                    $shopper_device_type_array = $shopperData->where('id', $shopper_id)->pluck('device_type');
                    $shopper_device_type=$shopper_device_type_array[0];

                    $driver_device_type_array = $shopperData->where('id', $driver_id)->pluck('device_type');
                    $driver_device_type=$driver_device_type_array[0];
                    //echo "<pre>"; print_r($user_id_array); die;
                    $shopperArray = [];
                    $shopperArray['type'] = 'Updated';
                    $shopperArray['title'] = 'Order updated';
                    $shopperArray['body'] = $order->order_code.' '.trans('order.update_success');
                    

                   // print_r($driver_device_type);
                   // die();
                    //shopper notifiction
                    Helper::sendNotification($shopper_id_array ,$shopperArray, $shopper_device_type);
                    //driver notifiction
                    Helper::sendNotification($driver_id_array ,$shopperArray, $driver_device_type);
                }
                Session::flash('success',trans('order.add_discount'));
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
            }
        }
        return view('admin/pages/order/add-discount',compact(['order']));
          }else{
         return redirect()->back();
      }

    }

     public function changeStatus(Request $request){
      
            $order= $this->order->findOrFail($request->id);
            $order->fill(['order_status'=>$request->status])->save();
            //$order->user->notify(new OrderStatus($order));

            if($request->ajax()){
                if($order){
                    //print_r($order); die();
                    if($request->status == 'C'){
                        $user = User::find($order->user_id);

                        $client = new Client();
                        $authkey = env('AUTHKEY');
                        $phone_number = $user->phone_number;
                        $senderid = env('SENDERID');
                        $message = "Dear Customer, Your order#".$order->order_code." has been Cancelled.";
                        $message = urlencode($message);
                          
                        $response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91");

                        $statusCode = $response->getStatusCode();
                        
                        $wallet_result = $this->user_wallet->where(['order_id'=>$order->id,'type'=>'Order Payment'])->first();
                        if(!empty($wallet_result)){                
                          $amount =  $wallet_result->amount;
                          $transaction_type = "CREDIT";
                          $type = "Order Cancelled Cashback";
                          $transaction_id = "DAR".time().$order->id;
                          $description ="Order cancelled cashback refunded to wallet";
                          $json_data = json_encode(['order_id'=>$order->id]); 
                          $order_id = $order->id; 
                          $user_wallet = Helper::updateCustomerWallet($order->user_id,$amount,$transaction_type,$type,$transaction_id,$description,$json_data,$order_id); 
                        }


                        $wallet_result = $this->user_wallet->where(['order_id'=>$order->id,'type'=>'Order Total Bonus'])->first();
                        if(!empty($wallet_result)){                
                          $amount =  $wallet_result->amount;
                          $transaction_type = "DEBIT";
                          $type = "Order Cancelled Cashback";
                          $transaction_id = "DAR".time().$order->id;
                          $description ="Order cancelled cashback refunded to wallet";
                          $json_data = json_encode(['order_id'=>$order->id]); 
                          $order_id = $order->id; 
                          $user_wallet = Helper::updateCustomerWallet($order->user_id,$amount,$transaction_type,$type,$transaction_id,$description,$json_data,$order_id); 
                        }

                        $updatestock = $this->productOrderItem->where("order_id", $request->id)->get();
                
                        //print_r($updatestock); die();

                        if(!empty($updatestock)){
                          foreach ($updatestock as $value) {
                            $vendorProduct = $this->vendorProduct->where('id',$value->vendor_product_id)->first();
                            $vendorProduct->update(['qty' => DB::raw('qty+'.$value->qty)]); 
                          }
                        }
                    }
                    if($request->status == 'D'){
                        $orders = ProductOrder::find($request->id);
                        if(!empty($orders)){
                            //$orders->transaction_id = 'pay_'.Helper::generateRandomString(10);
                            $orders->transaction_status = '1';
                            $orders->save();
                        }

                        $user = User::find($order->user_id);
                        $SiteSetting = SiteSetting::first();
                        try{
                            if (!empty($user->referral_code) && $user->referral_used != 1) {
                                $check_user_reffer  = $this->user->withTrashed()->where(['referral_code' => $user->referral_code])->first();
                                $refferalCound = $this->user->withTrashed()->where(['referred_by' => $check_user_reffer->id])->count();
                                if (!empty($refferalCound) && $refferalCound >= 3) {
                                    Log::error('Refferal code limit used');
                                }
                    
                                if (!empty($check_user_reffer)) {
                                    /*===This is for old user which is reffering==*/
                                    $amount =  $SiteSetting->referred_by_amount;
                                    $transaction_type = "CREDIT";
                                    $type = "Referral Amount";
                                    $transaction_id = rand(100000, 999999);
                                    $description = "Your referral amount Wallet Recharge";
                                    $json_data = json_encode(['refuser' => $check_user_reffer->id]);
                                    Helper::updateCustomerCoins($check_user_reffer->id, $amount, $transaction_type, $type, $transaction_id, $description, $json_data);
                                    /*===This is for old user which is reffering==*/
                                } else {
                                    Log::error('Worng Refferal code used');
                                }

                                $referral_amount =  $SiteSetting->referral_amount;
                                $transaction_type = "CREDIT";
                                $type = "Referral Amount";
                                $transaction_id = rand(100000, 999999);
                                $description = "Your referral amount Wallet Recharge";
                                $json_data = json_encode(['refuser' => $user->id]);
                                Helper::updateCustomerCoins($user->id, $referral_amount, $transaction_type, $type, $transaction_id, $description, $json_data);
                                
                                
                                $user->referral_used = 1;
                                $user->save();
                            }

                            

                            
                        }catch(\Exception $e){
                            Log::error($e->getMessage());
                        }
                        $client = new Client();
                        $authkey = env('AUTHKEY');
                        $phone_number = $user->phone_number;
                        $senderid = env('SENDERID');
                        $message = "Dear Customer, Your order#".$order->order_code." has been Delivered. Thanks for the order.";
                        $message = urlencode($message);
                          
                        $response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91");

                        $statusCode = $response->getStatusCode();
                    }
                    return response()->json([
                        'status' => true,
                        'message' => 'update'
                    ],200);
                }else{
                    return response()->json([
                        'status' => false,
                        'data' =>$order,
                        'message' => 'some thing is wrong'
                    ],400);
                }
            }
        }

    public function exportOrder($type)
    {
        $orders = $this->order->select(['id', 'order_code', 'user_id', 'zone_id', 'vendor_id', 'shopper_id', 'driver_id', 'vendor_product_id', 'order_status', 'delivery_time_id', 'delivery_date', 'payment_mode_id', 'delivery_charge', 'tax', 'total_amount', 'offer_total', 'admin_discount', 'transaction_id', 'transaction_status', 'cart_id', 'deleted_at', 'created_at', 'updated_at'])->get();

       // dd($orders);
        return Excel::create('orders', function($excel) use ($orders) {

            $excel->sheet('order', function($sheet) use ($orders)
            {

                $sheet->fromArray($orders);

            });

        })->export($type);


    }
    
    
    	public function showDetail($id)
    	{
    		$orders_details = $this->order->with(['ProductOrderItem','zone','vendor','driver','shopper','User'])->findOrFail($id);
    		
    		$phone_array=array('driver'=>$orders_details->driver->phone_number,'shopper'=>$orders_details->shopper->phone_number,'user'=>$orders_details->user->phone_number);
    		return  response()->json([
    		'status' => true,
    		'data'=>$phone_array,

    		],200);

    	}
        
        
        public function invoice($id)
        {
    		
    	$orders_details = $this->order->with(['ProductOrderItem','vendor','driver','shopper','User','zone','PaymentMode'])->findOrFail($id);
      if(isset($orders_details)){
        $user = User::where('id',$orders_details->user_id)->first();
      }
      
     // echo "<pre>"; print_r($orders_details);
    	return view('admin/pages/order/invoice')->with('orders_details',$orders_details)->with('id',$id)->with('user',$user); 
            
        }
        
    	public function pdfdownload($id)
    	{
        
    		$orders_details = $this->order->with(['ProductOrderItem','vendor','driver','shopper','User','zone','PaymentMode'])->findOrFail($id);
     
        
        $pdf = PDF::loadView('admin.pages.order.pdfdownload', compact('orders_details'));
        $path_to_pdf= "public/invoices/".$orders_details->order_code."#invoice.pdf";
        //Storage::put($path_to_pdf, $pdf->output());
        file_put_contents($path_to_pdf, $pdf->output());
        $image = new Imagick($path_to_pdf);
        $image->resetIterator(); 
        $image->setResolution( 500, 500 );
        $ima = $image->appendImages(true); 

        $ima->setImageFormat( "jpeg" );

        $new_file_name_image=$orders_details->order_code."#invoice.jpeg";
        header("Content-Type: image/jpeg");
        header("Cache-Control: no-store, no-cache");  
        header('Content-Disposition: attachment; filename="'.$new_file_name_image.'"');
        echo $ima; 
        exit;


 
    		//$pdf = PDF::loadView('admin.pages.order.pdfdownload', compact('orders_details'));
    		//return $pdf->download('invoice.pdf');
    		//return view('admin/pages/order/pdfdownload')->with('orders_details',$orders_details); 

    	}

      /*order heatmap for deliverred orders*/

      public function orderHeatmap()
      {
        $heatMapArray = [];
        $ordersList = $this->order->where('order_status','D')->select(['id', 'order_code','order_status', 'user_id'])
        ->with(['User'])->get();
         //
        $i=1;
        if (isset($ordersList)) {
        foreach ($ordersList as $key => $value) {
          if( $value->user->lat != '' && $value->user->lng != '' && is_numeric($value->user->lat)  && is_numeric($value->user->lng)  ){
            $heatMapArray[$i]['lat'] = $value->user->lat;
            $heatMapArray[$i]['lng'] = $value->user->lng;
            $heatMapArray[$i]['user_id'] = $value->user->id;
          }
          $i++;
        }
        //echo "<pre>"; print_r($heatMapArray);die;
        //reindexed array
        if(!empty($heatMapArray)){
             $heatMapArray = array_combine(range(1, count($heatMapArray)), array_values($heatMapArray));
        }
        }
       
        
        //return  $heatMapArray;
        return view('admin/pages/tracking/heatmap')->with('heatMapArray',$heatMapArray);
      }

      public function trackOrder(request $request, $id)
      {
        
        $driver_id = '';
        $user_id  = '';
        $customerLat = '';
        $customerLng = '';
        $shopperLat = '';
        $shopperLng = '';
        $curlat = '';
        $curlng = '';
        $driver_name = '';
        $orderdata = $this->order->findOrFail($id);

        if(isset($orderdata)){
          $driver_id = $orderdata->driver_id;
          $user_id = $orderdata->user_id;
          $shopper_id = $orderdata->shopper_id;
        }
        $userData = User::select('lat','lng','current_lat','current_lng','id','name')->whereIn('id',[$driver_id,$user_id,$shopper_id ])->get();

        if(isset($userData)){
          //print_r($orderdata->shipping_location);

          $shippingLocation = $orderdata->shipping_location;
          $userData = $userData->keyBy('id');
          $curlat = $userData[$driver_id]->current_lat;
          $curlng = $userData[$driver_id]->current_lng;
          $driver_name = $userData[$driver_id]->name;
          $customerLat = $shippingLocation->lat;
          $customerLng = $shippingLocation->lng;
          $shopperLat = $userData[$shopper_id]->current_lat;
          $shopperLng = $userData[$shopper_id]->current_lng;
        }

        return view('admin/pages/order/track', compact('curlat','curlng','customerLat','customerLng','shopperLat','shopperLng','driver_id','driver_name'));
      }

       public function trackDriverCurrentCoordinates(request $request)
      {
        $driver_id = '';
        $curlat = '';
        $curlng = '';
        $array=array();
        $driver_id = $request->id;
  
        $userData = User::select('current_lat','current_lng')->whereIn('id',[$driver_id])->get();

        if(isset($userData)){
          $curlat = $userData[0]->current_lat;
          $curlng = $userData[0]->current_lng;
        }
     
        $array[0]['latitude']=$curlat;
        $array[0]['longitude']=$curlng;
        //$array1=json_encode($array);
        return response()->json($array);
        //echo $array1;
      }
      
    

}
