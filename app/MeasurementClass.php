<?php

namespace App;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dimsav\Translatable\Translatable;

class MeasurementClass extends BaseModel
{
    use Translatable;
    public $translationModel = 'App\MeasurementClassTranslation';
    use SoftDeletes;
    protected $fillable = [
        'status',
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


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new StatusScope());
    }



}
