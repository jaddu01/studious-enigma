<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorProductDetailedResource extends JsonResource
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
            "price" => round($this->price,2),
            "qty" => $this->qty,
            "offer" => $this->Offer,
            "per_order" => $this->per_order,
            "best_price" => $this->best_price,
            "mrp" => !empty($this->best_price) ? round($this->best_price,2) : round($this->price,2),
            "memebership_p_price" => $this->memebership_p_price,
            "is_offer" => $this->is_offer,
            "offer_price"  => $this->offer_price,
            "product" => new ProductDetailedResource($this->product),
            "match_in_zone" => true,
            "is_wishlist" => (boolean) $this->wishList()->where('user_id',auth('api')->user()->id)->first(),
        ];
    }
}
