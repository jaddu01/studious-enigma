<?php

namespace App;

use App\Scopes\StatusScope;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cms extends BaseModel
{
    use Translatable;
    public $translationModel = 'App\CmsTranslation';
    public  $locales;
    protected $fillable = [
        'name',
    ];


    public $translatedAttributes = ['name','description'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->locales = config('translatable.locales');
    }

    /**
     * @param $method
     * @return array
     */
    public function rules($method,$id=0)
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
                    $page= [

                    ];
                    $trans = [
                    ];

                    foreach ($this->locales as $locale) {
                        $trans = $trans + [
                            'description:'.$locale => 'required',
                            ];
                    }
                    return $page + $trans;

                }
            case 'PUT':
                $page= [

                ];
                $trans = [
                ];

                foreach ($this->locales as $locale) {
                    $trans = $trans + [
                            'description:'.$locale => 'required',
                        ];
                }
                return $page + $trans;
            case 'PATCH':
                {
                    $page= [];
                    $trans = [
                    ];

                    foreach ($this->locales as $locale) {
                        $trans = $trans + [
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
                    $page= [];
                    $trans = [];

                    foreach ($this->locales as $locale) {
                        $trans = $trans + [
                                'name:'.$locale.'.required' => 'name files is required',
                                'description:'.$locale.'.required' => 'description files is required',
                            ];
                    }
                    return $page + $trans;

                }
            case 'POST':
                {
                    $page= [];
                    $trans = [];

                    foreach ($this->locales as $locale) {
                        $trans = $trans + [
                                'name:'.$locale.'.required' => 'name files is required',
                                'description:'.$locale.'.required' => 'description files is required',
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
                                'name:'.$locale.'.required' => 'name files is required',
                                'description:'.$locale.'.required' => 'description files is required',
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
       // static::addGlobalScope(new StatusScope());
    }
}
