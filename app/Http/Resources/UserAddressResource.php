<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserAddressResource extends JsonResource
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
            "id" => $this->id,
            "name" => $this->name,
            "address" => $this->address,
            "description" => $this->description,
            "lat"=>$this->lat,
            "lng"=>$this->lng,
            "actual_address"=>$this->actual_address,
            "address_as"=>$this->address_as,
            "city"=>$this->city,
            "area"=>$this->area,
        ];
    }
}
