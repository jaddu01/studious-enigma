<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessLevelTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
}
