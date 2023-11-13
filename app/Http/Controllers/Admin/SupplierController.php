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
use App\SupplierBillPurchase;
use App\SuppliersPayment;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;

class SupplierController extends Controller
{
    protected $model;
    protected $user;
    protected $method;
    function __construct(Request $request, Supplier $model, User $user)
    {
        parent::__construct();
        $this->model = $model;
        $this->user = $user;
        $this->method = $request->method();
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
        $this->types = ['debit' => 'Debit', 'credit' => 'Credit'];
    }

    public function index()
    {
        if ($this->user->can('view', Supplier::class)) {
            return abort(403, 'not able to access');
        }
        //$slug =  \Request::segment(2);
        $title = 'Suppliers';
        return view('admin/pages/supplier/index')->with('title', $title);
    }

    public function create()
    {
        if ($this->user->can('create', Supplier::class)) {
            return abort(403, 'not able to access');
        }
        $validator = JsValidatorFacade::make($this->model->rules('POST'));
        return view('admin/pages/supplier/add')->with('validator', $validator)->with('states', $this->states)->with('types', $this->types);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'company_name' => 'required',
                'gstin_number' => 'required|unique:suppliers,gstin_number',

            ],
            [
                'gstin_number.unique' => "This GSTIN number is already exists"
            ]
        );
        // $validator = Validator::make($request->all(),$this->model->rules($this->method),$this->model->messages($this->method));

        if ($validator->fails()) {
            return redirect('admin/supplier/create')
                ->withErrors($validator)
                ->withInput();
        } else {

            try {
                $this->model->create($input);
                Session::flash('success', 'Supplier create successful');
            } catch (\Exception $e) {
                Session::flash('danger', $e->getMessage());
            }
            return back();
        }
    }

    public function edit($id)
    {
        $supplier = $this->model->findOrFail($id);
        $validator = JsValidatorFacade::make($this->model->rules('POST'));
        return view('admin/pages/supplier/edit')->with('supplier', $supplier)->with('states', $this->states)->with('types', $this->types)->with('validator', $validator);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), $this->model->rules($this->method), $this->model->messages($this->method));


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        } else {
            $locales = config('translatable.locales');

            $supplier = $this->model->findOrFail($id);
            $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);

            return redirect()->route('supplier.index')->with('success', 'Supplier Update successful');
        }
    }

    public function destroy($id)
    {
        /*print_r((new Helper())->delete_cat($this->model->all(),$id,'',''));*/
        $purchase_id = Helper::delete_cat($this->model->all(), $id, '', '');

        $flight = $this->model->whereIn('id', $purchase_id)->delete();
        if ($flight) {
            return response()->json([
                'status' => true,
                'message' => 'deleted'
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'some thing is wrong'
            ], 400);
        }
    }

    public function anyData(Request $request)
    {
        $supplier = $this->model->select('*');
        $supplier->get();
        return Datatables::of($supplier)
            ->addColumn('company_name', function ($supplier) {
                return '<a href="' . route("admin.supplier.view", $supplier->id) . '" class="text-primary">' . $supplier->company_name . '</a>';
            })

            ->addColumn('created_at', function ($user) {
                return date('d/m/Y', strtotime($user->created_at));
            })
            ->addColumn('action', function ($supplier) {
                return '<a href="' . route("supplier.edit", $supplier->id) . '" class="btn btn-success">Edit</a><button type="button" onclick="deleteRow(' . $supplier->id . ')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" supplier-id="' . $supplier->id . '" ' . (($supplier->status == 1) ? "checked" : "") . ' value="' . $supplier->status . '" >';
            })
            ->addColumn('state', function ($supplier) {
                if ($supplier->state != '' && $supplier->state != null) {
                    return $this->states[$supplier->state];
                } else {
                    return $supplier->state;
                }
            })
            ->rawColumns(['company_name', 'action'])
            ->make(true);
    }

    public function changeStatus(Request $request)
    {

        if ($request->status == 1 || $request->status == '1') {
            $status = '0';
        } else {
            $status = '1';
        }

        $supplier = $this->model->findOrFail($request->id)->update(['status' => $status]);

        if ($request->ajax()) {
            if ($supplier) {
                return response()->json([
                    'status' => true,
                    'message' => 'update'
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'some thing is wrong'
                ], 400);
            }
        }
    }

    public function view(Request $request, Supplier $Supplier)
    {
        $currentSection = 'sidebarContacts';
        $states = $this->states;
        return view('admin.pages.supplier.view', compact('Supplier','currentSection','states'));
    }

    public function supplierViewTabs(Request $request){
        if($request->ajax()){
            dd("Working");
        }
    }
    public function supplierBillDtList(Request $request){
        if($request->ajax()){
            
                $_order = request('order');
                $_columns = request('columns');
                $order_by = $_columns[$_order[0]['column']]['name'];
                $order_dir = $_order[0]['dir'];
                $search = request('search');
                $skip = request('start');
                $take = request('length');
                $query = SupplierBillPurchase::where('supplier_id',$request->supplier_id);
                $recordsTotal = $query->count();
                $recordsFiltered = $query->count();
        
                if (isset($search['value'])) {
                    // $query->where('supplier','%'.$search['value'].'%');
                    $query->where('invoice_no','LIKE',"%{$search['value']}%");
            
                    };
            
                $data = $query
                    ->orderBy($order_by, $order_dir)->skip($skip)->take($take)->get();
                    // $data = $query->skip($skip)->take($take)->orderByRaw("$order_by $order_dir")->get();
        
                $i = 1;
                foreach ($data as &$d) {
                    $d->sr_no=$i;
                   $d->invoice_no=$d->invoice_no;
                   $d->bill_date=$d->bill_date;
                   $d->due_date=$d->due_date;
                   $d->net_amount=$d->net_amount;
                   $d->paid_amount=$d->paid_amount;
                   $d->due_amount=$d->due_amount;
                   $d->action='action';
                   $i=$i+1;
                }
        
                return [
                    "draw" => request('draw'),
                    "recordsTotal" => $recordsTotal,
                    'recordsFiltered' => $recordsFiltered,
                    "data" => $data,
                ];
            
        }
    }

    public function supplierPaymentDtList(Request $request){
        if($request->ajax()){
            
            $_order = request('order');
            $_columns = request('columns');
            $order_by = $_columns[$_order[0]['column']]['name'];
            $order_dir = $_order[0]['dir'];
            $search = request('search');
            $skip = request('start');
            $take = request('length');
            $query = SuppliersPayment::where('supplier_id',$request->supplier_id);
            $recordsTotal = $query->count();
            $recordsFiltered = $query->count();
    
            if (isset($search['value'])) {
                // $query->where('supplier','%'.$search['value'].'%');
                $query->where('payment_no','LIKE',"%{$search['value']}%");
        
                };
        
            $data = $query
                ->orderBy($order_by, $order_dir)->skip($skip)->take($take)->get();
                // $data = $query->skip($skip)->take($take)->orderByRaw("$order_by $order_dir")->get();
    
            $i = 1;
            foreach ($data as &$d) {
                $d->sr_no=$i;
               $d->payment_no=$d->payment_no;
               $d->payment_date=$d->payment_date;
               $d->payment_mode=ucfirst($d->payment_mode);
               $d->amount=$d->amount;
               $d->action='action';
               $i=$i+1;
            }
    
            return [
                "draw" => request('draw'),
                "recordsTotal" => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                "data" => $data,
            ];
        
    }
    }
}
