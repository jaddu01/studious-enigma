<?php

namespace App\Http\Controllers\Api;

use App\Ads;
use App\Category;
use App\City;
use App\ZoneTranslation;
use App\CountryPhoneCode;
use App\DeliveryDay;
use App\Helpers\Helper;
use App\PaymentMode;
use App\ProductOrder;
use App\SiteSetting;
use App\SocialMedia;
use App\AppSetting;
use App\AppVersion;
use App\Slider;
use App\FirstOrder;
use App\Membership;
use App\OfferSlider;
use App\Offer;
use App\HowItWorks;
use App\Scopes\StatusScope;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use App\User;
use App\VendorProduct;
use App\Zone;
use App\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\Return_;

use GuzzleHttp\Client;
use App\ProductOrderItem;

use App;
use DB;

class UtilityController extends Controller
{
    use RestControllerTrait,ResponceTrait;

    const MODEL = 'App\Category';
    /**
     * @var Category
     */
    private $category;
    /**
     * @var string
     */
    protected $method;

    /**
     * CategoryController constructor.
     * @param Request $request
     * @param Category $pickUpPoint
     */
    public function __construct(Request $request,Category $pickUpPoint,Zone $zone,Membership $membership,VendorProduct $VendorProduct,User $user,Offer $offer,FirstOrder $firstorder,ProductOrder $order)
    {
        parent::__construct();
        $this->category = $pickUpPoint;
        $this->zone = $zone;
        $this->order = $order;
        $this->user=$user;
        $this->offer=$offer;
        $this->first_order=$firstorder;
        $this->membership = $membership;
        $this->vendorProduct = $VendorProduct;
        $this->method=$request->method();
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */

    public function getLanguage(Request $request){
        $data['language'] = config('translatable.locales');

        return $this->listResponse($data);
    }

    public function getSlider(Request $request){
        $data  =Slider::all();

        return $this->listResponse($data);
    }
    public function getHowItWorks(Request $request){
        $data  = HowItWorks::get();

        return $this->listResponse($data);
    }
    public function getOfferSlider(Request $request){
        $validator = Validator::make($request->all(), [

            'lat'=>'required',
            'lng' => 'required'

        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }
       

        $zonedata = $this->getZoneData($request->lat, $request->lng);
        $zone_id =  $zonedata['zone_id'];
        $data  = OfferSlider::with('category','sub_category','product')->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->get();
       
        return $this->listResponse($data);
    }

    public function getCity(Request $request){
        $data  =City::with(['region'])->get();

        return $this->listResponse($data);
    }
    public function getCityName(Request $request){
        $data  =City::get();

        return $this->listResponse($data);
    }
    public function getRegionsByCityId(Request $request){
        $city_id = $request->city_id; 
        $data  =Region::where('city_id', $city_id)->get();

        return $this->listResponse($data);
    }

    public function getCategory(Request $request){

        $categories = Category::where('parent_id','=','0')->orderBy('sort_no', 'ASC')->get();
        //return $categories;
        $data = [];
        foreach ($categories as $category){
            $category['sub_category'] = Category::where('parent_id','=',$category->id)->orderBy('sort_no', 'ASC')->get();
            $data[]=$category;
        }

        return $this->listResponse($data);
    }

    public function getSubCategoryByCategoryId(Request $request,$categoryId){
        $categories = Category::where('parent_id','=',$categoryId)->orderBy('sort_no', 'ASC')->get();


        return $this->listResponse($categories);
    }


    public function home(Request $request){
        $zone_id = '';
        /* $validator = Validator::make($request->all(), [

            'lat'=>'required',
            'lng' => 'required'

        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);

        } */
        
        if(isset($request->lat) && isset($request->lng) && !empty($request->lat) && !empty($request->lng)){
        	$request->lat = $request->lat;
        	$request->lng = $request->lng;
        }else{
        	$request->lat = "26.1046";
        	$request->lng = "74.3199";
        }

        try {
            $slider = Slider::with('category','sub_category','product');
            $ads = Ads::with('category','sub_category','product');
            $category = Category::where('parent_id', '=', '0')->orderBy('sort_no','ASC')->get();
            if(Auth::guard('api')->user()){
            $zonedata = $this->getZoneData($request->lat, $request->lng);
            $zone_id = $zonedata['zone_id'];
            $zone_name =  $zonedata['zone_name'];
            $match_in_zone = $zonedata['match_in_zone'];
         }else{
           $zonedata = $this->zone->where('is_default','1')->where('status','1')->first();
           $zone_id =  $zonedata->id;
            $zone_name =  $zonedata->name;
            $match_in_zone = true;
         } 
          
            $user  = User::select('*');
            $offer = [];
            $user->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor']);

           
            $slider = $slider->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->get();
            $ads =  $ads->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->get();
            $user = $user->get();
            if(Auth::guard('api')->user()){
			DB::table('users')->where('id', Auth::guard('api')->user()->id)->update(['zone_id' => $zone_id]);
			}
            if(!isset($user)){
                 $vendorProduct = [];
            }else{
                $useridarray =[];
                foreach($user as $userkey=>$uservalue){
                        $useridarray[]= $uservalue->id;
                }
                if(Auth::guard('api')->user()){
                 $vendorProduct = $this->vendorProduct
                ->with([
                    'product.MeasurementClass',
                    'product.image','cart'=>function($q){
                        $q->where(['user_id'=>Auth::guard('api')->user()->id,'zone_id'=>Auth::guard('api')->user()->zone_id]);
                        },'wishList'=>function($q){
                        $q->where(['user_id'=>Auth::guard('api')->user()->id]);
                    }])->whereIn('user_id',$useridarray)->has('offer');
                $offer= $vendorProduct->take(15)->get()->map(function ($offer)use($match_in_zone){
                    $offer['match_in_zone']=$match_in_zone;
                    return $offer;
                });
            }else{
                   $vendorProduct = $this->vendorProduct
                ->with([
                    'product.MeasurementClass',
                    'product.image'])->whereIn('user_id',$useridarray)->has('offer');
                $offer= $vendorProduct->take(15)->get()->map(function ($offer)use($match_in_zone){
                    $offer['match_in_zone']=$match_in_zone;
                    return $offer;
                });

            }
            }
          $is_first_order =false;
           $membership = $this->membership->get();
           if(Auth::guard('api')->user()){
           foreach($membership as $mmk=>$mmv){
             $mmv->is_active=false;
             $mmv->daysleft = "";
             $userdata =  $this->user->where('id',Auth::guard('api')->user()->id)->first();
            if($mmv->id==$userdata->membership){
                $membership_to = date('Y-m-d',strtotime(Auth::guard('api')->user()->membership_to));
                if($membership_to>=date('Y-m-d')){
                    $future = strtotime($membership_to); //Future date.
                    $timefromdb = strtotime(date('Y-m-d')); //source time
                    $timeleft = $future-$timefromdb;
                    $daysleft = round((($timeleft/24)/60)/60);
                    if($daysleft == 0){
                    $mmv->daysleft = "0 day Left";
                    }elseif($daysleft < 0){
                    $mmv->daysleft = "Membership expired!";
                    }else{
                    $mmv->daysleft = $daysleft. ' days left';
                    }
                    $mmv->is_active=true;
                }
            }
           }
              $order_count = $this->order->where('user_id',Auth::guard('api')->user()->id)->count();
              if($order_count==0){
                $is_first_order=true;
                $first_order = $this->first_order->first();
                if(!empty($first_order->free_product)){
                  $free_product =  $first_order->free_product;
                   $freeproductdata = [];
                  foreach($free_product as $kk=>$vv){
                      $dd = $this->vendorProduct->with(['Product','cart'=>function($q){
                        $q->where(['user_id'=>Auth::guard('api')->user()->id,'zone_id'=>Auth::guard('api')->user()->zone_id]);
                        }])->where('product_id',$vv)->whereIn('user_id',$useridarray)->first();
                      if(!empty($dd)){
                        $dd->name = $dd->Product->name;
                        $dd->image = !empty($dd->Product->image)?$dd->Product->image->name:url('storage/app/public/upload');
                        $dd->offerprice = "Free";
                        
                        unset($dd->Product);
                        $freeproductdata[$kk] = $dd;
                      }
                  }
                 unset($first_order->free_product);
                  $first_order->free_product_data = $freeproductdata;
                }
             }
       }else{
                  $is_first_order=true;
                $first_order = $this->first_order->first();
                if(!empty($first_order->free_product)){
                  $free_product =  $first_order->free_product;
                   $freeproductdata = [];
                  foreach($free_product as $kk=>$vv){
                      $dd = $this->vendorProduct->with(['Product'])->where('product_id',$vv)->whereIn('user_id',$useridarray)->first();
                      if(!empty($dd)){
                        $dd->name = $dd->Product->name;
                        $dd->image = !empty($dd->Product->image)?$dd->Product->image->name:url('storage/app/public/upload/404.jpg');
                        $dd->offerprice = "Free";
                        unset($dd->Product);
                            $freeproductdata[$kk] = $dd;

                      }
                  }
                 unset($first_order->free_product);
                  $first_order->free_product_data = $freeproductdata;
                }
   }
            //TODO end get vendor offer product
            $data['slider'] = $slider;
            $data['category'] = $category;
            $data['is_first_order'] = $is_first_order;
            if($is_first_order) { $data['first_order'] =  $first_order ; }
            $data['offer'] = $offer;
            $data['memberships'] = $membership;
            $data['ads'] = $ads;
            $data['match_in_zone'] = $match_in_zone;
            $data['zone_id'] = $zone_id;
            $data['zone_name'] = $zone_name;
           $data['topselling'] = $this->getTopSellingProduct($zone_id,$match_in_zone);
            $data['topessentail'] = $this->offerdata($zone_id, $match_in_zone);

            if(Auth::guard('api')->user()){
                $data['cart_count'] = count(Helper::checkProducInCart(Auth::guard('api')->user()->id,Auth::guard('api')->user()->zone_id));
            }else{
                $data['cart_count'] = 0;
            }
            return $this->listResponse($data);
        } catch (\Exception $e) {
            return $e;
            return $this->clientErrorResponse($e);
        }
    }
public function isPointInPolygon($latitude, $longitude, $latitude_array, $longitude_array) {
    $size = count($longitude_array);
    $flag1 = false;
    $k = $size - 1;
    $j = 0;
    while ($j < $size) {
        $flag = false;
        $flag2 = false;
        $flag3 = false;
        if ($latitude_array[$j] > $latitude) {
            $flag2 = true;
        } else {
            $flag2 = false;
        }
        if ($latitude_array[$k] > $latitude) {
            $flag3 = true;
        } else {
            $flag3 = false;
        }
        $flag = $flag1;
        if ($flag2 != $flag3) {
            $flag = $flag1;
            if ($longitude < (($longitude_array[$k] - $longitude_array[$j]) * ($latitude - $latitude_array[$j])) / ($latitude_array[$k] - $latitude_array[$j]) +
                $longitude_array[$j]) {
                if (!$flag1) {
                    $flag = true;
                } else {
                    $flag = false;
                }
            }
        }
        $k = $j;
        $j++;
        $flag1 = $flag;
    }
    return $flag1;
}
    public function checkZone(Request $request)
    {
        $zone_id = '';
        $zoneArray = [];
        $zArray = [];
        $fArray = [];
        $finalArray = [];
        $validator = Validator::make($request->all(), [

            'lat'=>'required',
            'lng' => 'required'

        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);

        }
        $zonedata = DB::table('zones')->select('id',DB::raw("ST_AsGeoJSON(point) as json") )->where('deleted_at',null)->where('status','=', 1)->get();
        if(isset($zonedata)){
            $json_arr = json_decode($zonedata, true);
            foreach ($json_arr as $zvalue) {
                $zone_id=$zvalue['id'];
                $json=json_decode($zvalue['json']);
                $coordinates=$json->coordinates;
                $new_coordinates=$coordinates[0];
                $lat_array=array();
                $lng_array=array();
                foreach($new_coordinates as $new_coordinates_value){
                    $lat_array[]=$new_coordinates_value[0];
                    $lng_array[]=$new_coordinates_value[1];


                }
           
            $is_exist = $this->isPointInPolygon($request->lat, $request->lng,$lat_array,$lng_array);
           
            if($is_exist){
                $zData = ZoneTranslation::where('zone_id', $zone_id)->where('locale', App::getLocale())->first();
                $data['match_in_zone'] = true;
                $data['zone_id'] = $zone_id;
                $data['zone_name'] = $zData->name;
                return $this->listResponse($data);
            }

            }
            $zone = Zone::where('status','=','1')->where('is_default','=','1')->withoutGlobalScope(StatusScope::class)->first();
                  
            
            if(isset($zone)){
                $zone_id_default = $zone->id;
            }else{
                $zone_id_default = 24;
            }
            $zData = ZoneTranslation::where('zone_id', $zone_id_default)->where('locale', App::getLocale())->first();
            $data['match_in_zone'] = false;
            $data['zone_id'] = $zone_id_default;
            $data['zone_name'] = $zData->name;
            
             return $this->listResponse($data);
        }else{
            return $this->userNotExistResponse('There is no zone');
        }
       

         /*old code*/

        /*$zone = Zone::whereRaw('st_CONTAINS(point, point('.$request->lat.','.$request->lng.'))')->where('status','=','1')->withoutGlobalScope(StatusScope::class)->first();

            $match_in_zone=false;
            if($zone == null){
                $zone = Zone::where('status','=','1')->where('is_default','=','1')->withoutGlobalScope(StatusScope::class)->first();
                $match_in_zone=false;
                $zone_id = $zone->id;
                $zone_name = $zone->name;

            }else{

                $match_in_zone=true;
                $zone_id = $zone->id;
                $zone_name = $zone->name;
            }
            $data['zone_id'] = $zone_id;
            $data['match_in_zone'] = $match_in_zone;
            $data['zone_name'] = $zone_name;
            return $this->listResponse($data);*/

    }
     public function getZoneData($lat, $lng)
    {
        $zone_id = '';
        $zoneArray = [];
        $zArray = [];
        $fArray = [];
        $finalArray = [];
      if(Auth::guard('api')->user()){
        $zonedata = DB::table('zones')->select('id',DB::raw("ST_AsGeoJSON(point) as json") )->where('deleted_at',null)->where('status','=','1')->get();
      
            $json_arr = json_decode($zonedata, true);
            foreach ($json_arr as $zvalue) {
                $zone_id=$zvalue['id'];
                $json=json_decode($zvalue['json']);
                $coordinates=$json->coordinates;
                $new_coordinates=$coordinates[0];
                $lat_array=array();
                $lng_array=array();
                foreach($new_coordinates as $new_coordinates_value){
                    $lat_array[]=$new_coordinates_value[0];
                    $lng_array[]=$new_coordinates_value[1];


                }
           
            $is_exist = $this->isPointInPolygon($lat, $lng,$lat_array,$lng_array);
            if($is_exist){
                $zData = ZoneTranslation::where('zone_id', $zone_id)->where('locale', App::getLocale())->first();
                $data['match_in_zone'] = true;
                $data['zone_id'] = $zone_id;
                $data['zone_name'] = $zData->name;
                return $data;
            }


            }
        }
            $zone = Zone::where('status','=','1')->where('is_default','=','1')->withoutGlobalScope(StatusScope::class)->first();
            if(isset($zone)){
                $zone_id_default = $zone->id;
            }else{
                $zone_id_default = 24;
            }
            $zData = ZoneTranslation::where('zone_id', $zone_id_default)->where('locale', App::getLocale())->first();
            $data['match_in_zone'] = false;
            $data['zone_id'] = $zone_id_default;
            $data['zone_name'] = $zData->name;
            return $data;
       
       

       
    }


    public function deliveryDay_old(Request $request){

        $validator = Validator::make($request->all(), [
            'delivery_date'=>'required|date_format:Y-m-d|after:yesterday',
        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }
        $data = DeliveryDay::with('deliveryTime')->get()->toArray();
        $result=[];
        foreach ($data as $date){
            $temp = [];
            foreach ($date['delivery_time'] as $time){
                $time['no_of_order']=ProductOrder::where(['delivery_time_id'=>$time['id'],'delivery_date'=>$request->delivery_date])->count();
                $temp[]=$time;
            }
            $date['delivery_time'] = $temp;
            $result[] = $date;
        }
        $response = [
            'code' => 0,
            'error' => false,
            'message'=>trans('site.success'),
            'data' => $result,
            "payment_mode"=> PaymentMode::listsTranslations('name')->get(),
        ];
        return response()->json($response, 200);
       // return $this->listResponse($result);
    }

    public function deliveryDay(Request $request)
    {
        $dataArray = [];
        $time = time();
        $today_date = now();
        $to_day = $today_date->format('l');
        //return trans('site.'. lcfirst($to_day));
        $tomorrow_date = now()->addDay();
        $tomorrow_day = $tomorrow_date->format('l');
        $next_tomorrow_date = now()->addDays(2);
        $next_tomorrow_day = $next_tomorrow_date->format('l');
        $zone_id = Auth::guard('api')->user()->zone_id;
        $zone = $this->zone->findorfail($zone_id);

        if($zone==null){
            return $this->notFoundResult(trans('order.zone_deleted'));
        }
        //return $zone->weekPackage->$to_day->listsTranslations('name','id')->get();
        $today_data = $zone->weekPackage->$to_day->getSlotTimes()->map(function ($today_data)use($today_date) {
                 $today_data['no_of_order']=ProductOrder::where(['delivery_time_id'=>$today_data->id,'delivery_date'=>$today_date->format('Y-m-d')])->count();
              
                $to_time = strtotime($today_data['to_time']);  
                $from_time = strtotime($today_data['from_time']);
                /*Time is coming as per server time zone Africa/Khartoum*/
                $tt_time = time(); 
                $dateTime = now(); 
              
                 $lock_time = strtotime($today_data['lock_time']); 
                if($tt_time >= $lock_time || $tt_time >= $from_time ){
					$today_data['is_clickable'] = 'N';
				}else{
					$today_data['is_clickable'] = 'Y';
				}
      
                 $today_data['to_time'] = date('h:i A',strtotime($today_data['to_time']));
                 $today_data['from_time'] = date('h:i A',strtotime($today_data['from_time']));


                $today_data['current_time'] = $dateTime->format("d/m/Y  H:i A");
            return $today_data;
            });


     //   print_r($today_data);die;
           
        $tomorrow_data = $zone->weekPackage->$tomorrow_day->getSlotTimes()->map(function ($tomorrow_data)use($tomorrow_date) {
			//dd($tomorrow_date);
            $tomorrow_data['no_of_order']=ProductOrder::where(['delivery_time_id'=>$tomorrow_data->id,'delivery_date'=>$tomorrow_date->format('Y-m-d')])->count();
			
			$tomorrow_data['is_clickable'] = 'Y';
            $tomorrow_data['to_time']=date('h:i A',strtotime($tomorrow_data['to_time']));
            $tomorrow_data['from_time'] = date('h:i A',strtotime($tomorrow_data['from_time']));
				
            return $tomorrow_data;
        });

        $next_tomorrow_data = $zone->weekPackage->$next_tomorrow_day->getSlotTimes()->map(function ($next_tomorrow_data)use($next_tomorrow_date) {
            $next_tomorrow_data['no_of_order']=ProductOrder::where(['delivery_time_id'=>$next_tomorrow_data->id,'delivery_date'=>$next_tomorrow_date->format('Y-m-d')])->count();
            
            $next_tomorrow_data['is_clickable'] = 'Y';
		    $next_tomorrow_data['to_time']=date('h:i A',strtotime($next_tomorrow_data['to_time']));
            $next_tomorrow_data['from_time'] = date('h:i A',strtotime($next_tomorrow_data['from_time']));
				
            return $next_tomorrow_data;
        });
$tomorrow_day_name = $tomorrow_date->format('D');
$next_tomorrow_day_name = $next_tomorrow_date->format('D');

        $dataArray=[
            ['name'=> "Today",'date'=>$today_date->format('Y-m-d'),'delivery_time'=>$today_data],
            ['name'=>Ucfirst($tomorrow_day_name),'date'=>$tomorrow_date->format('Y-m-d'),'delivery_time'=>$tomorrow_data],
            ['name'=>Ucfirst($next_tomorrow_day_name),'date'=>$next_tomorrow_date->format('Y-m-d'),'delivery_time'=>$next_tomorrow_data]
        ];

        $response = [
            'code' => 0,
            'error' => false,
            'message'=>trans('site.success'),
            'data' => $dataArray,
            "payment_mode"=>PaymentMode::listsTranslations('name')->get(),
        ];
        return response()->json($response, 200);
        // return $this->listResponse($result);
    }



    public function getPhoneCode(Request $request){
        $data['phone_code'] = CountryPhoneCode::pluck('phonecode','phonecode');

        return $this->listResponse($data);
    }

    public function inviteFriend(Request $request){
        //dd(json_decode($request->email));


        try {
             Mail::to($request->email)->send(new InviteFriend());
            return $this->listResponse([]);
        } catch (\Exception $e) {
            return $this->clientErrorResponse($e);
        }

    }

    public function globalSetting(){
    
        $data  = SiteSetting::select('free_delivery_charge','phone','whats_up')->firstOrFail();
        $SocialMedia = SocialMedia::firstOrFail();
        $AppSetting = AppSetting::select('mim_amount_for_order','mim_amount_for_free_delivery','ios_app_store','android_play_store','update_shopper_location', 'update_driver_location', 'update_shopper_app', 'update_driver_app')->firstOrFail();
        $data["min_price"] =  $AppSetting->mim_amount_for_order;
        $data["max_price"] =  $AppSetting->mim_amount_for_free_delivery;
        $data["update_shopper_location"] =  (int) filter_var($AppSetting->update_shopper_location, FILTER_SANITIZE_NUMBER_INT)* 60;
        $data["update_driver_location"] =  (int) filter_var($AppSetting->update_driver_location, FILTER_SANITIZE_NUMBER_INT)* 60;
        $data["update_shopper_app"] =  (int) filter_var($AppSetting->update_shopper_app, FILTER_SANITIZE_NUMBER_INT)* 60;
        $data["update_driver_app"] =  (int) filter_var($AppSetting->update_driver_app, FILTER_SANITIZE_NUMBER_INT)* 60;
        $data["android_play_store"] =  $AppSetting->android_play_store;
        $data["ios_play_store"] =  $AppSetting->ios_app_store;
        $data["facebook_page"] =  $SocialMedia->facebook_page;
        $data["twitter_page"] =  $SocialMedia->twitter_page;
        $data["instagram_page"] =  $SocialMedia->instagram_page;
        $data["linkedin_page"] =  $SocialMedia->linkedin_page;
        $data["whatsapp_share"] =  $SocialMedia->whatsapp_share;
        $data["facebook_share"] =  $SocialMedia->facebook_share;
        $data["instagram_share"] =  $SocialMedia->instagram_share;
        $data["twitter_share"] =  $SocialMedia->twitter_share;
        $data["linkedin_share"] =  $SocialMedia->linkedin_share;
        $data["other_share"] =  $SocialMedia->other_share;
        $data["facebook_follow"] =  $SocialMedia->facebook_follow;
        $data["twitter_follow"] =  $SocialMedia->twitter_follow;
        $data["instagram_follow"] =  $SocialMedia->instagram_follow;
        $data["linkedin_follow"] =  $SocialMedia->linkedin_follow;
        
        return $this->listResponse($data);

    }
    public function appVersion(){
    
        $data  = [];
        $AppSetting = AppVersion::firstOrFail();
        $AppDetail = AppSetting::firstOrFail();
        $data['customer']["android_current_version"] =  $AppSetting->customer_android_current_version;
        $data['customer']["android_mandatory_update"] =  $AppSetting->customer_android_mandatory_update;
        $data['customer']["android_main_tenance_mode"] =  $AppSetting->customer_android_main_tenance_mode;
        $data['driver']["android_current_version"] =  $AppSetting->driver_android_current_version;
        $data['driver']["android_mandatory_update"] =  $AppSetting->driver_android_mandatory_update;
        $data['driver']["android_main_tenance_mode"] =  $AppSetting->driver_android_main_tenance_mode;
        $data['shopper']["android_current_version"] =  $AppSetting->shopper_android_current_version;
        $data['shopper']["android_mandatory_update"] =  $AppSetting->shopper_android_mandatory_update;
        $data['shopper']["android_main_tenance_mode"] =  $AppSetting->shopper_android_main_tenance_mode;
        
         $data['customer-ios']["ios_current_version"] =  $AppSetting->ios_current_version;
        $data['customer-ios']["ios_mandatory_update"] =  $AppSetting->ios_mandatory_update;
        $data['customer-ios']["ios_main_tenance_mode"] =  $AppSetting->ios_main_tenance_mode;
        $data["ios_play_store"] =  $AppDetail->ios_app_store;
        $data["android_play_store"] =  $AppDetail->android_play_store;
        $data["android_play_store_driver"] =  $AppDetail->android_play_store_driver;
        $data["android_play_store_shopper"] =  $AppDetail->android_play_store_shopper;
        return $this->listResponse($data);

    }



    public function getTopSellingProduct($zone_id,$match_in_zone){
        try {            
            $user  = User::select('*');
            $user->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor']);
            $user = $user->get();
            if(!isset($user)){
                 $vendorProduct = [];
            }else{
                $useridarray = [];
                foreach($user as $userkey=>$uservalue){
                    $useridarray[] = $uservalue->id;
                }

                $results = ProductOrderItem::select(DB::raw( "vendor_product_id,COUNT(id) as cnt"))->groupBy('vendor_product_id')->orderBy('cnt', 'DESC')->paginate(20)->toArray();
                $resultArray=[];
                if(!empty($results['data'])){
                    foreach ($results['data'] as $result) {
                        $pid = $result['vendor_product_id'];
                        if(Auth::guard('api')->user()){
                            $vendorProduct = $this->vendorProduct
                                ->with([
                                    'product.MeasurementClass',
                                    'product.image','cart'=>function($q){
                                        $q->where(['user_id'=>Auth::guard('api')->user()->id,'zone_id'=>Auth::guard('api')->user()->zone_id]);
                                    },
                                    'wishList'=>function($q){
                                        $q->where(['user_id'=>Auth::guard('api')->user()->id]);
                                    }
                                ])
                                ->where('id',$pid)->first();

                        }else{
                             $vendorProduct = $this->vendorProduct
                            ->with(['product.MeasurementClass','product.image'])->where('id',$pid)->first();
                        }
                        //echo "<pre>"; echo $vendorProduct->product->image->name; die();

                        
                        if(!empty($vendorProduct)){
                            $vendorProduct->product->pic = isset($vendorProduct->product->image->name) ? $vendorProduct->product->image->name : '';
                            $vendorProduct->match_in_zone = $match_in_zone;
                        }
                        $resultArray[] = $vendorProduct;
                    }
                    

                }
            }
/*
            $data=[];
            if(!empty($resultArray)){
                foreach ($resultArray as $rec){
                    $rec['product']['image'] = isset($rec['product']['image']['name']) ? $rec['product']['image']['name'] : '';
                    $data[]=$rec;
                }
                unset($resultArray);
                $resultArray = $data; 
            }*/
            
            return array_values(array_filter($resultArray));

        }catch (\Exception $e) {
            return $this->clientErrorResponse($e); die();
        }        

    }


    public function offerdata($zone_id, $match_in_zone){
        $user = User::select('*');
        $user->whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor']);
        $user = $user->get()->toArray();
        $product_data=[];
        $user_id_array=[];
        
        foreach($user as $kk=>$vv){
            $user_id_array[] = $vv['id'];
            $product_data[$vv['id']] = $this->vendorProduct->where('user_id',$vv['id'])->where('status','1')->get()->toArray();
        }

        $offer_product_id_array = [];
        if(Auth::guard('api')->user()){

            $offer_products = $this->vendorProduct->with(['Product','product.MeasurementClass','product.image',
                'cart'=>function($q) use($zone_id){
                    $q->where(['user_id'=>Auth::guard('api')->user()->id,'zone_id'=>$zone_id]);
                },
                'wishList'=>function($q){
                    $q->where(['user_id'=>Auth::guard('api')->user()->id]);
                }
            ])
            ->whereHas('product',function($q){ $q->where('status','1'); })->whereIn('user_id',$user_id_array)->whereNOTNULL('offer_id')->get();
        }else{

            $offer_products = $this->vendorProduct->with(['Product','product.MeasurementClass','product.image'])
            ->whereHas('product',function($q){ $q->where('status','1'); })->whereIn('user_id',$user_id_array)->whereNOTNULL('offer_id')->get();
        }

        foreach($offer_products as $offer_product){
            $pffer_data = $this->offer->where('id',$offer_product->offer_id)->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
            
            if(!empty($pffer_data)){
                $offer_product_id_array[] = $offer_product->product_id;
            }
        }

        if(Auth::guard('api')->user()){

            $vendorProduct =  $this->vendorProduct->with(['product.MeasurementClass','product.image',
                'cart'=>function($q) use($zone_id){
                    $q->where(['user_id'=>Auth::guard('api')->user()->id,'zone_id'=>$zone_id]);
                },
                'wishList'=>function($q){
                    $q->where(['user_id'=>Auth::guard('api')->user()->id]);
                }
            ])
            ->whereHas('product',function($q){ $q->where('status','1'); })->whereIn('user_id',$user_id_array)->whereNOTNULL('offer_id');
        }else{

            $vendorProduct =  $this->vendorProduct->with(['product.MeasurementClass','product.image'])
            ->whereHas('product',function($q){ $q->where('status','1'); })->whereIn('user_id',$user_id_array)->whereNOTNULL('offer_id');
        }

        if(!empty($vendorProduct)){
            $vProduct = $vendorProduct= $vendorProduct->groupBy('product_id')->take(10000)->get();
            $vendorProduct= $vendorProduct->toArray();
        }

        $data=[];
        if(!empty($vendorProduct)){
            foreach ($vendorProduct as $rec){
            $rec = $rec;
            $rec['price'] = number_format($rec['price'],2,'.','');   
            $rec['offer_price'] = number_format($rec['price'],2,'.','');   
   
            $rec['offer_data'] =   $ffer_data = $this->offer->where('id',$rec['offer_id'])->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
            
            if(!empty($ffer_data)){
                $rec['is_offer'] = true;
                $rec['offer_id'] = $rec['offer_id'];
                
                if($ffer_data->offer_type=='amount'){
                    $rec['offer_price'] = $rec['price']- $ffer_data->offer_value;
                }else if($ffer_data->offer_type=='percentages'){
                    $rec['offer_price'] = $rec['price'] -( $rec['price'] * ( $ffer_data->offer_value / 100 )) ;                 
                }
                
                $rec['offer_price'] = number_format( $rec['offer_price'],2,'.','');   
                $data[] = $rec;                       
            }     
        }
        unset($vendorProduct);
        $vendorProduct = $data; 
    }

    $data=[];
    if(!empty($vendorProduct)){
        foreach ($vendorProduct as $rec){
            $rec['match_in_zone'] = $match_in_zone;
            $rec['product']['image'] = isset($rec['product']['image']['name']) ? $rec['product']['image']['name'] : '';
            unset($rec['product']['related_products']/*,$rec['product']['category_id']*/);
            $data[]=$rec;
        }
        unset($vendorProduct);
        $vendorProduct = $data; 
    }
    //echo "<pre>"; print_r($vendorProduct); die;
    return $vendorProduct;
 }


 public function testSMS(){
    $client = new Client();
    $authkey = env('AUTHKEY');
    $phone_number = '919024162637';
    $senderid = env('SENDERID');
    $hash = env('SMSHASH');
    //$message="Your OTP for Darbaar Mart is ".$otp;
    $otp = rand(100000,999999);
    $tmp_id = '1207162028126071690';
    $message=urlencode("Dear Customer, use OTP ($otp) to log in to your DARBAAR MART account and get your grocery essentials safely delivered at your home.\n\r \n\rStay Home, Stay Safe.\n\rTeam Darbaar Mart, Beawar $hash");
//echo "http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91&DLT_TE_ID=1207161537738715065"; die;
    $response = $client->request('GET',"http://login.yourbulksms.com/api/sendhttp.php?authkey=".$authkey."&mobiles=".$phone_number."&message=".$message."&sender=".$senderid."&route=4&country=91&DLT_TE_ID=".$tmp_id);
    //$statusCode = $response->getStatusCode();
    print_r($response);
 }

}
