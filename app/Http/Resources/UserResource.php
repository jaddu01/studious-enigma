<?php

namespace App\Http\Resources;

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
        return [
            "user_id"=>$this->id,
            "name"=>$this->name,
            "email"=>$this->email ?? null,
            "phone_code"=>$this->phone_code,
            "phone_number"=>$this->phone_number,
            "image"=>$this->image,
            "user_type"=>$this->user_type,
            "dob" => Carbon::parse($this->dob)->timestamp ?? null,
            "gender" => $this->gender,
            "role" => $this->role,
            "wallet_amount" => $this->wallet_amount,
            "coin_amount" => $this->coin_amount,
            "device_id" => $this->device_id,
            "device_type" => $this->device_type,
            "device_token" => $this->device_token,
            "addresses" => UserAddressResource::collection($this->deliveryLocation),
            "membership" => $this->membership,
        ];
    }
}
