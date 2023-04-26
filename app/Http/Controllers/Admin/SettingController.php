<?php

namespace App\Http\Controllers\Admin;

use App\ApiSetting;
use App\AppSetting;
use App\AppVersion;
use App\Helpers\Helper;
use App\Payment;
use App\Scopes\StatusScope;
use App\Setting;
use App\SiteSetting;
use App\AddressSetting;
use App\SocialMedia;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;


class SettingController extends Controller
{
    protected $setting;
    
    protected $site_setting;
    protected $socialMedia;
    protected $apiSetting;
    protected $appSetting;
    protected $appVersion;
    protected $payment;
    public $output;
    public $user;
    protected $method;
    function __construct(Request $request, AddressSetting $address_setting, Setting $setting, SiteSetting $site_setting,User $user,SocialMedia $socialMedia,ApiSetting $apiSetting,AppSetting $appSetting,AppVersion $appVersion,Payment $payment)
    {
        parent::__construct();
        $this->setting=$setting;
        $this->socialMedia=$socialMedia;
        $this->apiSetting=$apiSetting;
        $this->appSetting=$appSetting;
        $this->appVersion=$appVersion;
        $this->payment=$payment;
        $this->user=$user;
        $this->site_setting=$site_setting;
        $this->address_setting=$address_setting;
        $this->method=$request->method();
    }

    public function general()
    {
        if ($this->user->can('view', Setting::class)) {
            return abort(403,'not able to access');
        }

        $validator = JsValidatorFacade::make($this->setting->rules('PUT'));
        try {
            $setting = $this->setting->withoutGlobalScope(StatusScope::class)->firstOrFail();
        } catch (\Exception $e) {
            $setting=[
                'app_name'=>config('setting.app_name'),
                'app_env'=>config('setting.app_env'),
                'app_debug'=>config('setting.app_debug'),
                'app_log_level'=>config('setting.app_log_level'),
                'app_url'=>config('setting.app_url'),
                'mail_driver'=>config('setting.mail_driver'),
                'mail_host'=>config('setting.mail_host'),
                'mail_port'=>config('setting.mail_port'),
                'mail_username'=>config('setting.mail_username'),
                'mail_password'=>config('setting.mail_password'),
                'mail_encryption'=>config('setting.mail_encryption'),
                'mail_from_address'=>config('setting.mail_from_address'),
                'mail_from_name'=>config('setting.mail_from_name'),
                'app_url_android'=>config('setting.app_url_android'),
                'app_url_ios'=>config('setting.app_url_ios'),
                'under_maintenance'=>config('setting.under_maintenance'),
                'app_logo'=>config('setting.app_logo'),
                'timezone'=>config('setting.timezone'),
                'pagination_limit'=>config('setting.pagination_limit'),
                'email'=>config('setting.email'),
                'phone'=>config('setting.phone'),
                'mobile'=>config('setting.mobile'),
                'address'=>config('setting.address'),
                ];

        }
        return view('admin/pages/setting/general')->with('setting',$setting)->with('validator',$validator);
    }


    public function generalUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($request->all(),$this->setting->rules($this->method));

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{

            try {

                if($request->hasFile('app_logo')){
                    $image = $request->file('app_logo');

                    $imageName = time().'.'.$image->getClientOriginalExtension();

                    $request->file('app_logo')->storeAs(
                        'public/upload', $imageName
                    );

                    $input['app_logo']=$imageName;
                }


                $site_setting = $this->setting->withoutGlobalScope(StatusScope::class)->firstOrCreate(['id'=>1]);
                $site_setting->update($input);
                Session::flash('success','Setting updated successful but your change are apply on re boot so please click on "re boot button"');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
            }
            return back();
        }
    }

    public function reBoot()
    {
        try {
            $setting = $this->setting->firstOrFail()->toArray();

            unset($setting['id'],$setting['created_at'],$setting['updated_at']);
            $setting['app_logo']=Helper::hasImage($setting['app_logo']);

            foreach ($setting as $key=>$value){

                $this->updateDotEnv(mb_strtoupper($key),$value);

            }

            if($setting['under_maintenance']=='true'){
                Artisan::call('down');
            }else{
                Artisan::call('up');
            }
          /*  $bar->finish();*/

            Session::flash('success','Congratulation your system  success re boot.');
        } catch (\Exception $e) {
            Session::flash('danger','some error accrue please update and than save .'.$e->getMessage());
        }

        return back();
    }

    protected function updateDotEnv($key, $newValue, $delim='"')
    {

        $path = base_path('.env');
        // get old value from current env
        $oldValue = env($key);

        // was there any change?
        if ($oldValue === $newValue) {
            return;
        }

        // rewrite file content with changed data
        if (file_exists($path)) {
            // replace current value with new value
            file_put_contents(
                $path, str_replace(
                    $key.'='.$delim.$oldValue.$delim,
                    $key.'='.$delim.$newValue.$delim,
                    file_get_contents($path)
                )
            );
        }
    }

    public function site_setting()
    {
        $validator = JsValidatorFacade::make($this->site_setting->rules('PUT'));
        try { //die;
            $site_setting = $this->site_setting->withoutGlobalScope(StatusScope::class)->firstOrFail();
        } catch (\Exception $e) {
            $site_setting=[
                'min_price'=>config('SiteSetting.min_price'),
                'max_price'=>config('SiteSetting.max_price'),
                'free_delivery_charge'=>config('SiteSetting.free_delivery_charge')
            ];

        }

        //echo '<pre>';
        //print_r($SiteSetting); die;
        return view('admin/pages/setting/site_setting')->with('setting',$site_setting)->with('validator',$validator);
    }

    public function siteSettingUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($request->all(),$this->site_setting->rules($this->method));

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            
            try {

                $site_setting = $this->site_setting->withoutGlobalScope(StatusScope::class)->firstOrCreate(['id'=>1]);
                $site_setting->update($input);
                Session::flash('success','Site Setting updated successful ');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
            }
            return back();
        }
    }

    public function address_setting()
    {
        $validator = JsValidatorFacade::make($this->address_setting->rules('PUT'));
        try { //die;
            $address_setting = $this->address_setting->withoutGlobalScope(StatusScope::class)->firstOrFail();
        } catch (\Exception $e) {
            $address_setting=[
                'address_name'=>config('AddressSetting.address_name'),
                'lat'=>config('AddressSetting.lat'),
                'long'=>config('AddressSetting.long'),
                'description'=>config('AddressSetting.description')
            ];

        }

        //echo '<pre>';
        //print_r($SiteSetting); die;
        return view('admin/pages/setting/address_setting')->with('setting',$address_setting)->with('validator',$validator);
    }

    public function AddressSettingUpdate(Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($request->all(),$this->address_setting->rules($this->method));

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{

            try {

                $address_setting = $this->address_setting->withoutGlobalScope(StatusScope::class)->firstOrCreate(['id'=>1]);

                $address_setting->update($input);
                Session::flash('success','Address Setting updated successful');
            } catch (\Exception $e) {

                Session::flash('danger',$e->getMessage());
            }
            return back();
        }
    }

    public function socialMedia()
    {
        $validator = JsValidatorFacade::make($this->socialMedia->rules('PUT'));

        $site_setting = $this->socialMedia->firstOrFail();

        return view('admin/pages/setting/social_media')->with('setting',$site_setting)->with('validator',$validator);
    }

    public function socialMediaUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($request->all(),$this->socialMedia->rules($this->method));

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            try {
                $site_setting = $this->socialMedia->firstOrCreate(['id'=>1]);
                $site_setting->update($input);
                Session::flash('success','Site Setting updated successful ');
            } catch (\Exception $e) {

                Session::flash('danger',$e->getMessage());
            }
            return back();
        }
    }

    public function apiSetting()
    {
        $validator = JsValidatorFacade::make($this->apiSetting->rules('PUT'));
        $site_setting = $this->apiSetting->firstOrFail();
        return view('admin/pages/setting/api_setting')->with('setting',$site_setting)->with('validator',$validator);
    }

    public function apiSettingUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($request->all(),$this->apiSetting->rules($this->method));

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            try {
                $site_setting = $this->apiSetting->firstOrCreate(['id'=>1]);
                $site_setting->update($input);
                Session::flash('success','Site Setting updated successful ');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
            }
            return back();
        }
    }

    public function appSetting()
    {
        $validator = JsValidatorFacade::make($this->appSetting->rules('PUT'));
        $site_setting = $this->appSetting->withoutGlobalScope(StatusScope::class)->firstOrFail();

        return view('admin/pages/setting/app_setting')->with('setting',$site_setting)->with('validator',$validator);
    }

    public function appSettingUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($request->all(),$this->appSetting->rules($this->method));
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{

            try {
                $site_setting = $this->appSetting->firstOrCreate(['id'=>1]);
                $site_setting->update($input);
                Session::flash('success','Site Setting updated successful ');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
            }
            return back();
        }
    }

    public function payment()
    {
        $validator = JsValidatorFacade::make($this->payment->rules('PUT'));

        $site_setting = $this->payment->firstOrFail();

        return view('admin/pages/setting/payment')->with('setting',$site_setting)->with('validator',$validator);
    }

    public function paymentUpdate(Request $request)
    {

        $input = $request->all();
        $validator = Validator::make($request->all(),$this->payment->rules($this->method));
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            try {
                $site_setting = $this->payment->firstOrCreate(['id'=>1]);
                $site_setting->update($input);
                Session::flash('success','Site Setting updated successful ');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
            }
            return back();
        }
    }

    public function appVersion()
    {
        
        $validator = JsValidatorFacade::make($this->appVersion->rules('PUT'));

        $site_setting = $this->appVersion->firstOrFail();

        return view('admin/pages/setting/app_version')->with('setting',$site_setting)->with('validator',$validator);
    }

    public function appVersionUpdate(Request $request)
    {
        $input = $request->all();
        
        if(empty($request->input('ios_mandatory_update')) ){
            $input['ios_mandatory_update'] = "0";
        }
        if(empty($request->input('ios_main_tenance_mode'))){
            $input['ios_main_tenance_mode'] = "0";
        }
        if(empty($request->input('shopper_android_mandatory_update') )){
            $input['shopper_android_mandatory_update'] = "0";
        }
        if(empty($request->input('shopper_android_main_tenance_mode') )){
            $input['shopper_android_main_tenance_mode'] = "0";
        }
        if(empty($request->input('driver_android_mandatory_update'))){
            $input['driver_android_mandatory_update'] = "0";
        }
         if(empty($request->input('driver_android_main_tenance_mode') )){
            $input['driver_android_main_tenance_mode'] = "0";
        }
         if(empty($request->input('customer_android_mandatory_update'))){
            $input['customer_android_mandatory_update'] = "0";
        }
         if(empty($request->input('customer_android_main_tenance_mode') )){
            $input['customer_android_main_tenance_mode'] = "0";
        }
        
        //return $input;
        $validator = Validator::make($request->all(),$this->appVersion->rules($this->method));
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            try {
                $site_setting = $this->appVersion->firstOrCreate(['id'=>1]);
                $site_setting->update($input);
                Session::flash('success','Site Setting updated successful ');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
            }
            return back();
        }
    }

}
