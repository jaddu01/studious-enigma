<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseBuilder;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Helper;
use Log;
use Validator;

class AuthController extends Controller
{
    //create check user function with mobile number
    public function checkUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_code' => 'required',
            'phone_number' => 'required|numeric|digits:10',
        ]);
        if ($validator->fails()) {
            //use response builder
            return ResponseBuilder::error($validator->errors()->first(),$this->validationStatus);
            // return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
        }
        $user = User::withTrashed()->where('phone_number', $request->phone_number)->where('phone_code', $request->phone_code)->first();
        if(!$user){
            try {
                $this->response->new_user  = true;
                return ResponseBuilder::success($this->response, 'New User register request',$this->successStatus);
            } catch (\Exception $e) {
                return ResponseBuilder::error($e->getMessage(),$this->errorStatus);
            }
        }else{
            if($user->user_type != 'user'){
                return ResponseBuilder::error('Please login with user details in user app.',$this->validationStatus);
            }
            if(!empty($user->deleted_at)){
                return ResponseBuilder::error('Your account has been deleted by admin.',$this->validationStatus);
            }
            //send otp
            $otp = "123456"; //rand(100000,999999);
            try {
                $user->update([
                    'device_token' => $request->device_token,
                    'device_id' => $request->device_id,
                    'language' => $request->language,
                    'device_type' => $request->device_type,
                    'otp' => $otp,
                ]);
                //send otp
                $phone_number = $request->phone_code . $request->phone_number;
                try{
                   Helper::sendOtp($phone_number, $otp);
                }catch(\Exception $e){
                   Log::error($e);
                }

                $this->response->new_user = false;
                return ResponseBuilder::success($this->response, 'OTP sent successfully',$this->successStatus);
            } catch (\Exception $e) {
                return ResponseBuilder::error($e->getMessage(),$this->errorStatus);
            }
        }
        
    }

    //create verify otp function
    public function loginOtpVerify(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'phone_code' => 'required',
                'phone_number' => 'required|numeric|digits:10',
                'otp' => 'required|numeric|digits:6'
            ]);
            if ($validator->fails()){
               return ResponseBuilder::error($validator->errors()->first(),$this->validationStatus);
            }

            $user = User::where('phone_code', $request->phone_code)->where('phone_number', $request->phone_number)->where('otp', $request->otp)->first();
            if (!$user) {
                return ResponseBuilder::error('OTP did not match! Try Again.', $this->validationStatus);
            }

            $user->otp = "";
            $user->save();

            $token =  $user->createToken('grocery')->accessToken;
            $this->response->user = new UserResource($user);
            return ResponseBuilder::successWithToken($token, $this->response, 'Login successfully',$this->successStatus);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return ResponseBuilder::error($e->getMessage(),$this->errorStatus);
        }
    }
    
}
