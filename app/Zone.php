<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;
use Illuminate\Support\Facades\DB;

class Zone extends BaseModel
{
    use Translatable;
    public $translationModel = 'App\ZoneTranslation';
    use SoftDeletes;
    protected $fillable = [
        'name','code','point','delivery_charges','minimum_order_amount','package_id','description','status','is_default','city_id'
    ];

    public $translatedAttributes = ['name', 'description'];


    /**
     * @param $method
     * @return array
     */
    public function rules($method)
    {
        /*$user = User::find($this->users);*/
        $locales = config('translatable.locales');
        switch($method)
        {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    $page= [

                    ];
                    $trans = [];

                    foreach ($locales as $locale) {
                        $trans = $trans + [
                                'name:'.$locale => 'required',
                                'description:'.$locale => 'required',
                            ];
                    }
                    return $page + $trans;

                }
            case 'PUT':
                $page= [

                ];
                $trans = [];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale => 'required',
                            'description:'.$locale => 'required',
                        ];
                }
                return $page + $trans;
            case 'PATCH':
                {
                    $page= [

                    ];
                    $trans = [];

                    foreach ($locales as $locale) {
                        $trans = $trans + [
                                'name:'.$locale => 'required',
                                'description:'.$locale => 'required',
                            ];
                    }
                    return $page + $trans;
                }
            default:break;
        }
    }


    public function messages($method)
    {
        /*$user = User::find($this->users);*/

        switch($method)
        {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
            case 'POST':
                {
                    return [
                        'name.required' => 'this fiels is required',
                        'description.required' => 'please upload a valid image file',
                    ];
                }
            case 'PUT':
            case 'PATCH':
                {
                    return [
                        'name.required' => 'this fiels is required',
                        'description.required' => 'please upload a valid image file',
                    ];
                }
            default:break;
        }
    }

    public function getPointsAttribute($value){
        $result=[];
        $value = ltrim($value,'POLYGON((');
        $value = rtrim($value,'))');
        $value = explode(',',$value);
        foreach ($value as $val){
            $val = explode(' ',$val);
            $result[]=['lat'=>(double)$val[0],'lng'=>(double)$val[1]];

        }
        return $result;

    }

    public function setPointAttribute($value)
    {
        $points = json_decode($value,true);
        $polygon="ST_PolygonFromText('POLYGON((";
        foreach ($points as $key=>$point){
            if($key==0){
                $first_point = $point['lat']." ".$point['lng'];
            }
            $polygon.= $point['lat']." ".$point['lng']." , ";

        }
        $polygon.=$first_point." ))')";

        $this->attributes['point'] = DB::raw($polygon);
    }
    public function weekPackage()
    {
        return $this->belongsTo('App\WeekPackage','package_id');
    }

    public function city()
    {
        return $this->belongsTo('App\City','city_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope());
    }



}

/*SELECT * from zones where CONTAINS(point, point(26.912434,75.787271))*/
