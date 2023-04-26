<?php

namespace App\Http\Controllers\Admin;


use App\City;
use App\Category;
use App\CountryPhoneCode;
use App\DeliveryDay;
use App\DeliveryLocation;
use App\DeliveryTime;
use App\Helpers\Helper;
use App\Notifications\OrderStatus;
use App\ProductOrderItem;
use App\Scopes\StatusScope;
use App\MeasurementClass;
use App\ProductOrder;
use App\Traits\ResponceTrait;
use App\User;
use App\VendorProduct;
use App\Zone;
use App\SlotTime;
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


class ManualOrderController extends Controller
{

   
    use ResponceTrait;
    protected $user;
    protected $zone;
    protected $productOrder;
    protected $countryPhoneCode;
    protected $method;
    function __construct(Request $request,User $user, Zone $zone,CountryPhoneCode $countryPhoneCode,ProductOrder $productOrder,MeasurementClass $measurementClass)
    {

        parent::__construct();
        $this->user=$user;
        $this->productOrder=$productOrder;
        $this->zone=$zone;
        $this->measurementclass=$measurementClass;
        $this->countryPhoneCode=$countryPhoneCode;
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->user->can('manualOrder', ProductOrder::class)) {
          return abort(403,'not able to access');
        }
        $zones=$this->zone->get()->pluck('name','id');
        $countryPhoneCode=$this->countryPhoneCode->get()->pluck('phonecode','phonecode');
        $zones=$this->zone->get()->pluck('name','id');
        $shoppers=$this->user->where(['user_type'=>'shoper','role'=>'user'])->get()->pluck('full_name','id');
        $drivers=$this->user->where(['user_type'=>'driver','role'=>'user'])->get()->pluck('full_name','id');
        $vendors=$this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');
        return view('admin/pages/manual-order/show',compact(['zones','shoppers','drivers','vendors','countryPhoneCode']));
        
    }

    public function store(Request $request)
    {
        //return $shopper_id_array;
        $validator = Validator::make(
            $request->all(),
            [
                'product_qtys'=>'required',
                'delivery_charge'=>'required',
                'user_id'=>'required',
                'zone_id'=>'required',
                'vendor_id'=>'required',
                'driver_id'=>'required',
                'shopper_id'=>'required',
                'admin_discount'=>'required',
                'delivery_time_id'=>'required',
                'delivery_date'=>'required',
            ]
        );

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }
        $cartRec = VendorProduct::with(['product','Product.image'])->whereIn('id',$request->product_ids)->get();

        ///$cartRec;
        $result =[];
        $error=0;

        try {
            foreach ($cartRec as $Rec) {
				
                $trmpArray = [];
                $qty  = $request->product_qtys[$Rec->id];
                if ($Rec['qty'] >=$qty) {

                    $trmpArray['price'] = $Rec['price'] * $qty;
                    $trmpArray['qty'] = $qty;
                    $trmpArray['vendor_product_id'] = $Rec['id'];
                    $trmpArray['offer_total'] = $Rec['offer_price'] * $qty;
                    $trmpArray['message'] = $Rec['Product']['name'] . trans('site.out_of_stock').' Max quantity is '.$Rec['qty'];
                    $trmpArray['is_offer']='no';
                    $trmpArray['offer_data'] = json_encode(array());
                    if($Rec['is_offer']){
                        $trmpArray['offer_value']=$Rec['offer']['offer_value'];
                        $trmpArray['offer_type']=$Rec['offer']['offer_type'];
                        $trmpArray['offer_data']=json_encode($Rec['offer']);
                        $trmpArray['is_offer']='yes';
                        $trmpArray['price'] = $Rec['offer_price'] * $qty;
                    }
                    
                    $newstock=$this->measurementclass->where(['id'=>$Rec['Product']['measurement_class']])->get()->toArray();
                  
                    $Rec['measurementclass'] =$newstock[0]['name'];
                   
                    
                    
                    
                    $trmpArray['data']=json_encode(['vendor_product'=>$Rec]);
                    $trmpArray['status'] = 1;
                } else {
                    $trmpArray['total'] = 0;
                    $trmpArray['offer_total'] = 0;
                    $trmpArray['message'] = $Rec['Product']['name'] . trans('site.out_of_stock').' Max quantity is '.$Rec['qty'];
                    $trmpArray['status'] = 0;
                    $error = 1;
                }

                $result[] = $trmpArray;
            }
            
            
           // print_r($result);die;
            

        } catch (\Exception $e) {
            return $this->notFoundResponse($e);
        }
		
		
        if($error){

            return $this->outOfStockResponse(collect($result)->where('status','=',0)->first());
        }else{

            DB::beginTransaction();

            try {

               
                $delivery_charge = $request->delivery_charge;
                $tax = 0;
                $sub_total = collect($result)->sum('price');
                $offer_total = collect($result)->sum('offer_total');
                
                $input = [];
                $input['user_id'] = $request->user_id;
                $input['zone_id'] = $request->zone_id;
                $input['vendor_id'] = $request->vendor_id;
                $input['shopper_id'] = $request->shopper_id;
                $input['driver_id'] = $request->driver_id;
                $input['order_status'] = 'N';
                $input['cart_id'] = json_encode(array());
                $input['order_code'] = Helper::orderCode($request->delivery_date,$request->zone_id,$request->delivery_time_id);
                $shipping_location = DeliveryLocation::with(['region'])->findOrFail($request->delivery_address_id);
                if(!empty($shipping_location)){
                    $input['shipping_location'] = $shipping_location->toJson();
                }else{
					$input['shipping_location'] ="";
			 }

                $to_day = Carbon::createFromFormat('Y-m-d',$request->delivery_date)->format('l');
                $today_data = $this->zone->find($request->zone_id)->weekPackage->$to_day->getSlotTimes()->first(function ($today_data) use($request,$to_day) {
                    $today_data['name']=$to_day;
                    return $today_data->id==$request->delivery_time_id;
                });
                
                /*if (isset($request->delivery_charge) && !empty($request->delivery_charge)) {
					$sub_total = $sub_total + $request->delivery_charge;
				}
				
                if (isset($request->promo_discount) && !empty($request->promo_discount)) {
					$sub_total = $sub_total - $request->promo_discount;
				}
				
                if (isset($request->admin_discount) && !empty($request->admin_discount)) {
					$sub_total = $sub_total - $request->admin_discount;
				}*/

                $input['delivery_time'] = json_encode($today_data,true);
                $input['delivery_charge'] = $delivery_charge;
                $input['admin_discount'] = $request->admin_discount;
                $input['promo_discount'] = $request->promo_discount;
                $input['tax'] = $tax;
                $input['total_amount'] = $sub_total;
                $input['offer_total'] = $offer_total;
                $input['delivery_time_id'] = $request->delivery_time_id;
                $input['delivery_date'] = $request->delivery_date;
                $input['payment_mode_id'] = '1';
                $input['transaction_id'] = null;
                $input['transaction_status'] = '0';
                //echo json_encode($result);die;
                $order = $this->productOrder->create($input);
                $order->ProductOrderItem()->createMany($result);
                $user_id_array1 = User::whereIn('id', [$request->user_id, $request->driver_id, $request->shopper_id])->select('id','device_type','device_token')->get();
                $shopper_id_array = $user_id_array1->where('id', $request->shopper_id)->pluck('device_token');
                 $driver_id_array = $user_id_array1->where('id', $request->driver_id)->pluck('device_token');
                $user_id_array = $user_id_array1->where('id', $request->user_id)->pluck('device_token');
                $shopper_device_type_array =  $user_id_array1->where('id', $request->shopper_id)->pluck('device_type');
                $shopper_device_type = $shopper_device_type_array[0];
                
                $driver_device_type_array = $user_id_array1->where('id', $request->driver_id)->pluck('device_type');
                 
                $driver_device_type = $driver_device_type_array[0];
             
                $user_device_type_array = $user_id_array1->where('id', $request->user_id)->pluck('device_type');
                $user_device_type=$user_device_type_array[0];
                $shopperArray = [];
                $shopperArray['type'] = 'Order';
                $shopperArray['product_type'] = 'new order';
                $shopperArray['title'] = 'New order placed';
                $shopperArray['body'] = trans('order.create_success_ordercode').$order->order_code;
                $dataArray = [];
                $dataArray['type'] = 'Order';
                $dataArray['product_type'] = 'New';
                $dataArray['title'] = 'New Order';
                $dataArray['body'] = trans('order.order_confirmed').$order->order_code;
               
                $data  = [];
                $data['order_code']  = $order->order_code;
                $data['order_id']  = $order->id;
                $data['msg']  = 'The Order placed successfully';
                
                /*$order->with('ProductOrderItem')->findOrFail($order->id);*/
                //echo json_encode($result);die;
                foreach ($result as $res){
                    VendorProduct::where(['id'=>$res['vendor_product_id']])->decrement('qty',$qty);
                }
                DB::commit();
                    //customer notifiction
                Helper::sendNotification($user_id_array ,$dataArray, $user_device_type);
                    //shopper notifiction
                Helper::sendNotification($shopper_id_array ,$shopperArray, $shopper_device_type);
                   //driver notifiction
                Helper::sendNotification($driver_id_array ,$shopperArray, $driver_device_type);
                

                return $this->listResponse($data);
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e->getMessage());
            }
        }


    }



    public function modifyAddress(Request $request,$id){
        $order =  $this->order->with(['User.deliveryLocation'])->findOrFail($id);
        if($request->isMethod('post')){

            try {
                
                $shipping_location = collect($order->shipping_location)->toArray();
                $shipping_location['id'] = $request->shipping_location;
                $shipping_location['name'] = $request->name;
                $shipping_location['address'] = $request->address;
                $shipping_location['lat'] = $request->lat;
                $shipping_location['lng'] = $request->lng;
                $order->update(['shipping_location' => json_encode($shipping_location, true)]);
                Session::flash('success',trans('order.item_remove_create_success'));

            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());

            }
        }
        return view('admin/pages/order/modify-address',compact(['order']));

    }

    public function deliveryDay(Request $request){

        $dataArray = [];

        $today_date = Carbon::createFromFormat('Y-m-d',$request->date);

        $to_day = $today_date->format('l');

        $zone = $this->zone->find($request->id);

        $today_data = $zone->weekPackage->$to_day->getSlotTimes()->map(function ($today_data)use($today_date) {
            $today_data['no_of_order']=ProductOrder::where(['delivery_time_id'=>$today_data->id,'delivery_date'=>$today_date->format('Y-m-d')])->count();
            return $today_data;
        });

        $dataArray=
            ['name'=>$to_day,'date'=>$today_date->format('Y-m-d'),'delivery_time'=>$today_data];
        $response = [
            'code' => 0,
            'error' => false,
            'message'=>trans('site.success'),
            'data' => $dataArray,
        ];
        return response()->json($response, 200);
        // return $this->listResponse($result);
    }

    public function getVendorProduct(Request $request){
        $user = $this->user->find($request->vendor_id);
        $vendorProduct = $user->newVendorProduct()->with(['product','Product.image'])->where('qty','>',0)->get();
        $response = [
            'code' => 0,
            'error' => false,
            'message'=>trans('site.success'),
            'data' => $vendorProduct,
        ];
        return response()->json($response, 200);
    }


}
