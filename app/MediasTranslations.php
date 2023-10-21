<?php

/**
 * @Author: Abhi Bhatt
 * @Date:   2022-03-11 23:37:15
 * @Last Modified by:   Abhi Bhatt
 * @Last Modified time: 2022-03-11 23:41:31
 */
namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class MediasTranslations extends Model
{
    public $timestamps = false;
    protected $table = 'medias_translations';
    protected $fillable = ['title','image','message'];

    public function getImageAttribute($value)
    {
        return Helper::hasImage($value);
    }
    protected $touches = ['slider'];
    public function slider()
    {
        return $this->belongsTo(Medias::class);
    }
}