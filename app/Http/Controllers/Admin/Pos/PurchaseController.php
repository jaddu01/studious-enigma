<?php

/**
 * @Author: abhi
 * @Date:   2021-09-06 22:56:19
 * @Last Modified by:   Abhi Bhatt
 * @Last Modified time: 2022-01-29 01:10:38
 */
namespace App\Http\Controllers\Admin\Pos;

use App\Purchase;
use App\BrandTranslation;
use App\Brand;
use App\ProductTranslation;
use App\Product;
use App\VendorProduct;
use App\Supplier;
use App\Helpers\Helper;
use App\Helpers\ResponseBuilder;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierProductResource;
use App\SupplierBillPurchase;
use App\SupplierPurchaseAdditionalCharge;
use App\SupplierPurchaseProductDetail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    protected $model;
    protected $user;
    protected $method;
    function __construct(Request $request,Purchase $model,User $user)
    {
        parent::__construct();
        $this->model=$model;
        $this->user=$user;
        $this->method=$request->method();
    }

    public function index() {
    	if ($this->user->can('view', Purchase::class)) {
        	return abort(403,'not able to access');
        }
        //$slug =  \Request::segment(2);
        $title = 'Purchase';
        return view('admin/pages/pos/purchase/index')->with('title',$title);
    }

    public function create()
    {
        if ($this->user->can('create', Purchase::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->model->rules('POST'));
        $brands=Brand::where('status','=','1')->listsTranslations('name','id')->pluck('name','id')->all();
        $vendors=$this->user->where(['user_type'=>'vendor','role'=>'user'])->pluck('name','id');
        $suppliers=Supplier::where('status','=','1')->orderBy('id','DESC')->pluck('company_name','id');
        $payment_mode = ['cash'=>'Cash','cheque'=>'Cheque','online'=>'Online'];
        $payment_status = ['paid'=>'Paid','due'=>'Due'];
        return view('admin/pages/pos/purchase/add')->with('validator',$validator)->with('brands',$brands)->with('vendors',$vendors)->with('suppliers',$suppliers)->with('payment_mode',$payment_mode)->with('payment_status',$payment_status);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        // dd($input);
        
        $validator = Validator::make($request->all(),$this->model->rules($this->method),$this->model->messages($this->method));

        if ($validator->fails()) {
            return redirect('admin/pos/purchase/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            
            try {
                //$input['date'] = date('Y-m-d');
                $product = Product::select('gst')->where('id','=',$input['product_id'])->first();
                $input['total_amount'] = $input['price']*$input['quantity'];
                $gst = $product->gst;
                $total_gst = ($gst / 100) * $input['total_amount'];
                $input['gst'] = $total_gst;
                $this->model->create($input);
                $vendor_product = VendorProduct::where('id','=',$input['vendor_id'])->where('product_id','=',$input['product_id'])->first();
                $quantity = $vendor_product['qty']+$input['quantity'];
                VendorProduct::where('user_id',$input['vendor_id'])->
                    where('product_id',$input['product_id'])->
                    update(['qty'=>$quantity]);
                Session::flash('success','Purchase create successful');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
            }
            return back();
        }
    }

    public function SaveSupplierPurchase(Request $request){
        try {
            if($request->ajax()){
                DB::beginTransaction();
                // dd($request->data['supplier_id']);
               $supplier_id = $request->data['supplier_id'];
               $supplier_bill_purchase_order = SupplierBillPurchase::create([
                    "supplier_id"=>$supplier_id,
                    "bill_date"=>$request->data['bill_date'],
                    "due_date"=>$request->data['bill_date'],
                    "bill_amount"=>$request->data['bill_amount'],
                    "invoice_no"=>$request->data['invoice_no'],
                    "reference_bill_no"=>$request->data['reference_bill_no'],
                    "payment_term"=>$request->data['payment_term'],
                    "tax_type"=>$request->data['tax_type'],
                    "tax_type"=>$request->data['tax_type'],
                    "net_amount"=>$request->data['net_amount'],
                    "due_amount"=>$request->data['net_amount'],
                    "total_amount"=>$request->data['total_amount'],
                    "total_additional_charge"=>$request->data['total_additional_charge']
                ]);

                foreach($request->data['additional_charges'] as $additionalCharge){
                    SupplierPurchaseAdditionalCharge::create([
                    'supplier_bill_id'=>$supplier_bill_purchase_order->id,
                    'supplier_id'=>$supplier_id,
                    'charge_name'=>$additionalCharge['charge_name'],
                    'charge'=>$additionalCharge['charge_value']

                    ]);
                }

                //  SupplierPurchaseProductDetail::create([
                //         'supplier_id'=>$supplier_id,
                //         'supplier_bill_id'=>$supplier_bill_purchase_order->id,
                //         'product_id'=>1,
                //         'bar_code'=>123455,
                //         'qty'=>1,
                //         'free_qty'=>123,
                //         'unit_cost'=>100,
                //         'selling_price'=>200,
                //         'mrp'=>10,
                //         'net_rate'=>300,
                //         'margin'=>500,
                //         'total'=>300
                //     ]);

                foreach($request->data['products'] as $product){
                   
                        echo $product['product_id'];
                        echo "\n";

                        echo $product['barcode'];
                        echo "\n";

                        echo $product['qty'];
                        echo "\n";

                        echo $product['free_qty'];
                        echo "\n";

                        echo $product['unit_cost'];
                        echo "\n";

                        echo $product['selling_price'];
                        echo "\n";

                        echo $product['mrp'];
                        echo "\n";

                        echo $product['net_rate'];
                        echo "\n";

                        echo $product['margin'];
                        echo "\n";
                        echo $product['total'];
                        echo "\n";


                    SupplierPurchaseProductDetail::create([
                        'supplier_id'=>$supplier_id,
                        'supplier_bill_id'=>$supplier_bill_purchase_order->id,
                        'product_id'=>$product['product_id'],
                        'bar_code'=>$product['barcode'],
                        'qty'=>$product['qty'],
                        'gst_amount'=>$product['gst_amount'],
                        'free_qty'=>$product['free_qty'],
                        'unit_cost'=>$product['unit_cost'],
                        'selling_price'=>$product['selling_price'],
                        'mrp'=>$product['mrp'],
                        'net_rate'=>$product['net_rate'],
                        'margin'=>$product['margin'],
                        'total'=>$product['total'],
                    ]);

                    
                }
              
                DB::commit(); 
            }
            //code...
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::error($th);
           
        }
   
    }

    public function edit($id)
    {
        $purchase=$this->model->findOrFail($id);
        $brands=Brand::where('status','=','1')->listsTranslations('name','id')->pluck('name','id')->all();
        $brand_id = $purchase->brand_id;
        $vendor_id = $purchase->vendor_id;
        $products = Product::join('product_translations',function($joins){
            $joins->on('products.id','=','product_translations.product_id');
        })->where('products.brand_id','=',$brand_id)->where('products.status','=','1')->where('products.brand_id','!=',null)->pluck('product_translations.name','products.id');
        $vendors=$this->user->where(['user_type'=>'vendor','role'=>'user'])->pluck('name','id');
        $suppliers=Supplier::where('status','=','1')->pluck('company_name','id');
        return view('admin/pages/pos/purchase/edit')->with('purchase',$purchase)->with('brands',$brands)->with('products',$products)->with('vendors',$vendors)->with('suppliers',$suppliers);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(),$this->model->rules($this->method),$this->model->messages($this->method));


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            $locales = config('translatable.locales');

            $purchase = $this->model->find($id);
            $old_qunatity = $purchase->quantity;
            $vendor_product = VendorProduct::where('user_id','=',$input['vendor_id'])->where('product_id','=',$input['product_id'])->first();
                $quantity = $vendor_product['qty']+$input['quantity'];
                $quantity = $quantity-$old_qunatity;
                VendorProduct::where('user_id',$input['vendor_id'])->
                    where('product_id',$input['product_id'])->
                    update(['qty'=>$quantity]);
            $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);

            return redirect()->route('purchase.index')->with('success','Purchase Upload successful');
        }
    }

    public function destroy($id)
    {
       /*print_r((new Helper())->delete_cat($this->model->all(),$id,'',''));*/
       $purchase_id=Helper::delete_cat($this->model->all(),$id,'','');

        $flight = $this->model->whereIn('id',$purchase_id)->delete();
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

    public function anyData(Request $request)
    {
        $purchases = $this->model->select('purchases.id','brand_translations.name as brand','product_translations.name as product','purchases.price','purchases.quantity','purchases.date','suppliers.company_name as supplier')->leftJoin('brand_translations','purchases.brand_id','=','brand_translations.id')->leftJoin('product_translations','purchases.product_id','=','product_translations.product_id')->leftJoin('suppliers','purchases.supplier_id','=','suppliers.id');
        

        $purchases->get();
        return Datatables::of($purchases)
            ->addColumn('date',function ($user){
                return date('d/m/Y',strtotime($user->date));
            })
            ->addColumn('action',function ($purchase){
                return '<a href="'.route("purchase.edit",$purchase->id).'" class="btn btn-success">Edit</a><button type="button" onclick="deleteRow('.$purchase->id.')" class="btn btn-danger">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function getProducts(Request $request) {
        $brand_id = $request->brand_id;
        $products = Product::join('product_translations',function($joins){
            $joins->on('products.id','=','product_translations.product_id');
        })->where('products.brand_id','=',$brand_id)->where('products.status','=','1')->where('products.brand_id','!=',null)->pluck('product_translations.name','products.id');
        return response()->json($products);
    }

    public function getBrands(Request $request) {
        $vendor_id = $request->vendor_id;
        $brands = VendorProduct::leftJoin('products','products.id', '=', 'vendor_products.product_id')->leftJoin('brand_translations','brand_translations.brand_id', '=', 'products.brand_id')->where('vendor_products.user_id', '=', $vendor_id)->where('brand_translations.name','!=',null)->pluck('brand_translations.name as name','brand_translations.brand_id as id');
        return response()->json($brands);
    }

    public function getSupplierAddress(Request $request,Supplier $Supplier){
        if($request->ajax()){
            // $supplier = $Supplier->company_name;
            
          return   response()->json([
                'state'=>$Supplier->state,
                'gstin'=>$Supplier->gstin_number,
                'company_name'=>$Supplier->company_name,
                'address'=>$Supplier->address,
                'pincode'=>$Supplier->pin_code,
                'country'=>$Supplier->country,
                'contact_number'=>$Supplier->contact_number,
                'phone_number'=>$Supplier->phone_number
            ]);
        }
    }

    public function getSupplierProducts(Request $request){
        $products = '';
        if($request->type=='barcode'){
        $products=Product::where('barcode','like',"%{$request->search}%")->has('ProductTranslation')->paginate($request->page);
         
            // return ResponseBuilder::success($supplierProducts,'success');
       // return ResponseBuilder::successWithPagination($products,$supplierProducts,"success");
        }
        else if($request->type=='products'){
        $products=Product::whereTranslationLike('name',"%{$request->search}%")->paginate($request->page);

        }

        $supplierProducts = SupplierProductResource::collection($products);
        $response['data'] = $supplierProducts;
        $response['pagination']=[
            "more"=>true,

        ];
        $response['meta']=[
            'total'=>$products->total()
            
        ];
        return response($response);
    }
    
    public function getSupplierProductsInfo(Request $request,Product $product){
        if($request->type=='barcode'){
            $pd = Product::where('barcode',$request->barcode)->first();
            return response()->json([
                'id'=>$pd->id,
                // 'qty'=>$pd->qty,
                 'name'=>$pd->ProductTranslation()->first()->name,
                'mrp'=>$pd->price,
                'unit_cost'=>$pd->per_order,
                'selling_price'=>$pd->best_price,
                'gst_percentage'=>$pd->gst,
                'barcode'=>$pd->barcode
                
                // 'best_price'=>
            ]);
        }
        return response()->json([
            'id'=>$product->id,
            // 'qty'=>$product->qty,
            'name'=>$product->ProductTranslation()->first()->name,
            'mrp'=>$product->price,
            'unit_cost'=>$product->per_order,
            'selling_price'=>$product->best_price,
            'gst_percentage'=>$product->gst,
            'barcode'=>$product->barcode

            
            // 'best_price'=>
        ]);
    }
}