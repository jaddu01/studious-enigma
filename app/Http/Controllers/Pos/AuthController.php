<?php

namespace App\Http\Controllers\Pos;

use App\Helpers\ResponseBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);
            if ($validator->fails()){
               return ResponseBuilder::error($validator->errors()->first(),$this->validationStatus);
            }

            $user = PosUser::where('email', $request->email)->first();
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
