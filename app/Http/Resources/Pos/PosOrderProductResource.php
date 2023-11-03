<?php

namespace App\Http\Resources\Pos;

use Illuminate\Http\Resources\Json\JsonResource;

class PosOrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[
            'vendor_product_id'=>$this->vendor_product_id,
            'productId'=>$this->product_id,
            'quantity'=>$this->qty,
            'price'=>$this->price,
            'offer_price'=>$this->offer_value,
            'best_price'=>$this->best_price,
            'is_offer'=>$this->is_offer,
            'offer_data'=>$this->offer_data,
            // 'best_price'=>$this->best_price,

        ];
    }
}
