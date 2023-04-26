<?php

namespace App\Http\Controllers\Admin;


use App\DeliveryLocation;
use App\Region;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use App\User;
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
        if ($this->user->can('create', DeliveryLocation::class)) {
            return abort(403,'not able to access');
        }

        $validator = Validator::make($request->all(),$this->deliveryLocation->rules($this->method),$this->deliveryLocation->messages($this->method));

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }else{

            try {
                $input = $request->all();
                if($request->filled('region_id')){
                    $region =   Region::findOrFail($request->region_id);
                    $input['lat'] =$region->lat;
                    $input['lng'] =$region->lng;
                }
                $data =   $this->deliveryLocation->updateOrCreate(['id'=>$input['shipping_location']],$input);

            } catch (\Exception $e) {
                return $this->clientErrorResponse($e);
            }
            return $this->showResponse($data);
        }
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
