<?php
namespace App;
use App\Scopes\StatusScope;

class NotifyMe extends BaseModel
{
    protected $table = 'notify_me';
    protected $fillable = [
        'user_id','product_id'
    ];
}