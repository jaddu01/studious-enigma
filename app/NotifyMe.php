<?php
namespace App;
use App\Scopes\StatusScope;

class NotifyMe extends BaseModel
{
    protected $table = 'notify_me';
    protected $fillable = [
        'user_id','product_id'
    ];
    
    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}