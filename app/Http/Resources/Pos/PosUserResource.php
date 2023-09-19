<?php

namespace App\Http\Resources\Pos;

use Illuminate\Http\Resources\Json\JsonResource;

class PosUserResource extends JsonResource
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
            'id' => $this->id,
            'zone_id' => $this->zone_id,
            'name' => $this->name,
            'phone_code' => $this->phone_code,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'language' => $this->language,
            'address' => $this->address,
            'status' => (boolean) $this->status,
            'wallet_amount' => $this->wallet_amount
        ];
    }
}
