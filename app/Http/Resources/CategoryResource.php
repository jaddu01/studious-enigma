<?php

namespace App\Http\Resources;

use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryResource extends JsonResource
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
            'parent_category_id' => $this->parent_id,
            'name' => $this->name,
            'image' => $this->image,
            'banner_image' => Helper::imageNotFound($this->banner_image),
            'slug' => $this->slug,
            'sort_no' => $this->sort_no,
            'status' => (boolean) $this->status,
            'is_show' => (boolean) $this->is_show,
            'sub_category' => $this->whenLoaded('children', function () {
                return CategoryResource::collection($this->children);
            }),
        ];
    }
}
