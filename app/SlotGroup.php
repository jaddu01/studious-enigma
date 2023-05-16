<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;
use DB;
class SlotGroup extends BaseModel
{
    use Translatable;
    public $translationModel = 'App\SlotGroupTranslation';
    use SoftDeletes;
    protected $fillable = [
         'number_of_slot', 'slot_ids',
    ];

    public $translatedAttributes = ['name'];
    public $locales;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->locales = config('translatable.locales');
    }
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
                        'slot_ids'=>'required'
                    ];
                    $trans = [];

                    foreach ($locales as $locale) {
                        $trans = $trans + [
                                'name:'.$locale => 'required'
                            ];
                    }
                    return $page + $trans;

                }
            case 'PUT':
                $page= [
                    'slot_ids'=>'required'
                ];
                $trans = [];

                foreach ($locales as $locale) {
                    $trans = $trans + [
                            'name:'.$locale => 'required'
                        ];
                }
                return $page + $trans;
            case 'PATCH':
                {
                    $page= [
                        'slot_ids'=>'required'
                    ];
                    $trans = [];

                    foreach ($locales as $locale) {
                        $trans = $trans + [
                                'name:'.$locale => 'required'
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
                    $page= [];
                    $trans = [];

                    foreach ($this->locales as $locale) {
                        $trans = $trans + [
                                'name:'.$locale.'.required' => 'Name files is required',
                            ];
                    }
                    return $page + $trans;
                }
            case 'PUT':
            case 'PATCH':
                {
                    $page= [];
                    $trans = [];

                    foreach ($this->locales as $locale) {
                        $trans = $trans + [
                                'name:'.$locale.'.required' => 'Image files is required',
                            ];
                    }
                    return $page + $trans;
                }
            default:break;
        }
    }

    public function setNumberOfSlotAttribute($value)
    {
        $this->attributes['number_of_slot'] = count(explode(',',$this->slot_ids));
    }

    public function setSlotIdsAttribute($value)
    {
        $this->attributes['number_of_slot'] = count($value);
        $this->attributes['slot_ids'] = implode(',',$value);
    }

    public function getSlotIdsAttribute($value)
    {
        return explode(',',$value);
    }

    public function getSlotTimes()
    {
        return SlotTime::whereIn('id',$this->slot_ids)
        ->select('*', \DB::raw("UNIX_TIMESTAMP(STR_TO_DATE(concat(curdate(), '', from_time), '%Y-%m-%d %h:%i %p')) AS from_final_time"))->orderBy('from_final_time')->get();

    }

    protected static function boot()
    {
        parent::boot();
    }



}
