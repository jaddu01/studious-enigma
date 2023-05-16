<?php

namespace App\Http\Controllers\Admin;


use App\Scopes\StatusScope;
use App\User;
use App\WeekPackage;
use App\Zone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class OperationController extends Controller
{
    protected $model;
    protected $user;
    protected $method;
    function __construct(Request $request,Zone $model,User $user)
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
        if ($this->user->can('view', Zone::class)) {
            return abort(403,'not able to access');
        }
        $zones=$this->model->selectRaw(' AsText(point) as points')->withoutGlobalScope(StatusScope::class)->get();
        return view('admin/pages/zone/operation')->with('zones',$zones);
    }
    public function show()
    {
        if ($this->user->can('view', Zone::class)) {
            return abort(403,'not able to access');
        }
       /* $zones=$this->model->selectRaw(' AsText(point) as points')->withoutGlobalScope(StatusScope::class)->get();*/
        return view('admin/pages/zone/operation_view');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', zone::class)) {
            return abort(403,'not able to access');
        }
        $zones=$this->model->selectRaw(' AsText(point) as points')->withoutGlobalScope(StatusScope::class)->get();
        $validator = JsValidatorFacade::make($this->model->rules('POST'));
        return view('admin/pages/zone/add')->with('validator',$validator)->with('zones',$zones);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs= $request->all();

        $inputs['code']=uniqid('z');

        $validator = Validator::make($inputs,$this->model->rules($this->method),$this->model->messages($this->method));

        if ($validator->fails()) {
            return redirect('admin/zone/create')
                ->withErrors($validator)
                ->withInput();
        }else{

            try {
                $this->model->create($inputs);
                Session::flash('success','zone create successful');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
            }

            return back();
        }

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $validator = JsValidatorFacade::make($this->model->rules('PUT'));
        $zone=$this->model->selectRaw('*, AsText(point) as points')->withoutGlobalScope(StatusScope::class)->findOrFail($id);

        $zones=$this->model->selectRaw(' AsText(point) as points')->withoutGlobalScope(StatusScope::class)->where('id','!=',$id)->get();
       // dd($zones->toJson());
        return view('admin/pages/zone/edit')->with('zone',$zone)->with('validator',$validator)->with('zones',$zones);
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
        if (!empty($request->old_user_id)) {
        $old_user = $this->user->withoutGlobalScope(StatusScope::class)->find($request->old_user_id);
        if($old_user){
            $old_Valye = $old_user->zone_id;

            unset($old_Valye[array_search($request->zone_id, $old_Valye)]);

            $zone_id = trim(implode(',', $old_Valye),',');

            $old_user->update(['zone_id' => $zone_id]);
        }
        }
        DB::beginTransaction();

        try {

            $user = $this->user->withoutGlobalScope(StatusScope::class)->findOrFail($id);

            $old_Valye = $user->zone_id;
            array_push($old_Valye,$request->zone_id);
            $zone_id = trim(implode(',',array_unique($old_Valye)),',');

            $user->update(['zone_id'=>$zone_id]);
            $responce=response()->json([
                'status' => true,
                'message' => 'update'
            ],200);
            DB::commit();
        } catch (\Exception $e) {
            $responce=response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ],200);
            DB::rollBack();
        }
        return $responce;



    }

    public function operationData(Request $request)
    {

        $zone = $this->model->get();
        $start = $request->start;
        $users = $this->user->where('role','=','user')->get();
        $venders =$users->where('user_type','=','vendor');
        $shopers =$users->where('user_type','=','shoper');
        $drivers =$users->where('user_type','=','driver');

        $weekPackages=WeekPackage::all();

        return Datatables::of($zone,$users)

            ->addColumn('Slno',function () use(& $start) {
                return $start = $start+1;
            })
            ->addColumn('vendor',function ($zone)use($venders){

                $old_selected='Vendor';
                $html = '';
                foreach ($venders as $vender){
                    if(in_array($zone->id,$vender->zone_id)){
                        $old_selected=$vender->name;
                    }
                    //$html.='<input value="'.$vender->name.'">';
                }
                $html='<input type="vendor_id"  value="'.$old_selected.'">';

                return $old_selected;
            })
            ->addColumn('shopper',function ($zone)use($shopers){
                $shoper_selected='Shopper';
                foreach ($shopers as $shoper){
                    if(in_array($zone->id,$shoper->zone_id)){
                        $shoper_selected=$shoper->name;
                    }
                    
                }
               
                return $shoper_selected;
            })
            ->addColumn('driver',function ($zone)use($drivers){
                $old_selected='Driver';

              
                foreach ($drivers as $driver){
                    if(in_array($zone->id,$driver->zone_id)){
                        $old_selected=$driver->name;
                    }
                  
                }
               

                return $old_selected;
            })
            ->addColumn('delivery_charges',function ($zone){
               

                return $zone->delivery_charges;
            })
            ->addColumn('delivery_times',function ($zone)use($weekPackages){

                $old_selected = 'Delivery Times';
                foreach ($weekPackages as $weekPackage){
                    if($zone->package_id==$weekPackage->id){
                       $old_selected =  $weekPackage->name;
                    }
                   
                }
               

                return $old_selected;
            })
            ->rawColumns(['vendor','shopper','driver','delivery_charges','delivery_times'])
            ->make(true);

    }

    /**
     * @return mixed
     */
    public function anyData(Request $request)
    {

        $zone = $this->model->get();
        $start = $request->start;
        $users = $this->user->where('role','=','user')->get();
        $venders =$users->where('user_type','=','vendor');
        $shopers =$users->where('user_type','=','shoper');
        $drivers =$users->where('user_type','=','driver');

        $weekPackages=WeekPackage::all();

        return Datatables::of($zone,$users)

            ->addColumn('Slno',function () use(& $start) {
                return $start = $start+1;
            })
            ->addColumn('vendor',function ($zone)use($venders){
                $old_selected='';
                $html = '<select name="vendor_id" class="form-control" onchange="updateUserZone(this,'.$zone->id.' ,$(this).val())" multiple><option value="">Vendor</option>';
                foreach ($venders as $vender){
                    if(in_array($zone->id,$vender->zone_id)){
                        $old_selected=$vender->id;
                    }
                    $html.='<option value="'.$vender->id.'" '.(in_array($zone->id,$vender->zone_id) ? "selected":"") .' >'.$vender->name.' '.$vender->last_name.'</option>';
                }
                $html.='</select><input type="hidden"  value="'.$old_selected.'">';

                return $html;
            })
            ->addColumn('shopper',function ($zone)use($shopers){
                $old_selected='';
                $html = '<select name="shopper_id" class="form-control " onchange="updateUserZone(this,'.$zone->id.',$(this).val())" ><option value="">shopper</option>';
                foreach ($shopers as $shoper){
                    if(in_array($zone->id,$shoper->zone_id)){
                        $old_selected=$shoper->id;
                    }
                    $html.='<option value="'.$shoper->id.'" '.(in_array($zone->id,$shoper->zone_id) ? "selected" : "" ) .' >'.$shoper->name.' '.$shoper->last_name.'</option>';
                }
                $html.='</select><input type="hidden"  value="'.$old_selected.'">';

                return $html;
            })
            ->addColumn('driver',function ($zone)use($drivers){
                $old_selected='';

                $html = '<select name="driver_id" class="form-control " onchange="updateUserZone(this,'.$zone->id.',$(this).val())"><option value="">driver</option>';
                foreach ($drivers as $driver){
                    if(in_array($zone->id,$driver->zone_id)){
                        $old_selected=$driver->id;
                    }
                    $html.='<option value="'.$driver->id.'" '.(in_array($zone->id,$driver->zone_id) ? "selected": "") .' >'.$driver->name.' '.$driver->last_name.'</option>';
                }
                $html.='</select><input type="hidden"  value="'.$old_selected.'">';

                return $html;
            })
            ->addColumn('delivery_charges',function ($zone){
               //$html='<div>'.$zone->delivery_charges.'</div>';
                $html='<input type="text" name="delivery_charges" class="form-control" onchange="updateDelivaryCharges($(this).val(),'.$zone->id.')"  value="'.$zone->delivery_charges.'">';
                return $html;

                return $html;
            })
            ->addColumn('minimum_order_amount',function ($zone){
               //$html='<div>'.$zone->delivery_charges.'</div>';
                $html='<input type="text" name="minimum_order_amount" class="form-control" onchange="updateMinimumOrderAmount($(this).val(),'.$zone->id.')"  value="'.$zone->minimum_order_amount.'">';
                return $html;

                return $html;
            })
            ->addColumn('delivery_times',function ($zone)use($weekPackages){

                $html = '<select name="package_id" class="form-control " onchange="updateDeliveryTimes($(this).val(),'.$zone->id.')"><option value="">delivery times</option>';
                foreach ($weekPackages as $weekPackage){

                    $html.='<option value="'.$weekPackage->id.'" '.(($zone->package_id==$weekPackage->id) ? "selected": "") .' >'.$weekPackage->name.' </option>';
                }
                $html.='</select>';

                return $html;
            })
            ->rawColumns(['vendor','shopper','driver','delivery_charges','minimum_order_amount','delivery_times'])
            ->make(true);

    }
    /**
     * To track view of all drivers.*/

    public function viewTracking()
    {
        if ($this->user->can('view', Zone::class)) {
            return abort(403,'not able to access');
        }
        //$zones=User::where('status','=','1')->where('user_type','driver')->select('current_lat','current_lng','name','id')->get();
        $zones=User::where('status','=','1')->where('user_type','shoper')->select('current_lat','current_lng','name','id')->get();
        //return $zones;
        return view('admin/pages/zone/view-tracking')->with('zones',$zones);
    }

     public function realTimeTracking()
    {

      
    }

    
}
