<?php

/**
 * @Author: abhi
 * @Date:   2021-09-30 01:51:45
 * @Last Modified by:   abhi
 * @Last Modified time: 2021-09-30 01:54:59
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderTypeTranslation extends BaseModel
{
    public $timestamps = false;
    protected $fillable = ['name','details'];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
}