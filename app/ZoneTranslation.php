<?php

namespace App;

use App\Helpers\Helper;
use Illuminate\Database\Eloquent\Model;

class ZoneTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','description'];

}
