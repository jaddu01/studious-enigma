<?php
namespace App;
use App\Scopes\StatusScope;

class NotifyMe extends BaseModel
{
    protected $table = 'notify_me';
    protected $fillable = [
        'user_id','vendor_product_id','status','count'
    ];
    
}