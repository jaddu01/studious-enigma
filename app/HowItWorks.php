<?php

namespace App;
use App\Helpers\Helper;
use App\Scopes\RecentScope;
use App\Scopes\StatusScope;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class HowItWorks extends BaseModel
{
    use Translatable;
    protected $table = 'how_it_works';
    public $translationModel = 'App\HowItWorksTranslation';
    public  $locales;
    use SoftDeletes;
    protected $fillable = [
        'status'
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
                            'image:'.$locale => 'required|image|dimensions:width=540,height=720|mimes:jpeg,png,jpg,gif,svg',
                            'title:'.$locale => "required|unique:how_it_works_translations,title",
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
                            'image:'.$locale => 'sometimes|image|dimensions:width=540,height=720|mimes:jpeg,png,jpg,gif,svg',
                            'title:'.$locale => 'required|unique:how_it_works_translations,title,'.$id.',how_it_works_id',
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
                            'image:'.$locale => 'sometimes|image|dimensions:width=540,height=720|mimes:jpeg,png,jpg,gif,svg',
                             'title:'.$locale => 'required|unique:how_it_works_translations,title,'.$id.',how_it_works_id',
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
