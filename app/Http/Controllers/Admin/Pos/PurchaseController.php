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
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;

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
        $suppliers=Supplier::where('status','=','1')->pluck('company_name','id');
        $payment_mode = ['cash'=>'Cash','cheque'=>'Cheque','online'=>'Online'];
        $payment_status = ['paid'=>'Paid','due'=>'Due'];
        return view('admin/pages/pos/purchase//add')->with('validator',$validator)->with('brands',$brands)->with('vendors',$vendors)->with('suppliers',$suppliers)->with('payment_mode',$payment_mode)->with('payment_status',$payment_status);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        
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
    
}