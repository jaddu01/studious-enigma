<?php

namespace App\Http\Controllers\Pos;

use App\Helpers\ResponseBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Pos\PosUserResource;
use App\PosUser;
use Auth;
use Illuminate\Validation\Rule;
use Log;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'email' => ['required','email', Rule::exists('pos_users')->where(function ($query) {
                    $query->where('status', '1');
                })],
                'password' => 'required'
            ]);
            if ($validator->fails()){
               return ResponseBuilder::error($validator->errors()->first(),$this->validationStatus);
            }

            if(!Auth::guard('pos')->attempt(['email' => $request->email, 'password' => $request->password])){
                return ResponseBuilder::error('Invalid email or password', $this->validationStatus);
            }

            $user = Auth::guard('pos')->user();
           
            $token =  $user->createToken('pos')->accessToken;
            $this->response->user = new PosUserResource($user);
            return ResponseBuilder::successWithToken($token, $this->response, 'Login successfully',$this->successStatus);
        }catch(\Exception $e){
            Log::error($e);
            return ResponseBuilder::error($e->getMessage(),$this->errorStatus);
        }
    }
}
