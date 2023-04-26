<?php

namespace App\Http\Controllers\Admin;


use App\DeliveryLocation;
use App\Region;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use App\User;
use App\Zone;
use App\ZoneTranslation;
use App;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DeliveryLocationController extends Controller
{
    use ResponceTrait;

    /**
     * @var Contact
     */
    private $deliveryLocation;
    /**
     * @var string
     */
    protected $method;
    protected $user;
    /**
     * @var
     */
    protected $validationRules;

    public function __construct(Request $request,DeliveryLocation $deliveryLocation,User $user)
    {
        parent::__construct();
        $this->deliveryLocation = $deliveryLocation;
        $this->user = $user;
        $this->method=$request->method();
        $this->validationRules = $this->deliveryLocation->rules($this->method);
    }





    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $zone_id =  0;
        $vendor = [];
        $shoper = [];
        $driver = [];
        $delivery_charges = 0;
        if ($this->user->can('create', DeliveryLocation::class)) {
            return abort(403,'not able to access');
        }

        $validator = Validator::make($request->all(),$this->deliveryLocation->rules($this->method),$this->deliveryLocation->messages($this->method));

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }else{

            try {
                $input = $request->all();
                //return $input;
                if($request->filled('region_id')){
                    $region =   Region::findOrFail($request->region_id);
                    $input['lat'] =$region->lat;
                    $input['lng'] =$region->lng;
                }
                if($request->has('description') && $input['description'] != ''){
                    $input['description'] = $request->input('description');
                }else{
                    $input['description'] = $request->input('address');
                }
                $data =  $this->deliveryLocation->updateOrCreate(['id'=>$input['shipping_location']],$input);
                if(isset($data->id)){
                    $deliveryLocation = DeliveryLocation::findOrFail($data->id);
                    $lat = $deliveryLocation->lat;
                    $lng = $deliveryLocation->lng;
                    //return $lat.'-----'. $lng;
                    $zonedata = $this->getZoneData($lat, $lng);
                    //return $zonedata;
                    if(isset($zonedata)){
                        $zone_id =  $zonedata['zone_id'];
                        $delivery_charges =  $zonedata['delivery_charges'];
                        $vendor= $this->user->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor'])->select(['name','id'])->first();
                        $shoper= $this->user->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'shoper'])->select(['name','id'])->first();
                        $driver= $this->user->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'driver'])->select(['name','id'])->first();
                    }
                    $data['driver'] = $driver;
                    $data['shoper'] = $shoper;
                    $data['vendor'] = $vendor;
                    $data['zone_id'] = $zone_id;
                    $data['delivery_charges']= $delivery_charges;
       
                }

            } catch (\Exception $e) {
                return $this->clientErrorResponse($e);
            }
            return $this->showResponse($data);
        }
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


    public  function getDeliveryAddressById(Request $request){
        $request->request->remove('_token');
        $deliveryLocation = $this->deliveryLocation->select('*');
        foreach ($request->all() as $key=>$item){
            $deliveryLocation->where([$key=>$item]);
        }
        $deliveryLocation = $deliveryLocation->first();

        if($deliveryLocation){
            return response()->json([
                'status' => true,
                'message' => 'successfully',
                'data'=>$deliveryLocation
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'no record found'
            ],400);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,$id)
    {

        $validator = Validator::make($request->all(),$this->deliveryLocation->rules($this->method),$this->deliveryLocation->messages($this->method));

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }else{
            try {

               $deliveryLocation =  $this->deliveryLocation->findOrFail($id);
                $input = $request->all();
               // $input['user_id']=Auth::guard('api')->user()->id;
                $deliveryLocation->fill($input)->save();
                return $this->showResponse($deliveryLocation);

            } catch (\Exception $e) {
                return $this->clientErrorResponse($e);
            }


        }
    }

    public function destroy($id){
        $this->deliveryLocation->destroy($id);
        return back();
    }

}
