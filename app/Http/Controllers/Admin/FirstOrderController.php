<?php

namespace App\Http\Controllers\Admin;

use App\FirstOrder;
use App\Product;
use App\User;
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
use Imagick;
use Storage;
use redirect;



class FirstOrderController extends Controller
{
    protected $first_order;
    protected $product;
    protected $method;


    function __construct(Request $request,FirstOrder $first_order,Product $product,User $user)
    {
        parent::__construct();
        $this->first_order=$first_order;
        $this->product=$product;
        $this->user=$user;
        $this->method=$request->method();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		
        if ($this->user->can('view', FirstOrder::class)) {
          return abort(403,'not able to access');
        }
        $products=$this->product->get()->pluck('name','id');
        return view('admin/pages/first_order/index',compact(['products']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', FirstOrder::class)) {
            return abort(403,'not able to access');
        }

        $validator = JsValidatorFacade::make($this->first_order->rules('POST'));
          $products=$this->product->get()->pluck('name','id');
       
        return view('admin/pages/first_order/add')->with('products',$products)->with('validator',$validator);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
         public function store(Request $request)
    { $input = $request->all();
       $validator = Validator::make($request->all(),$this->first_order->rules('POST'),$this->first_order->messages('POST'));
       if($validator->fails()){
         return back()->withErrors($validator)->withInput();
       }else{DB::beginTransaction();
            try {
                $first_order = $this->first_order->create($input);
               
                Session::flash('success','First Order Free products Added successful');
                DB::commit();
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
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $validator = JsValidatorFacade::make($this->first_order->rules('PUT'));
        $first_order=$this->first_order->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $products=$this->product->get()->pluck('name','id');
        return view('admin/pages/first_order/edit')->with('first_order',$first_order)->with('products',$products)->with('validator',$validator);
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
        $input['free_product'] = implode(',',$request->free_product);
    //   echo "<pre>"; print_r($input); die;
        $validator = Validator::make($request->all(),$this->first_order->rules($this->method),$this->first_order->messages($this->method));

        if ($validator->fails()) {

            Session::flash('danger',$validator->errors()->first());
            return redirect('admin/first_order/create')->withErrors($validator)->withInput();
        }else{

            DB::beginTransaction();
            try {
                $first_order = $this->first_order->FindOrFail($id);
                $first_order->update($input);
        
                DB::commit();
                 Session::flash('success','First Order Offer updated successfully');
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

        $flight = $this->first_order->withoutGlobalScope(StatusScope::class)->findOrFail($id);
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
    {   //die('asd');
try{
        $first_order = $this->first_order->get();
        return Datatables::of($first_order)
             ->addColumn('free_product',function ($first_order){
               $free_products = $first_order->free_product;
//               $free_products =  explode(',',$first_order->free_product);
               $html = '';$i=1;
               foreach($free_products as $key=>$val){
                $product = $this->product->where('id',trim($val))->first();
                if(!empty($product)){ $html .=$i.") ".$product->name.' ,<br/>'; 
         }else{  $html="no product"; }
                
               }
               return rtrim($html,',<br/>');
             
            })
            ->addColumn('action',function ($first_order){
              return '<a href="'.route('first_order.edit',$first_order->id).'"  class="btn btn-success">Edit</a><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" first-order-id="'.$first_order->id.'" '.(($first_order->status==1) ? "checked" : "") . ' value="'.$first_order->status.'" >';
              })

            ->rawColumns(['free_product','action'])
            ->make(true);
         
    }catch(Exception $e){
        return $e;
    }    
    }
    
    
    
    
    
    
    
    public function show($id)
    {
        $first_order = $this->first_order->findOrFail($id);
        return view('admin/pages/first_order/show')->with('first_order',$first_order);
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
        $user= $this->first_order->withoutGlobalScope(StatusScope::class)->findOrFail($request->id)->update(['status'=>$status]);

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
    

    
   


    
    

}
