<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class HowItWorksTranslation extends Model
{
    public $timestamps = false;
    protected $table = 'how_it_works_translations';
    protected $fillable = ['title','image'];

    public function getImageAttribute($value)
    {
        return Helper::hasImage($value);
    }
    protected $touches = ['slider'];
    public function slider()
    {
        return $this->belongsTo(HowItWorks::class);
    }
}
