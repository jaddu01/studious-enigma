<?php

namespace App\Http\Resources;

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
            "email"=>$this->email,
            "phone_code"=>$this->phone_code,
            "phone_number"=>$this->phone_number,
            "profile_image"=>$this->image,
            "user_type"=>$this->user_type,
            "dob" => $this->dob,
            "gender" => $this->gender,
            "role" => $this->role,
            "wallet_amount" => $this->wallet_amount,
            "coin_amount" => $this->coin_amount,
            "address" => UserAddressResource::collection($this->deliveryLocation),
        ];
    }
}
