<?php

namespace App\Http\Controllers\Admin;
use App\Category;
use App\Helpers\Helper;
use App\Offer;
use App\Product;
use App\ProductOrder;
use App\VendorCommission;
use App\Scopes\StatusScope;
use App\User;
use App\Zone;
use App\Revenue;
use App\OrderComments;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class RevenueController extends Controller
{
    protected $product;
    protected $user;
    protected $offer;
    protected $category;
    protected $vendorController;
    protected $method;
    function __construct(Request $request,Product $product,Revenue $revenue,User $user,Offer $offer,Category $category,ProductOrder $order,VendorCommission $vendorcommission )
    {
        parent::__construct();
        $this->product=$product;
        $this->user=$user;
        $this->offer=$offer;
        $this->order=$order;
        $this->category=$category;
        $this->revenue=$revenue;
        $this->vendorcommission=$vendorcommission;
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id=null,request $request)
    {
        $order=$this->order->with(['ProductOrderItem','zone',"OrderStatusNew"]);
            //echo "<pre>" ;print_r($vendors);
        $zone = Zone::get()->pluck('name','id');
        $vendor=$this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');
        
        if ($this->user->can('revenue', VendorCommission::class)) {
            return abort(403,'not able to access');
        }
     
        //echo "<pre>";print_r($new_array_order);die;
        return view('admin/pages/revenue/index',compact(['order','vendor','zone']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

  
   
  /*  public function update(Request $request, $id)
    {

        $input = $request->all();
        $order = $this->order->where('order_id',$id)->get();
        if ($order->count() != 0) {
           $success = $this->order->where('order_id',$id)->update($input);
        } else {
            $order->fill($input)->save();
        }
        if($request->ajax()){
                if($success){
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

    }*/
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 

    /**
     * @return mixed
     */
    public function anyData(Request $request)
    {
    $newdata = [];
    $sum_v_invoice = 0;
    $sum_v_revenue  = 0;
    $sum_v  = 0;

    $vendors = $this->order->with(['ProductOrderItem','zone',"OrderStatusNew",'vendor','revenue'])->where('order_status','D')->select('*');
    
        if ($request->has('delivery_from_date') and !empty($request->delivery_from_date)) {
            $vendors->whereDate('delivery_date','>=',$request->delivery_from_date);
        }
        if ($request->has('delivery_to_date') and !empty($request->delivery_to_date)) {
            $vendors->whereDate('delivery_date','<=',$request->delivery_to_date);
        }
        if ($request->has('from_date') and !empty($request->from_date)) {
            $vendors->whereDate('created_at','>=',$request->from_date." 00:00:00");
        }
        if ($request->has('to_date') and !empty($request->to_date)){
            $vendors->whereDate('created_at','<=',$request->to_date." 23:59:59");
        }
        if($request->has('zone_id') and !empty($request->zone_id)){
           
            $vendors->where(['zone_id'=>$request->zone_id]);
        }
        if($request->has('vendor_id') and !empty($request->vendor_id)){

            $vendors->where(['vendor_id'=>$request->vendor_id]);
        }
        if($request->has('transaction_status') and !empty($request->transaction_status)){

            $vendors->where(['transaction_status'=>$request->transaction_status]);
        }
        if($request->has('admin_discount') and !empty($request->admin_discount)){
            if($request->admin_discount == 'yes'){
                $vendors->where('admin_discount','>',0);
            }else{
                $vendors->where('admin_discount','<=',0);
            }
        }
        if($request->has('delivery_charge') and !empty($request->delivery_charge)){
            if($request->delivery_charge == 'yes'){
                $vendors->where('delivery_charge','>',0);
            }else{
                $vendors->where('delivery_charge','<=',0);
            }
            
        }

        
        $rawVendors= $vendors->get();
        //echo "<pre>";print_r($rawVendors);die;
        $vendors = $rawVendors->toArray();
        $no_of_order = $rawVendors->count();
        $sum_sub_total = $rawVendors->sum('total_amount');
        $sum_total_amount = $sum_sub_total + $rawVendors->sum('delivery_charge') - $rawVendors->sum('admin_discount') - $rawVendors->sum('promo_discount');
        $sum_delivery_charge = $rawVendors->sum('delivery_charge');
        $sum_admin_discount = $rawVendors->sum('admin_discount');
        $sum_promo_code = $rawVendors->sum('promo_code');
        $sum_delivery_charge = $rawVendors->sum('delivery_charge');
        $sum_total_revenue = $rawVendors->sum('total_revenue');
        if($sum_total_amount > 0){
             $sum_revenue_percentage = $sum_total_revenue / $sum_total_amount;
         }else{
             $sum_revenue_percentage = $sum_total_revenue / 1;
         }

        $i=1;
        foreach($vendors as $vendordata)
        {
           
            $admin_commission = $this->vendorcommission->where(['vendor_id'=>$vendordata['vendor_id']])->get()->toArray();
            if(!empty($admin_commission)){
                 $new_array_order['vendor_commission']=$admin_commission[0]['percent'];
            }else{
                 $new_array_order['vendor_commission']=0;
            }

                $new_array_order['order_code']=$vendordata['order_code'];
                $new_array_order['order_id']=$vendordata['id'];
                $new_array_order['id']=$vendordata['id'];
                $new_array_order['created_at']=$vendordata['created_at'];
                $new_array_order['vendor']=$vendordata['vendor']['name'];
                $new_array_order['vendor_id']=$vendordata['vendor']['id'];
                $new_array_order['sub_total']=$vendordata['total_amount'];
            if($vendordata['revenue']['vendor_invoice'] !=''){
                $new_array_order['vendor_invoice']=$vendordata['revenue']['vendor_invoice'];
            }else{
                $new_array_order['vendor_invoice']=$vendordata['total_amount'];
            }
                
                $new_array_order['total_amount']=($vendordata['total_amount']+$vendordata['delivery_charge'])-$vendordata['admin_discount']-$vendordata['promo_discount'];
                $new_array_order['delivery_charge']=$vendordata['delivery_charge'];
                $new_array_order['admin_discount']=$vendordata['admin_discount'];
             if($vendordata['revenue']['verience_revenue'] !=''){
                $new_array_order['varience_revenue'] = $vendordata['revenue']['verience_revenue'];
            }else{
                $new_array_order['varience_revenue'] = 0.00;
            }
            $new_array_order['varience']=$vendordata['total_amount']-$new_array_order['vendor_invoice'];
            $new_array_order['promo_code']=$vendordata['promo_discount'];
            $new_array_order['vendor_revenue']=($new_array_order['vendor_invoice']*($new_array_order['vendor_commission']/100));
            $new_array_order['sum_vendor_revenue']=($new_array_order['vendor_invoice']*($new_array_order['vendor_commission']/100));
            $new_array_order['total_revenue'] = $new_array_order['delivery_charge']+$new_array_order['varience_revenue']+$new_array_order['vendor_revenue']-$new_array_order['admin_discount'];
            $new_array_order['revenue_percentage']=number_format($new_array_order['total_revenue']/$new_array_order['total_amount'],4)*100;
            $newdata[]=$new_array_order;
        }
        //echo "<pre>";print_r( array_sum($newdata['varience_revenue']));die;
       /* foreach ($newdata as $nkey => $nvalue) {
                $sum_v_invoice += $nvalue['vendor_invoice'];
                $sum_v += $nvalue['varience'];
                if($nvalue['varience_revenue'] > 0){
                    $positiveR[] = $nvalue['varience_revenue'];
                }else{
                    $negativeR[] = $nvalue['varience_revenue'];
                }
        }
       
        $sum_v_revenue = (array_sum(array_values($positiveR))) + (array_sum(array_values($negativeR)));*/
        
          //echo "<pre>";print_r($sum_v_revenue);die;
            if ($request->has('rev_from') and !empty($request->rev_from)) {
                $filterBy = $request->rev_from;
                $newdata = array_filter($newdata, function ($var) use ($filterBy) {
                    return ($var['total_revenue'] >= $filterBy);
                    });
            }
            if ($request->has('rev_to') and !empty($request->rev_to)) {
                $filterBy = $request->rev_to;
                $newdata = array_filter($newdata, function ($var) use ($filterBy) {
                    return ($var['total_revenue'] <= $filterBy);
                });
            
            }
            if ($request->has('rev_perc_from') and !empty($request->rev_perc_from)) {
                $filterBy = $request->rev_perc_from;
                $newdata = array_filter($newdata, function ($var) use ($filterBy) {
                    return ($var['revenue_percentage'] >= $filterBy);
                    });
            }
            if ($request->has('rev_perc_to') and !empty($request->rev_perc_to)) {
                $filterBy = $request->rev_perc_to;
                $newdata = array_filter($newdata, function ($var) use ($filterBy) {
                    return ($var['revenue_percentage'] <= $filterBy);
                });
            }
            /*if ($request->has('promo_code') and !empty($request->promo_code)) {
                $filterBy = $request->promo_code;
                $newdata = array_filter($newdata, function ($var) use ($filterBy) {
                    if($filterBy == 'yes'){
                        return ($var['promo_code'] > 0);
                    }else{
                        return ($var['promo_code'] <= 0);
                    }
                    
                });
            }*/
            if ($request->has('verience_revenue') and !empty($request->verience_revenue) && $request->has('verience') and !empty($request->verience)){
                 $filterByv = $request->verience;
                 $filterBy = $request->verience_revenue;
                 $newdata = array_filter($newdata, function ($var) use ($filterBy,$filterByv) {
                    if($filterBy == 'positive' && $filterByv == 'negative'){
                        return ($var['varience_revenue'] > 0 && $var['varience'] < 0);
                    } 
                    if($filterBy == 'negative' && $filterByv == 'positive'){
                        return ($var['varience_revenue'] < 0 && $var['varience'] > 0);
                    }
                    if($filterBy == 'positive' && $filterByv == 'zero'){
                        return ($var['varience_revenue'] > 0 && $var['varience'] == 0);
                    }
                    if($filterBy == 'zero' && $filterByv == 'positive'){
                        return ($var['varience_revenue'] == 0 && $var['varience'] > 0);
                    }
                     if($filterBy == 'negative' && $filterByv == 'zero'){
                       return ($var['varience_revenue'] < 0 && $var['varience'] == 0);
                    }
                    if($filterBy == 'zero' && $filterByv == 'negative'){
                        return ($var['varience_revenue'] == 0 && $var['varience'] < 0);
                    }
                     if($filterBy == 'zero' && $filterByv == 'zero'){
                        return ($var['varience_revenue'] == 0 && $var['varience'] == 0);
                    }
                     if($filterBy == 'negative' && $filterByv == 'negative'){
                       return ($var['varience_revenue'] < 0 && $var['varience'] < 0);
                    }
                     if($filterBy == 'positive' && $filterByv == 'positive'){
                        return ($var['varience_revenue'] > 0 && $var['varience'] > 0);
                    }
                });

            }else{
            
                   if ($request->has('verience_revenue') and !empty($request->verience_revenue)) {
                        $filterBy = $request->verience_revenue;
                        $newdata = array_filter($newdata, function ($var) use ($filterBy) {
                            if($filterBy == 'positive'){
                                return ($var['varience_revenue'] > 0.00 || $var['varience_revenue'] > 0);
                            } 
                            if($filterBy == 'negative'){
                                return ($var['varience_revenue'] < 0.00 || $var['varience_revenue'] < 0);
                            }
                            if($filterBy == 'zero'){
                                return ($var['varience_revenue'] == 0.00 || $var['varience_revenue'] == 0);
                            }
                        });
                    
                    }
                    if ($request->has('verience') and !empty($request->verience)) {
                        $filterByv = $request->verience;
                        $newdata = array_filter($newdata, function ($var) use ($filterByv) {
                            if($filterByv == 'positive'){
                                return ($var['varience'] > 0.00 || $var['varience'] > 0);
                            } 
                            if($filterByv == 'negative'){
                                return ($var['varience'] < 0.00 || $var['varience'] < 0);
                            }
                            if($filterByv == 'zero'){
                                return ($var['varience'] == 0.00 || $var['varience'] == 0);
                            }
                        });
                    
                    }
            }
           /* if($no_of_order > 0){
                    $avg_revenue_percentage = number_format((float) array_sum(array_column($newdata, 'revenue_percentage'))/$no_of_order, 2, '.', '');
                    $delivery_revenue_percentage = number_format((float) $sum_delivery_charge / array_sum(array_column($newdata, 'total_revenue'))*100, 2, '.', '');
                    $product_revenue_percentage = number_format((float) 100 - $delivery_revenue_percentage, 2, '.', '');
                }else{
                    $avg_revenue_percentage = 'N/A';
                    $delivery_revenue_percentage = 'N/A';
                    $product_revenue_percentage = 'N/A';
                }*/
       return Datatables::of($newdata,$sum_v_invoice,$sum_v_revenue ) 
           ->addColumn('order_code',function ($newdata){
            return isset($newdata['order_code']) ? $newdata['order_code']: '';
              
            })->addColumn('vendor',function ($newdata){
                  return isset($newdata['vendor']) ? $newdata['vendor']: '';

            })->addColumn('sub_total',function ($newdata){
                return isset($newdata['sub_total']) ? $newdata['sub_total']: '';

            })->addColumn('delivery_charge',function ($newdata){
                return isset($newdata['delivery_charge']) ? $newdata['delivery_charge']: '';
                
            }) ->addColumn('total_amount',function ($newdata){
                return isset($newdata['total_amount']) ? $newdata['total_amount']: '';

            }) ->addColumn('commission',function ($newdata){
                return isset($newdata['vendor_commission']) ? $newdata['vendor_commission']: '';

            })->addColumn('admin_discount',function ($newdata){
               return isset($newdata['admin_discount']) ? $newdata['admin_discount']: '';


            }) ->addColumn('vendor_invoice',function ($newdata){
                return isset($newdata['vendor_invoice']) ? $newdata['vendor_invoice']: '';
                
            }) ->addColumn('varience',function ($newdata){
                return isset($newdata['varience']) ? $newdata['varience']: '';

            }) ->addColumn('varience_revenue',function ($newdata){
                 return isset($newdata['varience_revenue']) ? $newdata['varience_revenue']: '';

            })
            
            ->addColumn('revenue_percentage',function ($newdata){
                return isset($newdata['revenue_percentage']) ? $newdata['revenue_percentage']: '';

            })
            ->addColumn('created_at',function ($newdata){
                return date('d/m/Y',strtotime($newdata['created_at']));
            })
            ->addColumn('action',function ($newdata){
                return '<a href="'.route("order.show",$newdata['order_id'])
                .'" class="btn btn-success">View</a></br><button type="button" onclick="editOrder
                ('.$newdata['order_id'].','.$newdata['sub_total'].','.$newdata['varience'].','.$newdata['vendor_revenue'].')" class="btn btn-success">Edit</button><a href="'.route("order.show",$newdata['order_id'])
                .'" class="btn btn-success">Go to Order</a><a  class="btn btn-success" onclick="editComment
                ('.$newdata['order_id'].')">Add/Read Comment</a>';
            })
             ->with([
                'no_of_order' => $no_of_order,
                'sum_vendor' =>  'N/A',
                'sum_sub_total'=> number_format((float)array_sum(array_column($newdata, 'sub_total')), 2, '.', ''),
                'sum_total_amount'=>number_format((float)array_sum(array_column($newdata, 'total_amount')), 2, '.', ''),
            'sum_admin_discount' => array_sum(array_column($newdata, 'admin_discount')),
                'sum_vendor_invoice' => array_sum(array_column($newdata, 'vendor_invoice')),
                'sum_varience' => number_format((float)array_sum(array_column($newdata, 'varience')), 2, '.', ''),
                'sum_varience_revenue' => number_format((float)array_sum(array_column($newdata, 'varience_revenue')), 2, '.', ''),
                'sum_delivery_charge' => array_sum(array_column($newdata, 'delivery_charge')),
                'sum_vendor_revenue' => number_format((float)array_sum(array_column($newdata, 'vendor_revenue')), 2, '.', ''),
                'sum_total_revenue' => number_format((float)array_sum(array_column($newdata, 'total_revenue')), 2, '.', ''),
                'sum_revenue_percentage' => count($newdata) > 0 ? number_format((float)array_sum(array_column($newdata, 'total_revenue'))/(float)array_sum(array_column($newdata, 'total_amount'))*100, 2, '.', ''): '0',

                'sum_vendor_commission' => number_format((float)array_sum(array_column($newdata, 'vendor_commission')), 2, '.', 'N/A'),
                /*'sum_promo_code' => $sum_promo_code,*/
                'avg_revenue_percentage' => count($newdata) > 0 ? number_format((float)array_sum(array_column($newdata, 'revenue_percentage'))/count(array_column($newdata, 'revenue_percentage')), 2, '.', ''): '0',
                'delivery_revenue_percentage'=> count($newdata) > 0? number_format((float) array_sum(array_column($newdata, 'delivery_charge')) / array_sum(array_column($newdata, 'total_revenue'))*100, 2, '.', ''): '0',
                'product_revenue_percentage'=> count($newdata) > 0 ? number_format((float) 100 - number_format((float) array_sum(array_column($newdata, 'delivery_charge')) / array_sum(array_column($newdata, 'total_revenue'))*100, 2, '.', ''), 2, '.', ''): '0',
            ])
            ->rawColumns(['action'])
            ->toJson();

    }
      public function storeRevenue(Request $request)
    {
        //return $input['order_id'];
        $input = $request->all();
         if($input['order_id'] == '' || $input['vendor_invoice'] == '' || $input['verience_revenue'] == ''){
                return response()->json($data=[
                    'status' => false,
                    'message' => 'Please fill required fields'
                ],400);
        }
        $order = $this->revenue->where('order_id',$input['order_id'])->get();
        if (count($order) > 0) {
           $success = $this->revenue->where('order_id',$input['order_id'])->update(['order_id'=>$input['order_id'],'vendor_invoice'=>$input['vendor_invoice'],'verience_revenue'=>$input['verience_revenue']]);
        } else {
             $success = $this->revenue->create(['order_id'=>$input['order_id'],'vendor_invoice'=>$input['vendor_invoice'],'verience_revenue'=>$input['verience_revenue']]);
        }
        if($request->ajax()){
                if($success){
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
      public function storeComment(Request $request)
    {
        //return $input['order_id'];
        $input = $request->all();
         if($input['order_id'] == '' || $input['comment'] == ''){
            //return "hi";
                return response()->json($data=[
                    'status' => false,
                    'message' => 'Please fill required fields'
                ],400);
        }
        $order = OrderComments::where('order_id',$input['order_id'])->get();
        if (count($order) > 0) {
           $success = OrderComments::where('order_id',$input['order_id'])->update(['order_id'=>$input['order_id'],'vendor_invoice'=>$input['vendor_invoice'],'verience_revenue'=>$input['verience_revenue']]);
        } else {
           $orderComment= new OrderComments;
             $success = $orderComment->create(['order_id'=>$input['order_id'],'comment'=>$input['comment']]);
        }
        if($request->ajax()){
                if($success){
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
    public function getRevenueData(Request $request){
        $input =  $request->all();
        //return $input['order_id'];
        $revenue = $this->revenue->select('verience_revenue','vendor_invoice')->where('order_id',$input['order_id'])->first();
        if (isset($revenue) && !empty($revenue)) {
            return response()->json(['status' => 'true', 'data' => $revenue ]);

        }else{
            return response()->json(['status' => 'false', 'data' => []]);
        }

    }
     public function getCommentData(Request $request){
        $input =  $request->all();
        //return $input['order_id'];
        $comment = OrderComments::select('comment')->where('order_id',$input['order_id'])->first();

        if (isset($comment) && !empty($comment)) {
            return response()->json(['status' => 'true', 'data' => $comment ]);

        }else{
            return response()->json(['status' => 'false', 'data' => []]);
        }

    }

    
    
}
