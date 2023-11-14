<?php

namespace App\Http\Resources\Pos;

use App\VendorProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class PosAppProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request){
    //   $vendor_product = VendorProduct::find($this->vendor_product_id);
    $data = $this->data;
      
        return [
            'vendor_product_id'=>$data['vendor_product']['id'],
            'productId'=>$data['vendor_product']['product']['id'],
            'quantity'=>$data['qty'],
            'price'=>$data['vendor_product']['product']['price'],
            'offer_price'=>'id',
            'best_price'=>$data['vendor_product']['product']['best_price'],
            'is_offer'=>$this->is_offer,
            'offer_data'=>$this->offer_data,
        ];
    }

}
