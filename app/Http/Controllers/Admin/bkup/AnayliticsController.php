<?php

namespace App\Http\Controllers\Admin;

use App\City;
use App\Category;
use App\DeliveryDay;
use App\DeliveryTime;
use App\Helpers\Helper;
use App\OrderStatusNew;
use App\ProductOrderItem;
use App\VendorCommission;
use App\Scopes\StatusScope;
use App\ProductOrder;
use App\Notifications\OrderStatus;
use App\User;
use App\Product;
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

class AnayliticsController extends Controller
{
    protected $user;
    protected $order;
    protected $productOrderItem;
    protected $method;
    function __construct(Request $request,User $user, ProductOrder $order,ProductOrderItem $productOrderItem,OrderStatusNew $orderstatusnew,VendorCommission $vendorcommission)
    {
        parent::__construct();
        $this->user=$user;
        $this->order=$order;
        $this->productOrderItem=$productOrderItem;
        $this->vendorcommission=$vendorcommission;
        $this->orderstatusnew=$orderstatusnew;
        $this->method=$request->method();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

	public function orders(Request $request){

        $zones = Zone::get()->pluck('name','id');
        $vandors=$this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');
        $shoper=$this->user->where(['user_type'=>'shoper','role'=>'user'])->get()->pluck('full_name','id');
        $driver=$this->user->where(['user_type'=>'driver','role'=>'user'])->get()->pluck('full_name','id');
		return view('admin/pages/analytics/order' ,compact(['zones','vandors','driver','shoper']));
	} 

    public function customers(Request $request){

        return view('admin/pages/analytics/customer');
    } 

    public function products(Request $request){
        $zones = Zone::get()->pluck('name','id');
        $vandors=$this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');
        $customer=$this->user->where(['user_type'=>'user','role'=>'user'])->get()->pluck('full_name','id');
        
        return view('admin/pages/analytics/product' ,compact(['zones','vandors','customer']));
    } 
    public function slotTimes(Request $request){
        $zones = Zone::get()->pluck('name','id');
        $weekdays = DeliveryDay::listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/analytics/slotTimes' ,compact(['zones','weekdays']));

    } 

    public function zone(Request $request){
         $zone = Zone::get()->pluck('name','id');
        return view('admin/pages/analytics/zone' ,compact(['zone']));
    } 

    public function shopper(Request $request){
        return view('admin/pages/analytics/shopper');
    } 
    public function driver(Request $request){
        return view('admin/pages/analytics/driver');
    } 

    public function vendor(Request $request){
        $vendors = $this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');
        return view('admin/pages/analytics/vendor',compact(['vendors']));
    } 

    public function anyData(Request $request)
    {
    //return $request->user_id;
    //$vendors = $this->vendorCommission->with(['User'])->select('*')->get();
    $newdata = [];
    $vendors = $this->order->with(['ProductOrderItem','zone',"OrderStatusNew",'vendor','revenue'])->select('*',DB::raw('count(id) AS no_of_orders'),DB::raw("SUM(total_amount) as sum_total"),DB::raw("SUM(delivery_charge) as sum_delivery_charge"),DB::raw("SUM(admin_discount) as sum_admin_discount"))->groupBy(DB::raw('DAY(created_at)') )->orderBy('created_at',"DESC");

        if($request->has('order_type') and !empty($request->order_type)){
             $filterBy = $request->order_type;
           if($filterBy !='days'){

                if($filterBy == 'MONTH'){
                    $vendors = $this->order->with(['ProductOrderItem','zone',"OrderStatusNew",'vendor','revenue'])->select('*',DB::raw('count(id) AS no_of_orders'),DB::raw("SUM(total_amount) as sum_total"),DB::raw("SUM(delivery_charge) as sum_delivery_charge"),DB::raw("SUM(admin_discount) as sum_admin_discount"),
                        DB::raw("DATE_FORMAT(created_at, '%m-%Y') AS start_date") ,DB::raw("YEAR(created_at) year"),DB::raw("MONTH(created_at) month"))->orderBy('year','DESC')->orderBy('month','DESC')->groupBy('start_date');
                }else{
                    $vendors = $this->order->with(['ProductOrderItem','zone',"OrderStatusNew",'vendor','revenue'])->select('*',DB::raw('count(id) AS no_of_orders'),DB::raw("SUM(total_amount) as sum_total"),DB::raw("SUM(delivery_charge) as sum_delivery_charge"),DB::raw("SUM(admin_discount) as sum_admin_discount"))->orderBy('created_at')->groupBy(DB::raw($filterBy.'(created_at)'));
                    }
                }
                
        }
     //echo"<pre>";print_r($request->all);die;
        if($request->has('transaction_status') and !empty($request->transaction_status)){

            $vendors->where(['transaction_status'=>$request->transaction_status]);
        }
        if($request->has('zone_id') and !empty($request->zone_id)){

            $vendors->where(['zone_id'=>$request->zone_id]);
        }
        if($request->has('vendor_id') and !empty($request->vendor_id)){

            $vendors->where(['vendor_id'=>$request->vendor_id]);
        }
        
        if ($request->has('from_date') and !empty($request->from_date)) {
            $vendors->whereDate('created_at','>=',$request->from_date." 00:00:00");
        }
        if ($request->has('to_date') and !empty($request->to_date)) {
            $vendors->whereDate('created_at','<=',$request->to_date." 23:59:59");
        }
        
        $vendors= $vendors->get()->toArray();
        
        
       // print_r($vendors);
       // die;
        
       //$vendors= $vendors->toSql();
        //dd($vendors);
        //echo "<pre>";print_r($vendors);die;
        $i=0;
        foreach($vendors as $vendordata){
		//	echo $start_date.",";
            $admin_commission = $this->vendorcommission->where(['vendor_id'=>$vendordata['vendor_id']])->get()->toArray();
            //echo "<pre>";print_r($admin_commission);
            if(!empty($admin_commission)){
                 $new_array_order['vendor_commission']=$admin_commission[0]['percent'];
            }else{
                 $new_array_order['vendor_commission']=0;
            }
            if($request->order_type=="WEEK" && $request->from_date){
				/*if($i==0){
				$adddays=$i*7;
				}else{
				$adddays=($i*7)+1;
				}*/
                $adddays=$i*7;
				$new_array_order['start_date']= date('Y-m-d', strtotime($request->from_date. ' +'.$adddays. 'days'));
				$new_array_order['end_date']= date('Y-m-d', strtotime($new_array_order['start_date']. ' + 7days'));
				
			}else{
				if($request->order_type=="MONTH"){
                    $new_array_order['start_date']= $vendordata['start_date'];
                    $new_array_order['end_date']= $vendordata['start_date'];
                }
                else if($request->order_type=="YEAR"){
                    $new_array_order['start_date']= date('Y',strtotime($vendordata['created_at']));
                    $new_array_order['end_date']= date('Y',strtotime($vendordata['created_at']));
                }
                else if($request->order_type=="WEEK"){
                    $adddays=$i*7;
                    $new_array_order['start_date']= date('Y-m-d', strtotime($vendordata['created_at']. ' +'.$adddays. 'days'));
                    $new_array_order['end_date']= date('Y-m-d', strtotime($new_array_order['start_date']. ' + 7days'));
                }else{
                    $new_array_order['start_date']= date('d-m-Y',strtotime($vendordata['created_at']));
                    $new_array_order['end_date']= date('d-m-Y',strtotime($vendordata['created_at']));
                }
           
			}
				
		
           // echo $request->order_type;die;
          
            $new_array_order['no_of_orders']= $vendordata['no_of_orders'];
            
            $new_array_order['total_amount']=($vendordata['sum_total']+$vendordata['sum_delivery_charge'])-$vendordata['sum_admin_discount'];
            if($vendordata['revenue']['vendor_invoice'] !=''){
                $new_array_order['vendor_invoice']=$vendordata['revenue']['vendor_invoice'];
            }else{
                $new_array_order['vendor_invoice']=$vendordata['total_amount'];
            }
            $new_array_order['total_amount']=($vendordata['sum_total']+$vendordata['sum_delivery_charge'])-$vendordata['sum_admin_discount'];
            $new_array_order['delivery_charge']=$vendordata['sum_delivery_charge'];
            $new_array_order['admin_discount']=$vendordata['sum_admin_discount'];
           if($vendordata['revenue']['verience_revenue'] !=''){
                $new_array_order['varience_revenue'] = $vendordata['revenue']['verience_revenue'];
            }else{
                $new_array_order['varience_revenue'] = 0.00;
            }
            $new_array_order['varience']= $new_array_order['total_amount']-$new_array_order['vendor_invoice'];
            $new_array_order['promo_code']=$new_array_order['total_amount']-$vendordata['offer_total'];
            $new_array_order['vendor_revenue']=($new_array_order['vendor_invoice']*($new_array_order['vendor_commission']/100));
            $new_array_order['total_revenue']=$new_array_order['delivery_charge']+$new_array_order['varience_revenue']+$new_array_order['vendor_revenue']-$new_array_order['admin_discount']-$new_array_order['promo_code'];
          $new_array_order['product_revenue']=$new_array_order['total_revenue']-$new_array_order['delivery_charge'];
            
           $new_array_order['revenue_percentage']=number_format($new_array_order['total_revenue']/$new_array_order['total_amount'],2);
        
            $newdata[]=$new_array_order;
            $i++;
        }
            if ($request->has('delivery_charge') and !empty($request->delivery_charge)) {
                $filterBy = $request->delivery_charge;
                $newdata = array_filter($newdata, function ($var) use ($filterBy) {
                    if($filterBy == 'y'){
                        return ($var['delivery_charge'] > 0.00);
                        
                    }else{ 
                        return ($var['delivery_charge'] == 0.00);
                    }
                    
                    });
            }
            if ($request->has('varience_revenue') and !empty($request->varience_revenue)) {
                $filterBy = $request->varience_revenue;
                $newdata = array_filter($newdata, function ($var) use ($filterBy) {
                     if($filterBy == 'positive'){
                        return ($var['varience_revenue'] >= 0.00);
                    }else{ 
                        return ($var['varience_revenue'] == 0.00);
                    }
                });
            
            }
           
        
       return Datatables::of($newdata) 

       ->addColumn('start_date',function ($newdata){
            return isset($newdata['start_date']) ? $newdata['start_date']: '';
        })
        ->addColumn('end_date',function ($newdata){
            return isset($newdata['end_date']) ? $newdata['end_date']: '';
        })
        ->addColumn('no_of_orders',function ($newdata){
            return isset($newdata['no_of_orders']) ? $newdata['no_of_orders']: '';    
        })
        ->addColumn('total_amount',function ($newdata){
            return isset($newdata['total_amount']) ? $newdata['total_amount']: '';    
        })
        ->addColumn('delivery_charge',function ($newdata){
            return isset($newdata['delivery_charge']) ? $newdata['delivery_charge']: '';
        })
        ->addColumn('product_revenue',function ($newdata){
            return isset($newdata['product_revenue']) ? $newdata['product_revenue']: '';    
        })
        ->addColumn('total_revenue',function ($newdata){
            return isset($newdata['total_revenue']) ? $newdata['total_revenue']: '';
        })
        ->addColumn('revenue_percentage',function ($newdata){
            return isset($newdata['revenue_percentage']) ? $newdata['revenue_percentage']: '';
        })
       ->make(true);

    }

     public function customerData(Request $request)
    {
    $newdata = [];
    $conditions= 'DATE(product_orders1.created_at)=DATE(users.created_at)';

    if($request->has('order_type') and !empty($request->order_type)){
     $filterBy = $request->order_type;
            if($filterBy =='days'){
                $conditions= 'DATE(product_orders1.created_at)=DATE(users.created_at)';
            }else{
                $conditions= $filterBy.'(product_orders1.created_at) = '.$filterBy.'(users.created_at)';
            }

    }
    if ($request->has('from_date') and !empty($request->from_date)) {
    $conditions .= ' AND Date(product_orders1.created_at) >= "' . $request->from_date .'"';
    }

    if ($request->has('to_date') and !empty($request->to_date)) {
    $conditions .= ' AND Date(product_orders1.created_at) <= "' . $request->to_date .'"';
    }
    $customers = DB::table("users")/*
                ->leftJoin('product_orders','users.id','=', 'product_orders.user_id')*/
                ->select(DB::raw('count(users.id) AS all_customers'),DB::Raw('DATE(users.created_at) day'),DB::raw('DATE_FORMAT(users.created_at, "%M-%Y") AS start_date') ,DB::raw('YEAR(users.created_at) year'),DB::raw('MONTH(users.created_at) month'),'users.created_at', DB::raw('(select count(product_orders1.id) as total1 from product_orders as product_orders1  where '.$conditions.') AS customer_ordered'))
                ->where('users.user_type','user');
        if($request->has('order_type') and !empty($request->order_type)){
            $filterBy = $request->order_type;
            if($filterBy =='days'){
                 $customers->groupBy('day')->orderBy('month','ASC');
            }else{
                $customers->groupBy(DB::raw($filterBy.'(users.created_at)'))->orderBy('users.created_at', 'DESC');
            }
        }else{
            $customers->groupBy('day')->orderBy('month','ASC');
        }

        if ($request->has('from_date') and !empty($request->from_date)) {
            $customers->whereDate('users.created_at','>=',$request->from_date." 00:00:00");
        }
        if ($request->has('to_date') and !empty($request->to_date)) {
            $customers->whereDate('users.created_at','<=',$request->to_date." 23:59:59");
        }
       
        $customers= $customers->get()->toArray();
        //$customers= $customers->toSql();
        //dd($customers);
    //echo "<pre>";print_r($customers);die;
        $i=1;
        foreach($customers as $customerdata){
            if($i==1){
                $new_array_order['all_customers'] = 0;
            }
            //$new_array_order['start_date']= date('d-m-Y',strtotime($customerdata->created_at));
            $filterByDate = $request->order_type;
            $new_array_order['start_date']= $customerdata->day;
             $new_array_order['end_date']= $customerdata->day;
             if($filterByDate =='MONTH'){
               $new_array_order['start_date']= $customerdata->start_date;
                $new_array_order['end_date']= $customerdata->start_date;
            }
            if($filterByDate =='WEEK'){
                $endDate = date('d-m-Y', strtotime($customerdata->created_at. ' + 7 days'));
                $new_array_order['end_date'] = $endDate;
            }
            if($filterByDate =='YEAR'){
               $new_array_order['start_date']= $customerdata->year;
                $new_array_order['end_date']= $customerdata->year;
            }
            if($filterByDate =='dayz'){
               $new_array_order['start_date']= $customerdata->day;
                $new_array_order['end_date']= $customerdata->day;
            }
            /*else{
                $new_array_order['end_date'] = date('d-m-Y', strtotime($customerdata->created_at));
            }*/
            $new_array_order['customers_joined']=$customerdata->all_customers;
            $new_array_order['all_customers']= $customerdata->all_customers ;
            $new_array_order['customers_ordered']=$customerdata->customer_ordered;
            $newdata[]=$new_array_order;
            $i++;
        }
       
       return Datatables::of($newdata) 

        ->addColumn('start_date',function ($newdata){
            return isset($newdata['start_date']) ? $newdata['start_date']: '';
        })
        ->addColumn('end_date',function ($newdata){
            return isset($newdata['end_date']) ? $newdata['end_date']: '';
        })
        ->addColumn('customers_joined',function ($newdata){
            return isset($newdata['customers_joined']) ? $newdata['customers_joined']: '';   
        })
        ->addColumn('all_customers',function ($newdata){
            return isset($newdata['all_customers']) ? $newdata['all_customers']: '';    
        })
        ->addColumn('customers_ordered',function ($newdata){
            return isset($newdata['customers_ordered']) ? $newdata['customers_ordered']: '';
        })
       ->make(true);
    }

    public function productData(Request $request)
    {
    /*$newdata = [];*/
    $finaldata =[];
    $sum =0;
    $count = 0; 
    $sumOrder = 0; 
    /*
    $product = Product::with(['VendorProduct.ProductOrderItem' => function ($query) {
                        $query->SUM('qty');
                }])->get()->toArray();
     echo "<pre>";print_r($product);die;*/
    $product = Product::with(['VendorProduct','VendorProduct.ProductOrderItem','VendorProduct.ProductOrderItem.ProductOrders']);
            if ($request->has('from_date') and !empty($request->from_date)) {
                                $product->whereDate('created_at','>=',$request->from_date." 00:00:00");
            }
            if ($request->has('to_date') and !empty($request->to_date)) {
                    $product->whereDate('created_at','<=',$request->to_date." 23:59:59");
            }

    $product = $product->get()->toArray();
               foreach($product as $pkey=>$pdata){
                $sumOrder = 0; 
                $sum = 0;
                    foreach ($pdata['vendor_product'] as $vkey => $vendorData) {
                        if($pdata['id'] == $vendorData['product_id']){
                            $sum += $vendorData['qty']; 
                                if(isset($vendorData['product_order_item']) && !empty($vendorData['product_order_item'])){

                                    foreach ($vendorData['product_order_item'] as $pkey => $itemData) {
                                         if($vendorData['id'] == $itemData['vendor_product_id']){
                                            $sumOrder += $itemData['qty']; 
                                        }
                                         $zone_id =$itemData['product_orders']['zone_id'];
                     
                                        $customer_id =$itemData['product_orders']['user_id'];
                                        $created_at =$itemData['product_orders']['created_at'];
                                        
                                    }
                                }
                        }
                                   
                }
                
                                $newdata['name'] =$pdata['name'];
                                $newdata['total_qty'] =$sum;
                                $newdata['orders'] =$sumOrder;
                                $newdata['vendor_id'][] =$vendorData['user_id'];
                                $newdata['zone_id'] =$zone_id;
                                $newdata['customer_id'][] =$customer_id;
                                $newdata['from_date'][] =$created_at;
                                $finaldata[]=$newdata; 
               
                   
                }

  /*  $vendors =  DB::table('products AS p')
                ->join('product_translations AS pt','pt.product_id','=','p.id')
                ->select('p.id as productId','pt.name',DB::raw("(Select sum(poi.qty) from product_order_items as poi where poi.vendor_product_id IN (Select vp.id from vendor_products as vp where vp.product_id=p.id))as orders"),DB::raw("(select sum(vp1.qty) from vendor_products as vp1 where vp1.product_id=p.id) as total_qty"))
                ->where('pt.locale','=','en')
                ->orderBy('pt.name', 'ASC'); 
*/

                if($request->has('zone_id') and !empty($request->zone_id)){
                    $filterBy = $request->zone_id;
                        $finaldata = array_filter($finaldata, function ($var) use ($filterBy) {
                        return $var['zone_id'] == $filterBy;
                    });
                    
                }
                if($request->has('vendor_id') and !empty($request->vendor_id)){
                    $filterBy = $request->vendor_id;
                        $finaldata = array_filter($finaldata, function ($var) use ($filterBy) {
                        return in_array($filterBy, $var['vendor_id']);
                    });
                }
                if($request->has('customer_id') and !empty($request->customer_id)){
                    $filterBy = $request->customer_id;
                        $finaldata = array_filter($finaldata, function ($var) use ($filterBy) {
                        return in_array($filterBy, $var['customer_id']);
                    });
                }
               
                
               return Datatables::of($finaldata) 
                    ->addColumn('product',function ($finaldata){
                        return isset($finaldata['name']) ? $finaldata['name']: '';
                    })
                    ->addColumn('total_qty',function ($finaldata){
                        return isset($finaldata['total_qty']) ? $finaldata['total_qty']: 0;
                    })
                    ->addColumn('orders',function ($finaldata){
                        return isset($finaldata['name']) ? $finaldata['orders']: 0;    
                    })
               ->make(true);
    }

    public function slotTimesData(Request $request)
    {
    $result = [];
    $finalArray = [];
    $order = 0;
    /*here delivery_time_id is slot_times table id*/
            $vendors =  DB::table('product_orders AS PO')
                ->join('slot_times AS ST','ST.id','=','PO.delivery_time_id')
                ->select(DB::raw('COUNT(PO.id) AS total_order'),'PO.zone_id','ST.to_time','ST.from_time','PO.order_status','PO.delivery_time_id','PO.delivery_date','PO.created_at')
                ->whereNull('ST.deleted_at')
                ->groupBy('PO.delivery_time_id');
                $vendors = $vendors->get()->toArray();
            $filterOrder =  DB::table('product_orders AS PO')
                ->rightJoin('slot_times AS ST','ST.id','=','PO.delivery_time_id')
                ->select(DB::raw('COUNT(PO.id) AS orders'),'PO.delivery_time_id','delivery_date')
                ->whereNull('ST.deleted_at')
                ->groupBy('PO.delivery_time_id');

                if($request->has('zone_id') and !empty($request->zone_id)){
                    $filterOrder->where(['PO.zone_id'=>$request->zone_id]);
                }
                if($request->has('order_status') and !empty($request->order_status)){
                    $filterOrder->where(['PO.order_status'=>$request->order_status]);
                }
                if($request->has('weekday') and !empty($request->weekday)){
                    $filterOrder->where(DB::raw("DAYOFWEEK(PO.delivery_date)"), $request->weekday);
                }
                if ($request->has('from_date') and !empty($request->from_date)) {
                    $filterOrder->whereDate('PO.created_at','>=',$request->from_date." 00:00:00");
                }
                if ($request->has('to_date') and !empty($request->to_date)) {
                    $filterOrder->whereDate('PO.created_at','<=',$request->to_date." 23:59:59");
                }
                $filterOrder = $filterOrder->get();

                //$finalArray = $filterOrder->pluck('orders','delivery_time_id');
                //echo"<pre>";print_r($finalArray[16]);

             foreach ($filterOrder as $fkey => $fvalue) {
                          $finalArray[$fvalue->delivery_time_id] = $fvalue;
                           
                        }
                if(isset($vendors) and !empty($vendors)){
                    
                    foreach ($vendors as $vkey => $vvalue) {
                        $arraylist=[];
                         if(array_key_exists($vvalue->delivery_time_id, $finalArray)){
                            $order = $finalArray[$vvalue->delivery_time_id]->orders;
                         }else{
                            $order = 0;
                        }
                        $arraylist['all_orders'] = $vvalue->total_order;
                        $arraylist['zone_id'] = $vvalue->zone_id;
                        $arraylist['order_status'] = $vvalue->order_status;
                        $arraylist['delivery_date'] = $vvalue->delivery_date;
                        $arraylist['created_at'] = $vvalue->created_at;
                        $arraylist['slot_times'] = $vvalue->to_time.'-'.$vvalue->from_time;
                        $arraylist['orders'] = $order;
                        $result[]= $arraylist;   
                    }
                    
                }
               
                /*
                                $vendors = $vendors->toSql();
                                DB::enableQueryLog();
                                echo"<pre>";print_r($finalArray);
                */

                return Datatables::of($result) 
                    ->addColumn('slot_times',function ($result){
                        return $result['slot_times'];
                    })
                    ->addColumn('all_orders',function ($result){
                        return $result['all_orders'];
                    })
                    ->addColumn('orders',function ($result){
                        return $result['orders'];
                    })
                ->make(true);
                 
    }

    public function zoneData(Request $request)
    {
    $newdata = [];
    $vendors =  DB::table('zone_translations AS ZT')
                ->leftJoin('product_orders AS PO','PO.zone_id','=','ZT.zone_id')
                ->select(DB::raw('COUNT(PO.id) AS orders'),DB::raw('SUM(PO.total_amount) AS sum_total'),'ZT.name',DB::raw('(select count(product_orders1.id) as total1 from product_orders as product_orders1  where product_orders1.zone_id=ZT.zone_id group by product_orders1.zone_id) AS total_filtered'))
                ->where('locale','en')
                ->groupBy('PO.zone_id');
  
                if($request->has('zone_id') and !empty($request->zone_id)){
                    $vendors->where(['PO.zone_id'=>$request->zone_id]);
                }
                if($request->has('order_status') and !empty($request->order_status)){
                    $vendors->where(['PO.order_status'=>$request->order_status]);
                }
               
                if ($request->has('from_date') and !empty($request->from_date)) {
                    $vendors->whereDate('PO.created_at','>=',$request->from_date." 00:00:00");
                }
                if ($request->has('to_date') and !empty($request->to_date)) {
                    $vendors->whereDate('PO.created_at','<=',$request->to_date." 23:59:59");
                }
                $vendors = $vendors->get()->toArray();
                //echo"<pre>";print_r($vendors);die;
               return Datatables::of($vendors) 
                    ->addColumn('zone',function ($vendors){
                        return isset($vendors->name) ? $vendors->name: '';
                    })
                    ->addColumn('all_orders',function ($vendors){
                        return isset($vendors->total_filtered) ? $vendors->total_filtered: 0;
                    })
                    ->addColumn('orders',function ($vendors){
                        return isset($vendors->orders) ? $vendors->orders: '';    
                    })
                    ->addColumn('total_amount',function ($vendors){
                        return isset($vendors->sum_total) ? $vendors->sum_total: 0;    
                    })
               ->make(true);
    }

    public function vendorData(Request $request)
    {
    $newdata = [];
    $vendors =  DB::table('users AS U')
                ->leftJoin('product_orders AS PO','PO.vendor_id','=','U.id')
                ->join('vendor_commissions AS VC','VC.vendor_id','=','U.id')
                ->select(DB::raw('COUNT(PO.id) AS orders'),DB::raw('SUM(PO.total_amount) AS sum_total'),'U.name','PO.delivery_charge','VC.percent','PO.admin_discount', DB::raw('(select count(product_orders1.id) as total1 from product_orders as product_orders1  where product_orders1.vendor_id=U.id group by product_orders1.vendor_id) AS total_filtered'))
                ->groupBy('PO.vendor_id');
                if($request->has('vendor_id') and !empty($request->vendor_id)){
                    $vendors->where(['PO.vendor_id'=>$request->vendor_id]);
                }
                if ($request->has('from_date') and !empty($request->from_date)) {
                    $vendors->whereDate('PO.created_at','>=',$request->from_date." 00:00:00");
                }
                if ($request->has('to_date') and !empty($request->to_date)) {
                    $vendors->whereDate('PO.created_at','<=',$request->to_date." 23:59:59");
                }
                $vendors = $vendors->get()->toArray();
                 //$vendors = $vendors->get()->toSql();
                return Datatables::of($vendors) 
                    ->addColumn('vendor',function ($vendors){
                        return isset($vendors->name) ? $vendors->name: '';
                    })
                    ->addColumn('all_orders',function ($vendors){
                        return isset($vendors->total_filtered) ? $vendors->total_filtered: '';
                    })
                    ->addColumn('orders',function ($vendors){
                        return isset($vendors->orders) ? $vendors->orders: '';    
                    })
                    ->addColumn('total_amount',function ($vendors){
                        return isset($vendors->sum_total) ? $vendors->sum_total: '';    
                    })
                    ->addColumn('total_vendor_invoice',function ($vendors){
                        return isset($vendors->sum_total) ? $vendors->sum_total+$vendors->delivery_charge-$vendors->admin_discount: '';    
                    })
                    ->addColumn('total_vendor_revenue',function ($vendors){
                        return isset($vendors->sum_total) ?  ($vendors->sum_total+$vendors->delivery_charge-$vendors->admin_discount)*($vendors->percent/100): '';    
                    })
               ->make(true);
              

    }

     public function shopperData(Request $request)
    {
   
        $conditions= 'product_orders1.shopper_id = users.id';

        if($request->has('order_status') and !empty($request->order_status)){
        $conditions .= ' AND product_orders1.order_status = "' . $request->order_status .'"';
        }

        if ($request->has('from_date') and !empty($request->from_date)) {
        $conditions .= ' AND Date(product_orders1.created_at) >= "' . $request->from_date .'"';
        }

        if ($request->has('to_date') and !empty($request->to_date)) {
        $conditions .= ' AND Date(product_orders1.created_at) <= "' . $request->to_date .'"';
        }
         //echo $conditions;die;
        $vendors =  DB::table('users')
                    ->leftJoin('product_orders','users.id', '=', 'product_orders.shopper_id')
                    ->select( DB::raw('COUNT(product_orders.id) AS total_qty'),
                        'users.id', 
                        'users.name',
                        DB::raw('(select count(product_orders1.id) as total1 from product_orders as product_orders1 where '.$conditions.') AS total_filtered') )
                    ->where('users.user_type','=','shoper')
                    ->groupBy('users.id');

        $vendors = $vendors->get()->toArray();

                    return Datatables::of($vendors) 
                    ->addColumn('shopper',function ($vendors){
                        return isset($vendors->name) ? ucwords($vendors->name): '';
                    })
                    ->addColumn('all_orders',function ($vendors){
                        return isset($vendors->total_qty) ? $vendors->total_qty: '';
                    })
                    ->addColumn('orders',function ($vendors){
                        return isset($vendors->total_filtered) ? $vendors->total_filtered: '';    
                    })
                
               ->make(true);
    }

    
     public function driverData(Request $request)
    {
        $conditions= 'product_orders1.driver_id = users.id';

        if($request->has('order_status') and !empty($request->order_status)){
        $conditions .= ' AND product_orders1.order_status = "' . $request->order_status .'"';
        }

        if ($request->has('from_date') and !empty($request->from_date)) {
        $conditions .= ' AND Date(product_orders1.created_at) >= "' . $request->from_date .'"';
        }

        if ($request->has('to_date') and !empty($request->to_date)) {
        $conditions .= ' AND Date(product_orders1.created_at) <= "' . $request->to_date .'"';
        }
         //echo $conditions;die;
        $vendors =  DB::table('users')
                    ->leftJoin('product_orders','users.id', '=', 'product_orders.driver_id')
                    ->select( DB::raw('COUNT(product_orders.id) AS total_qty'),
                        'users.id', 
                        'users.name',
                        DB::raw('(select count(product_orders1.id) as total1 from product_orders as product_orders1 where '.$conditions.') AS total_filtered') )
                    ->where('users.user_type','=','driver')
                    ->groupBy('users.id');

        $vendors = $vendors->get()->toArray();

                    return Datatables::of($vendors) 
                    ->addColumn('driver',function ($vendors){
                        return isset($vendors->name) ? ucwords($vendors->name): '';
                    })
                    ->addColumn('all_orders',function ($vendors){
                        return isset($vendors->total_qty) ? $vendors->total_qty: '';
                    })
                    ->addColumn('orders',function ($vendors){
                        return isset($vendors->total_filtered) ? $vendors->total_filtered: '';    
                    })
                
               ->make(true);
    }
    
}
