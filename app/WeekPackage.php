<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class WeekPackage extends BaseModel
{
    use Translatable;
    public $translationModel = 'App\WeekPackageTranslation';
    use SoftDeletes;
    protected $fillable = [
        'saturday_slot_id', 'sunday_slot_id', 'monday_slot_id', 'tuesday_slot_id', 'wednesday_slot_id', 'thursday_slot_id', 'friday_slot_id'
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
                        'saturday_slot_id'=>'required',
                        'sunday_slot_id'=>'required',
                        'monday_slot_id'=>'required',
                        'tuesday_slot_id'=>'required',
                        'wednesday_slot_id'=>'required',
                        'thursday_slot_id'=>'required',
                        'friday_slot_id'=>'required',
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
                    'saturday_slot_id'=>'required',
                    'sunday_slot_id'=>'required',
                    'monday_slot_id'=>'required',
                    'tuesday_slot_id'=>'required',
                    'wednesday_slot_id'=>'required',
                    'thursday_slot_id'=>'required',
                    'friday_slot_id'=>'required',
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
                        'saturday_slot_id'=>'required',
                        'sunday_slot_id'=>'required',
                        'monday_slot_id'=>'required',
                        'tuesday_slot_id'=>'required',
                        'wednesday_slot_id'=>'required',
                        'thursday_slot_id'=>'required',
                        'friday_slot_id'=>'required',
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

    public function saturday()
    {
        return $this->belongsTo('App\SlotGroup','saturday_slot_id','id');
    }
    public function sunday()
    {
        return $this->belongsTo('App\SlotGroup','sunday_slot_id','id');
    }
    public function monday()
    {
        return $this->belongsTo('App\SlotGroup','monday_slot_id','id');
    }
    public function tuesday()
    {
        return $this->belongsTo('App\SlotGroup','tuesday_slot_id','id');
    }
    public function wednesday()
    {
        return $this->belongsTo('App\SlotGroup','wednesday_slot_id','id');
    }
    public function thursday()
    {
        return $this->belongsTo('App\SlotGroup','thursday_slot_id','id');
    }
    public function friday()
    {
        return $this->belongsTo('App\SlotGroup','friday_slot_id','id');
    }

    protected static function boot()
    {
        parent::boot();
    }



}
