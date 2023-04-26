<?php

/**
 * @Author: abhi
 * @Date:   2021-10-10 14:21:10
 * @Last Modified by:   abhi
 * @Last Modified time: 2021-10-10 16:16:20
 */
namespace App\Http\Controllers\Admin;

use App\CoinSettings;
use App\Helpers\Helper;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class CoinSettingsController extends Controller
{
    protected $model;
    protected $user;
    protected $method;
    function __construct(Request $request,CoinSettings $model,User $user)
    {
        parent::__construct();
        $this->model=$model;
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
        if ($this->user->can('view', CoinSettings::class)) {
        	return abort(403,'not able to access');
        }
        //$slug =  \Request::segment(2);
        $title = 'Coin Settings';
        return view('admin/pages/coin-settings/index')->with('title',$title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', CoinSettings::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->model->rules('POST'));
        return view('admin/pages/coin-settings/add')->with('validator',$validator);
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
        $validator = Validator::make($request->all(),$this->model->rules($this->method),$this->model->messages($this->method));

        if ($validator->fails()) {
            return redirect('admin/coin-settings/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            try {
                $this->model->create($input);
                Session::flash('success','Coin Setting create successful');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $coinSetting=$this->model->findOrFail($id);
        return view('admin/pages/coin-settings/edit')->with('coinSetting',$coinSetting);
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
        $validator = Validator::make($request->all(),$this->model->rules($this->method),$this->model->messages($this->method));


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('coin-settings.index')->with('success','Coin Setting Upload successful');
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
       /*print_r((new Helper())->delete_cat($this->model->all(),$id,'',''));*/
       $coin_setting_id=Helper::delete_cat($this->model->all(),$id,'','');

        $flight = $this->model->whereIn('id',$coin_setting_id)->delete();
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
        $coinSetting =$this->model->select('*');
        

        $coinSetting->get();
        return Datatables::of($coinSetting)
            ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($coin){
                return '<a href="'.route("coin-settings.edit",$coin->id).'" class="btn btn-success">Edit</a><button type="button" onclick="deleteRow('.$coin->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" coin-setting-id="'.$coin->id.'" '.(($coin->status==1) ? "checked" : "") . ' value="'.$coin->status.'" >';
            })
            ->rawColumns(['image','action'])
            ->make(true);

    }

      /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Request $request){

        if($request->status==1 || $request->status=='1'){
            $status=0;
        }else{
            $status=1;
        }
		$coin = $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($request->id)->update(['status'=>$status]);

        if($request->ajax()){
            if($coin){
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