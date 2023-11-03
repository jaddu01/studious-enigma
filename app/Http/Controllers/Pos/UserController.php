<?php

namespace App\Http\Controllers\Pos;

use App\Helpers\ResponseBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pos\AddCustomerRequest;
use App\Http\Resources\UserResource;
use App\User;
use Illuminate\Support\Facades\DB;
use Log;

class UserController extends Controller
{
    public function index(Request $request){
        try{
            $users = User::where('user_type', 'user')->where('status', '1')->get();
            $this->response->users = UserResource::collection($users);

            return ResponseBuilder::success($this->response, 'User list',$this->successStatus);
        }catch(\Exception $e){
            Log::error($e);
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }

    public function addCustomer(AddCustomerRequest $request){
        try {
            DB::beginTransaction();
            $data = $request->only('name','phone_code','phone_number','whatsapp_no','address','dob');
            
            User::create($data);
            DB::commit();
            return ResponseBuilder::success(null,__('user.add_customer_sucessfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);

        }
    }
}
