<?php

namespace App\Http\Controllers\Admin;


use App\Scopes\StatusScope;
use App\User;
use App\Zone;
use App\ZoneTranslation;
use App;
Use DB;
use App\DeliveryLocation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class ZoneController extends Controller
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
        return view('admin/pages/zone/index')->with('zones',$zones);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', Zone::class)) {
            return abort(403,'not able to access');
        }
        $zones = [];
        $zones =$this->model->selectRaw(' AsText(point) as points')->withoutGlobalScope(StatusScope::class)->get();

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
        //return $inputs;
        

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
        $validator = JsValidatorFacade::make($this->model->rules('PUT'));
        $zone=$this->model->selectRaw('*, AsText(point) as points')->withoutGlobalScope(StatusScope::class)->findOrFail($id);
       //print_r($zone);die;
       

        $zones=$this->model->selectRaw(' AsText(point) as points')->withoutGlobalScope(StatusScope::class)->where('id','!=',$id)->get();
        //dd($zones->toJson());
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

        $input = $request->all();
        $validator = Validator::make($request->all(),$this->model->rules($this->method),$this->model->messages($this->method));

        if($request->ajax()){
            try {
                $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
                $responce=response()->json([
                    'status' => true,
                    'message' => 'update'
                ],200);
            } catch (\Exception $e) {
                $responce=response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
                ],200);
            }
            return $responce;
        }

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{

            $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('zone.index')->with('success','zone update successful');
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

        $flight = $this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id);
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
    public function anyData()
    {
        //App::setLocale('in');
        //$zone = $this->model->getNewTranslation('in')->get();
        $zone = $this->model->get();
        //$zone =zone::query();
        return Datatables::of($zone)
          ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($zone){
                return '<a href="'.route("zone.edit",$zone->id).'" class="btn btn-success">Edit</a><button type="button" onclick="deleteRow('.$zone->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" zone-id="'.$zone->id.'" '.(($zone->status==1) ? "checked" : "") . ' value="'.$zone->status.'" ><button type="button" onclick="makeDefault('.$zone->id.')" class="btn btn-info">'.($zone->is_default==1 ? 'Defaulted':'Default').'</button>';
            })
            ->rawColumns(['action'])
            ->make(true);

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

        $user= $this->model->findOrFail($request->id)->update(['status'=>$status]);

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

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeDefault(Request $request){

        $this->model->where(['is_default'=>'1'])->update(['is_default'=>'0']);

        $user = $this->model->findOrFail($request->id)->update(['is_default'=>'1']);

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

    public function getZoneDetailsById(Request $request)
    {

        $vendor= $this->user->whereRaw('FIND_IN_SET('.$request->id.', zone_id) ')->where(['user_type'=>'vendor'])->select(['name','id'])->first();
        $shoper= $this->user->whereRaw('FIND_IN_SET('.$request->id.', zone_id) ')->where(['user_type'=>'shoper'])->select(['name','id'])->first();
        $driver= $this->user->whereRaw('FIND_IN_SET('.$request->id.', zone_id) ')->where(['user_type'=>'driver'])->select(['name','id'])->first();

        return response()->json([
            'status' => true,
            'message' => 'update',
            'data'=>['driver'=>$driver,'shoper'=>$shoper,'vendor'=>$vendor]
        ],200);
    }

    public function isPointInPolygon($latitude, $longitude, $latitude_array, $longitude_array) {
    $size = count($longitude_array);
    $flag1 = false;
    $k = $size - 1;
    $j = 0;
    while ($j < $size) {
        $flag = false;
        $flag2 = false;
        $flag3 = false;
        if ($latitude_array[$j] > $latitude) {
            $flag2 = true;
        } else {
            $flag2 = false;
        }
        if ($latitude_array[$k] > $latitude) {
            $flag3 = true;
        } else {
            $flag3 = false;
        }
        $flag = $flag1;
        if ($flag2 != $flag3) {
            $flag = $flag1;
            if ($longitude < (($longitude_array[$k] - $longitude_array[$j]) * ($latitude - $latitude_array[$j])) / ($latitude_array[$k] - $latitude_array[$j]) +
                $longitude_array[$j]) {
                if (!$flag1) {
                    $flag = true;
                } else {
                    $flag = false;
                }
            }
        }
        $k = $j;
        $j++;
        $flag1 = $flag;
    }
    return $flag1;
}
     public function loadZoneByLat(Request $request)
    {
        $deliveryLocation = DeliveryLocation::findOrFail($request->id);
        $lat = $deliveryLocation->lat;
        $lng = $deliveryLocation->lng;
        //return $lat.'-----'. $lng;
        $zone_id =  0;
        $vendor = 0;
        $shoper = 0;
        $driver = 0;
        $delivery_charges = 0;
        $zone = $this->getZoneData($lat, $lng);
        //return $zone;
        //return $zone['zone_id'];

        //$zone = Zone::whereRaw('CONTAINS(point, point('.$lat.','.$lng.'))')->first();
        //return $zone;
        if(isset($zone)){
           // $zone_id =  $zone->id;
            $zone_id =  $zone['zone_id'];
            $delivery_charges =  $zone['delivery_charges'];
            $vendor= $this->user->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor'])->select(['name','id'])->get();
            $shoper= $this->user->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'shoper'])->select(['name','id'])->first();
            $driver= $this->user->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'driver'])->select(['name','id'])->first();
            }
        return response()->json([
            'status' => true,
            'message' => 'update',
            'data'=>['driver'=>$driver,'shoper'=>$shoper,'vendor'=>$vendor,'zone_id'=>$zone_id,'delivery_charges'=>$delivery_charges]
        ],200);
    }
     public function getZoneData($lat, $lng)
    {
        $zone_id = '';
        $zoneArray = [];
        $zArray = [];
        $fArray = [];
        $finalArray = [];
      
        $zonedata = DB::table('zones')->select('id',DB::raw("ST_AsGeoJSON(point) as json"),'delivery_charges' )->where('deleted_at',null)->where('status','=','1')->get();
      
            $json_arr = json_decode($zonedata, true);
            foreach ($json_arr as $zvalue) {
                $zone_id=$zvalue['id'];
                $delivery_charges=$zvalue['delivery_charges'];
                $json=json_decode($zvalue['json']);
                $coordinates=$json->coordinates;
                $new_coordinates=$coordinates[0];
                $lat_array=array();
                $lng_array=array();
                foreach($new_coordinates as $new_coordinates_value){
                    $lat_array[]=$new_coordinates_value[0];
                    $lng_array[]=$new_coordinates_value[1];

                }
           
            $is_exist = $this->isPointInPolygon($lat, $lng,$lat_array,$lng_array);
           
            if($is_exist){
                $zData = ZoneTranslation::where('zone_id', $zone_id)->where('locale', App::getLocale())->first();
                $data['match_in_zone'] = true;
                $data['zone_id'] = $zone_id;
                $data['zone_name'] = $zData->name;
                $data['delivery_charges'] = $delivery_charges;
                return $data;
            }

            }
            
            $zone_id_default = 0;
            
            $zData = ZoneTranslation::where('zone_id', $zone_id_default)->where('locale', App::getLocale())->first();
            $data['match_in_zone'] = false;
            $data['zone_id'] = $zone_id_default;
            $data['delivery_charges'] = 0;
            return $data;
       
       

       
    }

}