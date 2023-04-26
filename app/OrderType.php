<?php

/**
 * @Author: abhi
 * @Date:   2021-09-30 01:51:39
 * @Last Modified by:   abhi
 * @Last Modified time: 2021-09-30 01:54:19
 */
namespace App;

use App\Scopes\StatusScope;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderType extends BaseModel
{
    use Translatable;
    public $translationModel = 'App\OrderTypeTranslation';
    use SoftDeletes;
    protected $fillable = [
        'status',
    ];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->locales = config('translatable.locales');
    }
    public $translatedAttributes = ['name','details'];
    public $locales;
    protected $with = ['translations'];

    public function rules($method,$id=0)
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
                                'details:'.$locale => 'required'
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
                            'details:'.$locale => 'required'
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
                                'details:'.$locale => 'required'
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
                                'name:'.$locale.'.unique' => 'Name files is unique',
                                'name:'.$locale.'.regex' => 'Please enter character only',
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
                                'name:'.$locale.'.unique' => 'Name files is unique',
                                'name:'.$locale.'.regex' => 'Please enter character only',
                            ];
                    }
                    return $page + $trans;
                }
            default:break;
        }
    }
    /*public function productOrder()
    {
        return $this->hasMany('App\ProductOrder');
    }*/
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new StatusScope());
    }

}