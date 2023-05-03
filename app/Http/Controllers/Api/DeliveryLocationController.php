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
use Response;

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
        $location = collect($this->deliveryLocation->where('user_id',Auth::guard('api')->user()->id)->get());
        try {
            if($location->count() > 1){
                $cart = $location->where('id',$id)->first();
                if(!$cart){
                    return ResponseBuilder::error("Delivery Location not found", $this->notFoundStatus);
                }
                $cart->delete();
                $this->response->id = $id;
                return ResponseBuilder::success($this->response, "Delivery Location deleted successfully", $this->successStatus);

            }else{
                return ResponseBuilder::error("You can't delete last address", $this->validationStatus);
            }
        } catch (\Exception $e) {
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }


    }
}
