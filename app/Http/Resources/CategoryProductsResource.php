<?php

namespace App\Http\Resources;

use App\VendorProduct;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $vendorProducts = VendorProduct::with(['product.MeasurementClass','product.image'])->whereHas('product.category',function($q){
            $q->whereRaw('FIND_IN_SET('.$this->id.', category_id) ');
        })->limit(10)->get();
        return [
            'id' => (int)$this->id,
            'name' => (string)$this->name,
            'slug' => (string)$this->slug,
            'products' => VendorProductResource::collection($vendorProducts),
        ];
    }
}
