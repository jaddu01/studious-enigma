<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            "zone_id" => $this->zone_id,
            "qty" => $this->qty,
            "vendor_product" => new VendorProductResource($this->vendorProduct)
        ];
    }
}
