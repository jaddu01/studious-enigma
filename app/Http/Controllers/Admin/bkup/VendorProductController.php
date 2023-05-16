<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Input;
use App\Category;
use App\Helpers\Helper;
use App\Offer;
use App\Product;
use App\Scopes\StatusScope;
use App\User;
use App\VendorProduct;
use App\ProductTranslation;
use App\MeasurementClass;
use App\ProductOrder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;
use Redirect;


class VendorProductController extends Controller
{
    protected $product;
    protected $user;
    protected $offer;
    protected $category;
    protected $vendorProduct;
    protected $method;
    function __construct(Request $request,Product $product,VendorProduct $vendorProduct,User $user,Offer $offer,Category $category,ProductOrder $productorder)
    {
        parent::__construct();
        $this->product=$product;
        $this->user=$user;
        $this->offer=$offer;
        $this->productorder=$productorder;
        $this->category=$category;
        $this->vendorProduct=$vendorProduct;
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id=null)
    {
		
		//echo date("2018-12-11", time() + 86400);
		
        if ($this->user->can('view', VendorProduct::class)) {
            return abort(403,'not able to access');
        }
        $users=$this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');
        //print_r($users);
        //die;
        $categories=$this->category->get()->pluck('name','id');
        return view('admin/pages/vendor-product/index',compact(['user_id','users','categories']));
    }
    
    
        
    
    /** shopper assignment*/
    
        public function shopperassignment(Request $request)
    {

        $driverId = '';
		

		$driverList=$this->user->where(['user_type'=>'driver','role'=>'user'])->get()->pluck('full_name','id');
		 
		$users =   $this->productorder->with(['shopper'])->groupBy('shopper_id')->get();
		//return $users[0]->driver_id;
        if ($request->isMethod('post')) {
            $input = $request->all();
        //return $input['date'];
            $users =   $this->productorder->whereDate('delivery_date','=',$input['date'])->with(['shopper'])->groupBy('shopper_id')->get();
            
        }
        if(isset($users)){
            $driverId = $users[0]->driver_id;
        }
		$order_array= array();
		foreach($users as $orderdata){
			// echo $orderdata->shopper_id;
			 
			$order_array['id']=$orderdata->shopper_id;
			
			$order_array['name']=$orderdata->shopper->name;
			if( $request->date){  $date=$request->date;   }else{ $date= date('Y-m-d');}
		      $order_details=   $this->productorder->with(['driver'])->where(['shopper_id'=>$orderdata->shopper_id])->Where('created_at', 'LIKE', "%{$date}%") ->get();
			 $order_array['driver']=array();
			 foreach($order_details as $details){
//echo "<pre>";
		//print_r($details->toArray());die;
				 
				$result_array = array();
				$result_array['order_code']=$details->order_code;
				$result_array['name']=$details->driver->name; 
				$result_array['id']=$details->driver->id; 
                $result_array['order_id']=$details->id; 
				$result_array['delivery_time'] = $details->delivery_time->from_time.'-'.$details->delivery_time->to_time; 
				$result_array['total_amount']=$details->total_amount; 
				$result_array['total_order']=$details->delivery_time->total_order; 
				$result_array['order_status']=$details->order_status; 
				$result_array['assigned_status']=$details->assigned_status;  
				$result_array['action']='<a href="'.route("order.show",$details->id).'" class="btn btn-success">Order</a><a onclick="changeDriver('.$details->id.')" class="btn btn-success">Change Driver/Shopper</a>'; 
				 
			$order_array['driver'][] = $result_array;
				
				 }

			  $newArray[]=$order_array;
		 }
		 $new_list_array[]= $newArray;

        return view('admin/pages/vendor-product/shopperassignment',compact(['newArray','driverList','driverId']));
    }
    
    /** driver assignment*/
    
        public function driverassignment(Request $request)
    {
		if ($request->isMethod('post')) {
		
		}
		 
		$users=   $this->productorder->with(['driver'])->groupBy('driver_id')->get();
		
		
	//	 echo "<pre>";
	//	print_r($users->toArray());die;
	
		
		
		 $shopperList=$this->user->where(['user_type'=>'shoper','role'=>'user'])->get()->pluck('full_name','id');
		 $order_array= array();
		 foreach($users as $orderdata){
			// echo $orderdata->shopper_id;
			 
			 $order_array['id']=$orderdata->driver_id;
			
			 $order_array['name']=$orderdata->driver->name;
			 if( $request->date){  $date=$request->date;   }else{ $date= date('Y-m-d');}
			 
			 
		$order_details=   $this->productorder->with(['shopper'])->where(['driver_id'=>$orderdata->driver_id])->Where('created_at', 'LIKE', "%{$date}%") ->get();
			$order_array['driver']=array();
			 foreach($order_details as $details){
		//	  echo "<pre>";
	//	print_r($details->toArray());die;
				 
				$result_array = array();
				 $result_array['order_code']=$details->order_code;
				$result_array['name']=$details->shopper->name; 
				$result_array['id']=$details->shopper->id; 
                $result_array['order_id']=$details->id; 
				$result_array['delivery_time']=$details->delivery_time->from_time.'-'.$details->delivery_time->to_time; 
				$result_array['total_amount']=$details->total_amount; 
				$result_array['total_order']=$details->delivery_time->total_order; 
				$result_array['order_status']=$details->order_status; 
				$result_array['assigned_status']=$details->assigned_status; 
				$result_array['action']='<a href="'.route("order.show",$details->id).'" class="btn btn-success">Order</a><a onclick="changeShoper('.$details->id.')" class="btn btn-success">Change Driver/Shopper</a>'; 
				 
			     $order_array['driver'][] = $result_array;
				
				 }

			  $newArray[]=$order_array;
		 }
		 $new_list_array[]= $newArray;

        return view('admin/pages/vendor-product/driverassignment',compact(['newArray','shopperList']));
    }
    
    
    
    
    public function getDriverShopper(request $request){
        $orderId = $request->id;
        $users = $this->productorder->where('id', $orderId)->select('driver_id','shopper_id')->first();
        if (isset($users) && !empty($users)) {
            return response()->json(['status' => 'true', 'data' => $users]);

        }else{
            return response()->json(['status' => 'false', 'data' => []]);
        }
    }
    
    
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $current_time = date("Y-m-d");
        $offerValue = [];
        if ($this->user->can('create', VendorProduct::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->vendorProduct->rules('POST'));
    // echo "<pre>";  print_r($validator->toArray());die;
        $users=$this->user->where(['user_type'=>'vendor','role'=>'user'])->pluck('name','id');
        $products=$this->product->listsTranslations('name','id')->pluck('name','id')->all();


        
        $offres = $this->offer->whereRaw('to_time >= CAST( "'.$current_time.'" AS DATE )')->listsTranslations('name','id')->pluck('name','id')->all();
        $offerValue = $this->offer->whereRaw('to_time >= CAST( "'.$current_time.'" AS DATE )')->where('offer_type','amount')->pluck('offer_value','id');
        return view('admin/pages/vendor-product/add')->with('users',$users)->with('validator',$validator)->with('products',$products)->with('offres',$offres)->with('offerValue',$offerValue);
    }


    /*get offer value on change of offer*/
     public function getOfferValue(Request $request)
    {
        $offerId = $request->id;
        $offres = [];
        $offres = $this->offer->where('id',$offerId)->select('offer_value','offer_type')->first();
        if(isset($offres) && !empty($offres)) {
            return response()->json(['status' => 'true', 'data' => $offres]);
        }else{
            return response()->json(['status' => 'false', 'data' => []]);
        }
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($request->all(),$this->vendorProduct->rules($this->method),$this->vendorProduct->messages($this->method));

        if ($validator->fails()) {

            Session::flash('danger',$validator->errors()->first());
            return redirect('admin/vendor-product/create')->withErrors($validator)->withInput();
        }else{

            DB::beginTransaction();
            try {

                $this->vendorProduct->create($input);
                DB::commit();

                Session::flash('success','product created successfully');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
                DB::rollBack();

            }

            return back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $offerName = '';
        $measurementName  = '';
        $product=$this->vendorProduct->with(['Product.images','User','Product.MeasurementClass'])->findOrFail($id);
        
        if(isset($product->offer_id)){
            $offer = $this->offer->where('id',$product->offer_id)->first();
            $offerName = $offer->name;
        }
        if(isset($product->product->measurement_class)){
            $measurement = MeasurementClass::where('id',$product->product->measurement_class)->first();
           
            $measurementName = $measurement->name;
             //return $measurementName;
        }
        
        $related_products = $this->product->whereIn('id',$product->Product->related_products)->get();

        return view('admin/pages/vendor-product/show')->with('product',$product)->with('related_products',$related_products)->with('offerName',$offerName)->with('measurementName',$measurementName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $current_time = date("Y-m-d");
        $offerAmt = [];
        $offer = $this->offer->select('id','offer_value')->where('offer_type', 'amount')->get();

        if(isset($offer)){
            $offerAmt = $offer->pluck('offer_value','id'); 
        }
        $validator = JsValidatorFacade::make($this->vendorProduct->rules('PUT'));
        $product = $this->vendorProduct->with('Offer')->findOrFail($id);
        //return $product;
        $users=$this->user->where(['user_type'=>'vendor','role'=>'user'])->pluck('name','id');
        $products=$this->product->listsTranslations('name','id')->pluck('name','id')->all();
        $offres=$this->offer->whereRaw('to_time >= CAST( "'.$current_time.'" AS DATE )')->listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/vendor-product/edit')->with('product',$product)->with('users',$users)->with('validator',$validator)->with('products',$products)->with('offres',$offres)->with('offerAmt',$offerAmt);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $input = $request->all();
        $offerId = $request->offer_id;
        $offers = [];
        $offers = $this->offer->where('id',$offerId)->select('offer_value','offer_type')->first();
       
        $validator = Validator::make($request->all(),$this->vendorProduct->rules($this->method),$this->vendorProduct->messages($this->method));
         if(isset($offers) && !empty($offers)) {
            if($offers->offer_type ==  'amount'){
                if((int)$request->price < (int)$offers->offer_value){
                    $validator->getMessageBag()->add('price', 'Price can not be less than offer price');    
                    return Redirect::back()->withErrors($validator)->withInput();
                }
            }
        }
        //if($request->input())

        if ($validator->fails()) {

            Session::flash('danger',$validator->errors()->first());
            return redirect('admin/vendor-product/create')->withErrors($validator)->withInput();
        }else{

            DB::beginTransaction();
            try {
                $product = $this->vendorProduct->FindOrFail($id);
                $product->update($input);
                DB::commit();
                Session::flash('success','product updated successfully');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
                DB::rollBack();
            }

            return back();

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $flight = $this->vendorProduct->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $flight->delete();
        //$flight->deleteTranslations();
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

       $product = $this->vendorProduct->with(['User','Offer','Product','Product.translations','Product.MeasurementClass','Product.MeasurementClass.translations'])->select('*');

        if($request->has('user_id') and !empty($request->user_id)){

            $product->where(['user_id'=>$request->user_id]);
        }
        if($request->has('is_offer') and !empty($request->is_offer)){
            if($request->is_offer=='n'){
                $product->where('offer_id','=','null');
            }else{
                $product->where('offer_id','!=','null');
            }

        }
        if($request->has('category_id') and !empty($request->category_id)){

            $product->whereHas('Product', function ($query) use($request) {
                    $query->whereRaw("FIND_IN_SET($request->category_id,category_id)");
            });
        }
        if ($request->has('to_date') and !empty($request->to_date)) {
            $product->whereDate('created_at','>=',$request->to_date." 00:00:00");
        }
        if ($request->has('from_date') and !empty($request->from_date)) {
		
            $product->whereDate('created_at','<=',$request->from_date." 23:59:59");
        }
        if ($request->has('unavailable') and !empty($request->unavailable) and $request->unavailable==1) {
            $product->where('qty','=',0);
        }


        $product->get()->toArray();
        //return  $product;
       //print_r($product->get()->toArray());
       //echo json_encode("sdf");
      // die;
        return Datatables::of($product)           
            ->addColumn('category_id',function ($product){
                $name='';
                // print_r($product->category_id);die;
                 $categories  = Category::whereIn('id',$product->product->category_id)->get();
                 foreach ($categories as $key=>$category){
                     $name.=++$key.') '.$category->name.'</br>';
                }
                return $name;
            })
            ->addColumn('user.full_name',function ($product){                
                return (isset($product->user->full_name) ? $product->user->full_name:'---');
            })
          
            ->filterColumn('product_id', function($query, $keyword) {
                $sql = "exists (select 'name' from `product_translations` where `vendor_products`.`product_id` = `product_translations`.`product_id`  and `product_translations`.`name` like ? order by `updated_at` desc) ";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })
           
            /*->filterColumn('product_id', function($query, $keyword) {
                $sql = "exists (select 'name' from `product_translations` where `vendor_products`.`product_id` = `product_translations`.`product_id`  and `product_translations`.`name` like ? order by `updated_at` desc) ";
                $query->whereRaw($sql, ["%{$keyword}%"]);
            })*/
            
           /* ->editColumn('product_id',function ($product){
                
                return $product->product->name;
            })*/
            /*->addColumn('measurement_details',function ($product){
                
                return 'hi';
            })*/
            ->addColumn('offer_name',function ($product){
            return ((isset($product->Offer->name) && !empty($product->Offer->name))  ? '<a href="'.route("offer.edit",$product->Offer->id).'" class="">'.$product->Offer->name.'</a>' : "Not applicable");
            
            })
            ->addColumn('action',function ($product){
                return '<a href="'.route("vendor-product.show",$product->id).'" class="btn btn-success">Show</a><a href="'.route("vendor-product.edit",$product->id).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$product->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" product-id="'.$product->id.'" '.(($product->status==1) ? "checked" : "") . ' value="'.$product->status.'" >';
            })
            ->rawColumns(['offer_name','action','category_id'])
            ->make(true);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Request $request){

        if($request->status==1){
            $status='0';
        }else{
            $status='1';
        }

        $user= $this->vendorProduct->withoutGlobalScope(StatusScope::class)->findOrFail($request->id)->update(['status'=>$status]);

        if($request->ajax()){
            if($user){
                return response()->json([
                    'status' => true,
                    'message' => 'update'
                ],200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'some thing is wrong'
                ],400);
            }
        }
    }
    
    public function mapview(Request $request){
		if ($this->user->can('view', Zone::class)) {
			return abort(403,'not able to access');
		}
	
		$zones = User::where('status', '=', '1')->where('user_type', 'driver')->orWhere('user_type', 'shoper')->select('current_lat', 'current_lng', 'name', 'id', 'user_type')->get();
		return view('admin/pages/vendor-product/view-map')->with('zones', $zones);
      
    }
    
    public function ajax_mapview(Request $request) {
		$data_return = array();
		$input = $request->all();
		
		if ($input['user_type'] == '1') {
			$zones = User::where('status', '=', '1')->where('user_type', 'driver')->select('current_lat', 'current_lng', 'name', 'id', 'user_type')->get();
		} else if ($input['user_type'] == '2') {
			$zones = User::where('status', '=', '1')->where('user_type', 'shoper')->select('current_lat', 'current_lng', 'name', 'id', 'user_type')->get();
		} else {
			$zones = User::where('status', '=', '1')->where('user_type', 'driver')->orWhere('user_type', 'shoper')->select('current_lat', 'current_lng', 'name', 'id', 'user_type')->get();
		}
		
		$view = \View::make('admin/pages/vendor-product/ajax-view-map', ['zones' => $zones]);
		$contents = $view->render();
		
		$data_return['status'] = true;
		$data_return['html'] = $contents;
		return json_encode($data_return);
	}
    
          public function changeShopperAndDriver(Request $request){


			
//echo "<pre>";
//print_r($request->all());die;


if($request->type=='shopper'){
$updateDetails=array(
    'driver_id'=>$request->driver_id,
    'assigned_status'=>"U"
);
        $order= $this->productorder->findOrFail($request->order_id)->update($updateDetails);
		   if ($order) {

			Session::flash('success','new driver assigned successful');	
            return redirect('admin/vendor-product/shopperassignment');
        }else{

        
            Session::flash('danger','something wrong');
            return redirect('admin/vendor-product/shopperassignment');
          
        }
        
    }else{
		$updateDetails=array(
    'shopper_id'=>$request->shoper_id,
    'assigned_status'=>"U"
);

		$order= $this->productorder->findOrFail($request->order_id)->update($updateDetails);
		
	//	echo "<pre>";
//print_r($request->all());die;
		
		
		
		
		   if ($order) {
			 Session::flash('success','new shopper assigned successful');	
           
            return redirect('admin/vendor-product/driverassignment');
        }else{

        
                Session::flash('danger','something wrong');
              return redirect('admin/vendor-product/driverassignment');
          
        }
		
		
		
	}
    
    

} 
    
    
}
