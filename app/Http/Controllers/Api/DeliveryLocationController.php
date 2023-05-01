<?php

namespace App\Http\Controllers\Api;


use App\DeliveryLocation;
use App\Helpers\ResponseBuilder;
use App\Region;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryLocationResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DeliveryLocationController extends Controller
{
    use RestControllerTrait,ResponceTrait;

    const MODEL = 'App\DeliveryLocation';
    /**
     * @var Contact
     */
    private $deliveryLocation;
    /**
     * @var string
     */
    protected $method;
    /**
     * @var
     */
    protected $validationRules;

    public function __construct(Request $request,DeliveryLocation $deliveryLocation)
    {

        parent::__construct();
        $this->deliveryLocation = $deliveryLocation;
        $this->method=$request->method();
        $this->validationRules = $this->deliveryLocation->rules($this->method);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user = Auth::guard('api')->user();
            if(!$user){
                return ResponseBuilder::error("User not found", $this->validationStatus);
            }
            $deliveryLocations = $user->deliveryLocation()->with('region')->latest()->paginate(20);
            $this->response->delivery_locations = DeliveryLocationResource::collection($deliveryLocations);
            return ResponseBuilder::successWithPagination($deliveryLocations,$this->response, $this->successStatus);
            //$deliveryLocation = $this->deliveryLocation->with(['user','region'])->where('user_id','=',Auth::guard('api')->user()->id)->orderBy('updated_at','desc')->get();

        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),$this->deliveryLocation->rules($this->method),$this->deliveryLocation->messages($this->method));

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }else{

            try {
                $input = $request->all();
                $input['user_id']=Auth::guard('api')->user()->id;
                if($request->filled('region_id')){
                    $region =   Region::findOrFail($request->region_id);
                    $input['lat'] =$region->lat;
                    $input['lng'] =$region->lng;
                }

                $data =   $this->deliveryLocation->create($input);

            } catch (\Exception $e) {
                return $this->clientErrorResponse($e);
            }
            return $this->showResponse($data);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request ,$id)
    {
        $location_count=$this->deliveryLocation->where('user_id',Auth::guard('api')->user()->id)->count();
        try {
            if($location_count>1){
            $cart = $this->deliveryLocation->where('id',$id)->where('user_id',Auth::guard('api')->user()->id)->firstOrFail();
            $cart->delete();
            return $this->deletedResponse();

        }else{

            $data['error']=true;
            $data['code']=1;
            $data['message'] = "You cannot delete last address";


            return response()->json($data);

        }
        } catch (\Exception $e) {
            return $this->clientErrorResponse($e);
        }


    }
}
