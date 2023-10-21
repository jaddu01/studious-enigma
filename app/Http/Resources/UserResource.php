<?php

namespace App\Http\Resources;

use App\Medias;
use App\MediasTranslations;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // dd($this->membership_to);
        $refer_msg = Medias::where('media_type','refer')->first();
        $refer_msg=$refer_msg->translate('en')->message;
        
        return [
            "user_id"=>$this->id,
            "name"=>$this->name,
            "email"=>$this->email ?? null,
            "phone_code"=>$this->phone_code,
            "phone_number"=>$this->phone_number,
            "image"=>$this->image,
            "user_type"=>$this->user_type,
            "formatted_dob" => Carbon::parse($this->dob)->format('d-m-Y') ?? null,
            "dob" => Carbon::parse($this->dob)->timestamp ?? null,
            "gender" => $this->gender,
            "role" => $this->role,
            "wallet_amount" => $this->wallet_amount,
            "coin_amount" => $this->coin_amount,
            "device_id" => $this->device_id,
            "device_type" => $this->device_type,
            "device_token" => $this->device_token,
            "addresses" => UserAddressResource::collection($this->deliveryLocation),
            "membership" => Carbon::parse($this->membership_to)->gte(today()) ? $this->membership : null,
            "referral_code" => $this->referral_code,
            "referral_by" => $this->referral_by,
            "referral" => $this->referral,
            'referral_msg_txt'=>$refer_msg??null,

        ];
    }
}
