<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'description','disclaimer','keywords','self_life','manufacture_details','marketed_by','print_name'];
    /**
     * @param $value
     * @return null|string
     */
    public function getImageAttribute($value)
    {
        return Helper::hasImage($value);
    }
    protected $touches = ['product'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
