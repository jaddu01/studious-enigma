<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class CmsTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','description'];

    protected $touches = ['cms'];
    public function cms()
    {
        return $this->belongsTo(Cms::class);
    }
}
