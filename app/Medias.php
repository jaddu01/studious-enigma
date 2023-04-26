<?php

/**
 * @Author: Abhi Bhatt
 * @Date:   2022-03-11 23:37:13
 * @Last Modified by:   Abhi Bhatt
 * @Last Modified time: 2022-03-12 00:23:59
 */
namespace App;
use App\Helpers\Helper;
use App\Scopes\RecentScope;
use App\Scopes\StatusScope;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medias extends BaseModel
{
    use Translatable;
    protected $table = 'medias';
    public $translationModel = 'App\MediasTranslations';
    public  $locales;
    use SoftDeletes;
    protected $fillable = [
        'status',
        'media_type'
    ];


    public $translatedAttributes = ['title','image'];

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
                    //'link'=>'nullable|required|url'
                
                ];

                foreach ($this->locales as $locale) {
                    $trans = $trans + [
                            'image:'.$locale => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                            'title:'.$locale => "required|unique:medias_translations,title",
                        ];
                }
                return $page + $trans;

            }
            case 'PUT':
                $page= [

                ];
                $trans = [
                    //'link'=>'nullable|required|url'
               
                ];

                foreach ($this->locales as $locale) {
                    $trans = $trans + [
                            'image:'.$locale => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg',
                            'title:'.$locale => 'required|unique:medias_translations,title,'.$id.',medias_id',
                        ];
                }
                return $page + $trans;
            case 'PATCH':
            {
                $page= [ ];
                $trans = [
               
                ];

                foreach ($this->locales as $locale) {
                    $trans = $trans + [
                            'image:'.$locale => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg',
                             'title:'.$locale => 'required|unique:medias_translations,title,'.$id.',medias_id',
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
                            'image:'.$locale.'.required' => 'Image files is required',
                            'image:'.$locale.'.dimensions' => 'Image file dimension should be 540X720 pixels',
                            'title:'.$locale.'.required' => 'Title field is required',
                            'title:'.$locale.'.unique' => 'Title:'.$locale.' must be unique',
                            
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
                            'image:'.$locale.'.required' => 'Image files is required',
                            'image:'.$locale.'.dimensions' => 'Image file dimension should be 540X720 pixels',
                            'title:'.$locale.'.required' => 'Title field is required',
                            'title:'.$locale.'.unique' => 'Title:'.$locale.' must be unique',
                            
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
                            'image:'.$locale.'.required' => 'Image files is required',
                            'image:'.$locale.'.dimensions' => 'Image file dimension should be 540X720 pixels',
                            'title:'.$locale.'.required' => 'Title field is required',
                            'title:'.$locale.'.unique' => 'Title:'.$locale.' must be unique',
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