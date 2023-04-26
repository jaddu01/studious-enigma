<?php

/**
 * @Author: abhi
 * @Date:   2021-09-13 23:32:15
 * @Last Modified by:   abhi
 * @Last Modified time: 2021-09-21 00:14:45
 */
namespace App\Http\Controllers\Admin;

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

class SupplierController extends Controller
{
    protected $model;
    protected $user;
    protected $method;
    function __construct(Request $request,Supplier $model,User $user)
    {
        parent::__construct();
        $this->model=$model;
        $this->user=$user;
        $this->method=$request->method();
        $this->states = [
					'AP' => 'Andhra Pradesh',
					'AR' => 'Arunachal Pradesh',
					'AS' => 'Assam',
					'BR' => 'Bihar',
					'CT' => 'Chhattisgarh',
					'GA' => 'Goa',
					'GJ' => 'Gujarat',
					'HR' => 'Haryana',
					'HP' => 'Himachal Pradesh',
					'JK' => 'Jammu and Kashmir',
					'JH' => 'Jharkhand',
					'KA' => 'Karnataka',
					'KL' => 'Kerala',
					'MP' => 'Madhya Pradesh',
					'MH' => 'Maharashtra',
					'MN' => 'Manipur',
					'ML' => 'Meghalaya',
					'MZ' => 'Mizoram',
					'NL' => 'Nagaland',
					'OR' => 'Odisha',
					'PB' => 'Punjab',
					'RJ' => 'Rajasthan',
					'SK' => 'Sikkim',
					'TN' => 'Tamil Nadu',
					'TG' => 'Telangana',
					'TR' => 'Tripura',
					'UP' => 'Uttar Pradesh',
					'UT' => 'Uttarakhand',
					'WB' => 'West Bengal',
					'AN' => 'Andaman and Nicobar Islands',
					'CH' => 'Chandigarh',
					'DN' => 'Dadra and Nagar Haveli',
					'DD' => 'Daman and Diu',
					'LD' => 'Lakshadweep',
					'DL' => 'National Capital Territory of Delhi',
					'PY' => 'Puducherry'
				];
		$this->types = ['debit'=>'Debit','credit'=>'Credit'];
    }

    public function index() {
    	if ($this->user->can('view', Supplier::class)) {
        	return abort(403,'not able to access');
        }
        //$slug =  \Request::segment(2);
        $title = 'Suppliers';
        return view('admin/pages/supplier/index')->with('title',$title);
    }

    public function create()
    {
        if ($this->user->can('create', Supplier::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->model->rules('POST'));
        return view('admin/pages/supplier/add')->with('validator',$validator)->with('states',$this->states)->with('types',$this->types);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(),$this->model->rules($this->method),$this->model->messages($this->method));

        if ($validator->fails()) {
            return redirect('admin/supplier/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            
            try {
                $this->model->create($input);
                Session::flash('success','Supplier create successful');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
            }
            return back();
        }
    }

    public function edit($id)
    {
        $supplier=$this->model->findOrFail($id);
        $validator = JsValidatorFacade::make($this->model->rules('POST'));
        return view('admin/pages/supplier/edit')->with('supplier',$supplier)->with('states',$this->states)->with('types',$this->types)->with('validator',$validator);
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

            $supplier=$this->model->findOrFail($id);
            $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);

            return redirect()->route('supplier.index')->with('success','Supplier Update successful');
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
        $supplier = $this->model->select('*');
        

        $supplier->get();
        return Datatables::of($supplier)
             ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($supplier){
                return '<a href="'.route("supplier.edit",$supplier->id).'" class="btn btn-success">Edit</a><button type="button" onclick="deleteRow('.$supplier->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" supplier-id="'.$supplier->id.'" '.(($supplier->status==1) ? "checked" : "") . ' value="'.$supplier->status.'" >';
            })
            ->addColumn('state',function ($supplier) {
                if($supplier->state!='' && $supplier->state!=null) {
                    return $this->states[$supplier->state];
                } else {
                    return $supplier->state;
                }
                
            })
            ->rawColumns(['action'])
            ->make(true);

    }

    public function changeStatus(Request $request){

        if($request->status==1 || $request->status=='1'){
            $status='0';
        }else{
            $status='1';
        }

        $supplier= $this->model->findOrFail($request->id)->update(['status'=>$status]);

        if($request->ajax()){
            if($supplier){
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