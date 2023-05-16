<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades;
use App\Revenue;
use Carbon\carbon;
use App;
use App\User;
use DB;
use File;
use Redirect;
use Lang;

class LanguageController extends Controller
{
 
    protected $method;
    public $user;
    function __construct(Request $request, User $user)
    {
        parent::__construct();
        $this->method=$request->method();
        $this->user = $user;
    }

    public function site()
    {

        $url =  \Request::segment(3);
        $lang =  \Request::segment(4);
        if ($this->user->can($url, Revenue::class)) {
            return abort(403,'not able to access');
        }

        try {
                $locale = App::getLocale();
                $data = $this->openLangFile($lang, $url);
                return view('admin/pages/language/site')->with('data',$data)->with('url',$url)->with('lang',$lang);
                return $data;
        
        } catch (\Exception $e) {
            Session::flash('danger',$e->getMessage());
        }
        
    }

    public function siteUpdate(request $request)
    {
        $valids = [];
        $url =  \Request::segment(3);
        $lang =  \Request::segment(4);
        if ($this->user->can('create', User::class)) {
            return abort(403,'not able to access');
        }
        try {
            $input = array_except($request->all(), ['_token']);
            $locale = App::getLocale();
           
                foreach ($input as $data => $value) {
                  $valids[$data] = "required";
                }
                $validator =  validator($request->all(),$valids);
                if ($validator->fails()) {
                   return back()
                    ->withErrors($validator)
                    ->withInput();
                }
                $this->saveLangFile($lang,$url, $input);
                return redirect()->back()->with('success','Language string updated successfully');
                 //Session::flash('success','Language string updated successfully');
           
        } catch (\Exception $e) {
            Session::flash('danger',$e->getMessage());
        }
        
    }
   
    private function openLangFile($lang,$code){
        
        $phpString = [];
         $siteArray = [];
        if(File::exists(base_path('resources/lang/'.$lang.'/'.$code.'.php'))){
            //$siteArray = trans($code);
           $siteArray =  Lang::get($code,[],$lang);
        }
        return $siteArray;
    }

    private function saveLangFile($lang, $code, $data){

        $var_str = var_export($data, true);
        $var = "<?php  return [".$var_str."\n\n];";
        $finalArray =  str_replace(array( '(', ')','array' ), '', $var);
        file_put_contents(base_path('resources/lang/'.$lang.'/'.$code.'.php'), print_r($finalArray, true));


    }
   
}
