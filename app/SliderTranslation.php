<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class SliderTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['title','image'];

    public function getImageAttribute($value)
    {
        return Helper::hasImage($value);
    }
    protected $touches = ['slider'];
    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }
}
