<?php

namespace App\Http\Resources;

use Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class AdsResource extends JsonResource
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
            'link' => $this->link,
            'link_type' => $this->link_type,
            'link_url_type' => $this->link_url_type,
            'category_id' => $this->cat_id,
            'sub_category_id' => $this->sub_cat_id,
            'image' => $this->image,
        ];
    }
}
