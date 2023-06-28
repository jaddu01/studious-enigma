<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            "sku_code"=>$this->sku_code,
            "hsn_code" => $this->hsn_code,
            "barcode" => $this->barcode,
            "gst" => $this->gst,
            "category_id" => $this->category_id,
            "measurement_class" => $this->MeasurementClass,
            "measurement_value" => $this->measurement_value,
            "self_life" => $this->self_life,
            "manufacture_details" => $this->manufacture_details,
            "marketed_by" => $this->marketed_by,
            "product_status" => $this->product_status,
            "expire_date" => $this->expire_date,
            "show_in_cart_page" => $this->show_in_cart_page,
            "returnable" => $this->returnable,
            "name" => $this->name,
            "print_name" => $this->print_name,
            "image" => $this->image,
            "images" => $this->images,
            "has_variant" => !empty($this->variant_products) ? true : false,
            // "variants" => $this->variant_products,
        ];
    }
}