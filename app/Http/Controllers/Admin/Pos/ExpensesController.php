<?php

/**
 * @Author: abhi
 * @Date:   2021-09-06 16:29:37
 * @Last Modified by:   abhi
 * @Last Modified time: 2021-09-06 20:21:56
 */
namespace App\Http\Controllers\Admin\Pos;

use App\Expenses;
use App\Helpers\Helper;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;

class ExpensesController extends Controller
{
    protected $model;
    protected $user;
    protected $method;
    function __construct(Request $request,Expenses $model,User $user)
    {
        parent::__construct();
        $this->model=$model;
        $this->user=$user;
        $this->method=$request->method();
    }

    public function index() {
    	if ($this->user->can('view', Expenses::class)) {
        	return abort(403,'not able to access');
        }
        //$slug =  \Request::segment(2);
        $title = 'Expenses';
        return view('admin/pages/pos/expenses/index')->with('title',$title);
    }

    public function create()
    {
        if ($this->user->can('create', Expenses::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->model->rules('POST'));
        return view('admin/pages/pos/expenses//add')->with('validator',$validator);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(),$this->model->rules($this->method),$this->model->messages($this->method));

        if ($validator->fails()) {
            return redirect('admin/pos/expenses/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            
            try {
                $input['date'] = date('Y-m-d');
                $this->model->create($input);
                Session::flash('success','Expenses create successful');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
            }
            return back();
        }
    }

    public function edit($id)
    {
        $expenses=$this->model->findOrFail($id);
        return view('admin/pages/pos/expenses/edit')->with('expenses',$expenses);
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

            
            $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('expenses.index')->with('success','Expenses Upload successful');
        }
    }

    public function destroy($id)
    {
       /*print_r((new Helper())->delete_cat($this->model->all(),$id,'',''));*/
       $expenses_id=Helper::delete_cat($this->model->all(),$id,'','');

        $flight = $this->model->whereIn('id',$expenses_id)->delete();
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
        $expenses = $this->model->select('*');
        

        $expenses->get();
        return Datatables::of($expenses)
            ->addColumn('date',function ($user){
                return date('d/m/Y',strtotime($user->date));
            })
            ->addColumn('action',function ($expenses){
                return '<a href="'.route("expenses.edit",$expenses->id).'" class="btn btn-success">Edit</a><button type="button" onclick="deleteRow('.$expenses->id.')" class="btn btn-danger">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);

    }
    
}