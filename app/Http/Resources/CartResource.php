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
            'data' => $this->cart ?? null,
            'cart_count' => $this->count,
            'total_saving' => $this->total_saving,
            'total_saving_percentage' => $this->total_saving_percentage,
            'product_price' => $this->offer_price_total,
            'delivery_charge' => $this->delivery_charge,
            'total_price' => $this->offer_price_total+$this->delivery_charge,
            'currency' => '₹' 
        ];
    }
}
