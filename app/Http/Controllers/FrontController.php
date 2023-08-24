<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Slider;
use App\OfferSlider;
use App\Ads;
use App\Category;
use App\CategoryTranslation;
use App\VendorProduct;
use App\Product;
use App\ProductTranslation;
use App\User;
use App\CountryPhoneCode;
use App\Offer;
use App\Zone;
use App\AppSetting;
use App\ZoneTranslation;
use App\AccessLevel;
use App\DeliveryLocation;
use App\Helpers\Helper;
use App\Tempcustomers;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Illuminate\Contracts\Auth\Authenticatable;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mail;

use App\ProductOrderItem;
use GuzzleHttp\Client;

class FrontController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Front Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles redirecting them to your home screen. 
    |
    */

    /**
     * Where to redirect users before login.
     *
     * @var string
     */
    protected $redirectTo = '/';

  
      /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(Request $request,CategoryTranslation $category,Tempcustomers $user,Product $product,Offer $offer,VendorProduct $vendorProduct,User $customer) 
    {
        parent::__construct();
        $this->category=$category;
        $this->user=$user;
        $this->customer=$customer;
        $this->offer=$offer;
        $this->product=$product;
        $this->vendorProduct=$vendorProduct;
        $this->vendorProduct=$vendorProduct;
        $this->middleware('guest')->except('logout');
    }

public function index(Request $request){
  $category =  $this->category->join('categories','categories.id','=','category_translations.category_id')->select('categories.id','image','name','category_translations.slug')->where(['locale' => 'en'])->where(['categories.parent_id' => '0'])->where(['categories.status' => '1'])->whereNull('categories.deleted_at')->orderBy('sort_no', 'ASC')->get();
        
  $slider = Slider::with('category','category.translations','sub_category','product');
  $offer_sliders = OfferSlider::with('category','sub_category','product');
  $ads = Ads::with('category','sub_category','product');

  $Zone= Zone::all();
  $zone_id =0;
  foreach($Zone as $k=>$v ){
    if($v['is_default']){ $zone_id =$v['id'];  }
  }
  $user = User::where('user_type','vendor')->get();
  $user_id_array = [];
  foreach ($user as $key => $value) {
    $user_id_array[] = $value->id;
  }
  if($zone_id==0){
    $slider = $slider->get();
    $offer_sliders = $offer_sliders->get();
    $ads = $ads->get();
    $product_id_array=[];$data=[]; $offer_arr=[];
    $all_offer = $this->vendorProduct->where('user_id',$user_id_array)->whereNOTNULL('offer_id')->groupBy('product_id')->get();
    foreach($all_offer as $key=>$value){  $product_id_array[]=$value->product_id; }
    $vendorProduct =  $this->product->with([ 'MeasurementClass','image'])->whereIn('id',$product_id_array)->get()->toArray();
    if(!empty($vendorProduct)){
      foreach ($vendorProduct as $rec){
        $rec['is_offer'] = 0;
        $rec['offer_id'] = 0;
        $rec['offer_data'] = [];
        $rec['price'] = $rec['price'];
        $rec['mrp'] = number_format(!empty($rec['best_price']) ? $rec['best_price']:$rec['price'],2,'.','');
        $rec['offer_price'] = $rec['price'];

        $productData =  $this->vendorProduct->where('user_id',$user_id_array)->where('product_id',$rec['id'])->first();
        if(!empty($productData) && !empty($productData->offer_id)){
          $rec['offer_id'] = $productData->offer_id;
          $rec['offer_data'] = $ffer_data = $this->offer->where('id',$productData->offer_id)->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
          if(!empty($ffer_data) && (strtotime($ffer_data->to_time)>=time()) ){ 
            $rec['is_offer'] = 1;
            $rec['offer_id'] = $productData->offer_id;
            if($ffer_data->offer_type=='amount'){
              $rec['offer_price'] = $productData->price - $ffer_data->offer_value;
            }else if($ffer_data->offer_type=='percentages'){
              $rec['offer_price'] = $productData->price -( $productData->price * ( $ffer_data->offer_value / 100 )) ;                 
            }
            $rec['offer_price'] = number_format( $rec['offer_price'],2,'.','');
            $rec['mrp'] = number_format(!empty($rec['offer_price']) ? $productData->price:$productData->best_price,2,'.','');                              
          }else{
            $rec['price'] = $productData->price;
            $rec['mrp'] = number_format(!empty($rec['offer_price']) ? $productData->price:$productData->best_price,2,'.','');
            $rec['offer_price'] = $productData->price;
          }
        }
        if($rec['is_offer']){
          unset($rec['category_id'],$rec['related_products']);
          $data[]=$rec; 
        }
      }
      unset($vendorProduct);
      $vendorProduct = $data; 
    }
  }else{
    $vendorProduct = [];
    $slider = $slider->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->get();
    foreach( $slider as $sliders){
      if($sliders->link_type=='internal'){
        if($sliders->link_url_type=='product'){
          $vproduct = $this->vendorProduct->where('id',$sliders->vendor_product_id)->first();
          if(!empty($vproduct)){
            $product = $this->product->where('id',$vproduct->product_id)->first();
            $sliders->rawslug = $product->slug;
          }else{ 
            $sliders->rawslug = "";
          }
        }else if($sliders->link_url_type=='category'){
          $subcat = $this->category->where('id',$sliders->cat_id)->first();
          if(!empty($subcat)){ $sliders->rawslug = $subcat->slug; }
          else{ $sliders->rawslug = "";}
        }else if($sliders->link_url_type=='subcategory'){
          $subcat = $this->category->where('id',$sliders->sub_cat_id)->first();
          if(!empty($subcat)){$sliders->rawslug = $subcat->slug; }
          else{  $sliders->rawslug = "";}
        }
      }
    }
    $ads =  $ads->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->get();
    foreach( $ads as $ad){
      if($ad->link_type=='internal'){
        if($ad->link_url_type=='product'){
          $vproduct = $this->vendorProduct->where('id',$ad->vendor_product_id)->first();
          if(!empty($vproduct)){
            $product = ProductTranslation::where('product_id',$vproduct->product_id)->first();
            if(!empty($product)){
              $ad->rawslug = $product->slug;
            }
          }else{ $ad->rawslug = "";}
        }else if($ad->link_url_type=='category'){
          $subcat = CategoryTranslation::where('category_id',$ad->cat_id)->first();
          if(!empty($subcat)){ $ad->rawslug = $subcat->slug; }
          else{ $ad->rawslug = "";}
        }else if($ad->link_url_type=='subcategory'){
          $subcat = CategoryTranslation::where('category_id',$ad->sub_cat_id)->first();
          if(!empty($subcat)){$ad->rawslug = $subcat->slug; }
          else{  $ad->rawslug = "";}
        }
      }
    }
    $offer_sliders =  $offer_sliders->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->get();
    foreach( $offer_sliders as $offer_slider){
      if($offer_slider->link_type=='internal'){
        if($offer_slider->link_url_type=='product'){
          $vproduct = $this->vendorProduct->where('id',$offer_slider->vendor_product_id)->first();
          if(!empty($vproduct)){
            $product = ProductTranslation::where('product_id',$vproduct->product_id)->first();
            if(!empty($product)){
              $offer_slider->rawslug = $product->slug;
            }
          }else{ $offer_slider->rawslug = "";}
        }else if($offer_slider->link_url_type=='category'){
          $subcat = CategoryTranslation::where('category_id',$offer_slider->cat_id)->first();
          if(!empty($subcat)){ $offer_slider->rawslug = $subcat->slug; }
          else{ $offer_slider->rawslug = "";}
        }else if($offer_slider->link_url_type=='subcategory'){
          $subcat = CategoryTranslation::where('category_id',$offer_slider->sub_cat_id)->first();
          if(!empty($subcat)){$offer_slider->rawslug = $subcat->slug; }
          else{  $offer_slider->rawslug = "";}
        }
      }
    }
    $offer = $offer_arr =  [];
    $user  = User::select('*');
    $user->whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor']);
    $user = $user->get()->toArray();
    foreach($user as $kk=>$vv){
      $user_id_array[] = $vv['id'];
    }
    $offerProduct =  $this->vendorProduct->with(['product.MeasurementClass',
      'product.image'])->whereHas('product',function($q){ $q->where('status','1'); }  )->whereIn('user_id',$user_id_array)->whereNOTNULL('offer_id')->groupBy('product_id');
    $offerProduct=  $offerProduct->get();
    $data=[];
    if(!empty($offerProduct)){
      $offerProduct = $offerProduct->toArray();
      // echo "<pre>"; print_r($offerProduct); die;
      foreach ($offerProduct as $rec){
        $rec['price'] = number_format($rec['price'],2,'.','');    
        $rec['offer_price'] = number_format($rec['price'],2,'.','');
        $rec['mrp'] = number_format(!empty($rec['offer_price']) ? $rec['price']:$rec['best_price'],2,'.','');  
        $rec['offer_data'] = $ffer_data = $this->offer->where('id',$rec['offer_id'])->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
        if(!empty($ffer_data)  ){ 
          $rec['is_offer'] = true;
          $rec['offer_id'] = $rec['offer_id'];
          if($ffer_data->offer_type=='amount'){
            $rec['offer_price'] = $rec['price'] - $ffer_data->offer_value;
          }else if($ffer_data->offer_type=='percentages'){
            $rec['offer_price'] = $rec['price']-( $rec['price'] * ( $ffer_data->offer_value / 100 )) ;       
          }
          $rec['offer_price'] = number_format( $rec['offer_price'],2,'.',''); 
          $rec['mrp'] = number_format(!empty($rec['offer_price']) ? $rec['price']:$rec['best_price'],2,'.','');
          if($rec['offer_price']>0) {
            $discount = ($rec['price'] - $rec['offer_price']) / $rec['price'];
          } else {
            $discount = ($rec['best_price'] - $rec['price']) / $rec['best_price'];
          }
          $discount = $discount * 100;
          $rec['discount'] = number_format($discount,2,'.','');
          $data[] = $rec;
        }
        unset($offerProduct);
        $vendorProduct = $offerProduct = $data; 
      }
    }
  }
  $super_deal = Helper::superDeal($zone_id);
  $appdata =  AppSetting::first();
  $topsellingproducts  =  $this->topsellingproducts($zone_id);
  return view('pages.index',['category' => $category,'Slider'=>$slider,'Ads'=>$ads,'offer'=>$vendorProduct,'appdata'=>$appdata,'offer_sliders'=>$offer_sliders,'topsellings'=>$topsellingproducts,'super_deal'=>$super_deal]);
}

public function topsellingproducts($zone_id){
  $user = User::select('*');
  $user->whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor']);
  $user = $user->get()->toArray();
  $product_data=[];
  $user_id_array=[];
  foreach($user as $kk=>$vv){
    $user_id_array[] = $vv['id'];
  }
  //print_r($user_id_array); die();

  $results = ProductOrderItem::select(DB::raw( "vendor_product_id,COUNT(id) as cnt"))->groupBy('vendor_product_id')->orderBy('cnt', 'DESC')->paginate(20)->toArray();
  $response_array = [];
  if(!empty($results['data'])){
    foreach ($results['data'] as $result) {
      $pid = $result['vendor_product_id'];
      if(Auth::guard()->user()){
        $vendorProduct =  $this->vendorProduct->with(['product.MeasurementClass','product.image',
          'cart'=>function($q) use($zone_id){
            $q->where(['user_id'=>Auth::user()->id,'zone_id'=>$zone_id]);
          },
          'wishList'=>function($q){
            $q->where(['user_id'=>Auth::user()->id]);
          }
        ])
        ->whereHas('product',function($q){ $q->where('status','1'); }  )
        ->whereIn('user_id',$user_id_array)->where('id',$pid);
        
        if(!empty($vendorProduct)){
          $vendorProduct= $vendorProduct->first();
        }
      }else{
        $vendorProduct = $this->vendorProduct->with(['product.MeasurementClass','product.image'])->whereHas('product',function($q){ $q->where('status','1'); }  )->where('id',$pid)->first();
      }
      if(!empty($vendorProduct)){
        $response_array[] = $vendorProduct;
      }
    }
    // dd($response_array);
   // die();
    $data=[];
    if(!empty($response_array)){
      foreach ($response_array as $rec){
        $rec['price'] = number_format($rec['price'],2,'.','');   
        $rec['mrp'] = number_format(!empty($rec['best_price']) ? $rec['best_price']:$rec['price'],2,'.','');   
        $rec['offer_price'] = number_format($rec['price'],2,'.','');   
        if(!empty($rec['offer_id'])){
          $rec['offer_data'] =   $ffer_data = $this->offer->where('id',$rec['offer_id'])->where('from_time','<=',date('Y-m-d'))->where('to_time','>=',date('Y-m-d'))->first();
          if(!empty($ffer_data)){
            $rec['is_offer'] = true;
            $rec['offer_id'] = $rec['offer_id'];
            if($ffer_data->offer_type=='amount'){
              $rec['offer_price'] = $rec['price']- $ffer_data->offer_value;
            }else if($ffer_data->offer_type=='percentages'){
              $rec['offer_price'] = $rec['price'] -( $rec['price'] * ( $ffer_data->offer_value / 100 ));
            }
            $rec['offer_price'] = number_format( $rec['offer_price'],2,'.',''); 
            $rec['mrp'] = number_format(!empty($rec['offer_price']) ? $rec['price']:$rec['best_price'],2,'.','');
            
            $discount = ($rec['price'] - $rec['offer_price']) / $rec['price'];
            $discount = $discount * 100;
            $rec['discount'] = number_format($discount,2,'.','');
            $data[]=$rec;                       
          }  
        }    
      }

      /*echo '<pre>';
      print_r($data);
      echo '</pre>';
      exit();*/
      unset($response_array);
      $response_array = $data; 
    }

    $data=[];
    if(!empty($response_array)){
      // dd($response_array);
      foreach ($response_array as $rec){
        $rec['match_in_zone']=true;
        $rec['product']['image'] = isset($rec['product']['image']['name']) ? $rec['product']['image']['name'] : '';
        unset($rec['product']['related_products']/*,$rec['product']['category_id']*/);
        $data[]=$rec;
      }
      unset($response_array);
      $response_array = $data; 
    }
  }

  return $response_array;
}


    
     public function updatedata($id){
        $countryPhoneCode  = CountryPhoneCode::pluck('phonecode','phonecode');     
        $validator = JsValidatorFacade::make($this->customer->rules('POST'));
        $user = $this->user->where('id',$id)->first();
        return view('auth.register')->with('validator',$validator)->with('countryPhoneCode',$countryPhoneCode)->with('user',$user);
    }
    public function updateZone(Request $request,$id){
        $request->session()->put('zone_id', $id);
        $user = $this->user->where(Auth::user()->id)->first();
        $user->zone_id = $id;
        $user->save();
        echo 1;
    }

    public function getZone(Request $request){
        $zone_id = $request->session()->get('zone_id');
        if(empty($zone_id)){ $request->session()->put('zone_id',Auth::user()->zone_id);  }
         echo   $zone_id;
    }

    /* slug Generate */

    public function categorySlug(Request $request){
        $category  = CategoryTranslation::select('id','category_id','locale','name')
                        ->where('locale','=','en')
                        ->whereNull('slug')
                        ->get();

        foreach($category as $cat){
            // $slug = new Slug();
            // $input['slug'] = $slug->createSlug($cat['name'],0,'Category');
             $input['slug'] =str_slug($cat['name'], '-');
            CategoryTranslation::where('category_id', $cat['category_id'])
                ->update(['slug' =>  $input['slug']]);
        }
    }

    public function productSlug(Request $request){
        $product  = ProductTranslation::select('id','product_id','locale','name')
            ->where('locale','=','en')
            ->whereNull('slug')
            ->get();

        foreach($product as $cat){
            // $slug = new Slug();
            // $input['slug'] = $slug->createSlug($cat['name'],0,'Product');
             $input['slug'] =str_slug($cat['name'], '-');
            ProductTranslation::where('product_id', $cat['product_id'])
                ->update(['slug' =>  $input['slug']]);
        }
    }

    

    

 public function zonecode(Request $request){
        $zonename  = ZoneTranslation::all();
        foreach($zonename as $input){
             ZoneTranslation::whereNOTNULL('name')->update(['code_name' => $input['name']]);
        }
    }


public function importExcel(Request $request)
{
$base = url('/');
$path =  '/public/Corrected_Arabic_Translation.xlsx';
$data = Excel::load($path)->get()->toArray();
if(isset($data[0][0])){
$valuess=$data[0];
}else{
$valuess=$data;
}
foreach ($valuess as $key => $value) {
$product = ProductTranslation::where('product_id',$value->id)->where('locale','ar')->first();
if(!empty($product)){
    $product->name =$product->corrected_arabic;
 $product->save();
}
}
}

public function transferimages(Request $request){

// $products =  DB::table('products AS p')
               // ->join('product_translations AS pt','pt.product_id', '=', 'p.id')
              //  ->join('images AS img','img.image_id', '=', 'p.id')
              //  ->whereNull('p.deleted_at')
              //  ->where([['p.status','=','1'],['pt.locale','=','en']])
              //  ->where('p.id','<',7000)->select('p.id','p.sku_code','p.category_id','p.measurement_class','p.measurement_value','p.related_products','p.price','p.per_order','pt.name','pt.description','pt.keywords','img.name as image')->orderby('p.id')->get();

 $categories =  DB::table('categories AS cat')
                ->join('category_translations AS cat_trans','cat_trans.category_id', '=', 'cat.id')
                ->whereNull('cat.deleted_at')
                ->where([['cat.status','=','1'],['cat_trans.locale','=','en']])
                ->where('cat.parent_id','!=',0)
                ->select('cat.id','cat_trans.image')->orderby('cat.id')->get();
//print_r($categories); die;
$i=0;
foreach($categories as $category){
 // print_r($category->image); 
//$org_image='/var/www/html/storage/app/public/upload/'.$product->image;
//$destination='/var/www/html/storage/app/public/pimg';
//$img_name=basename($org_image);
//if( rename( $org_image , $destination.'/'.$img_name )){echo 'moved!'; } else { echo 'failed'; }
if(Storage::disk('upload')->exists($category->image)){
$filePath = '/'.$category->image;                
$newname = url('storage/app/public/upload/'.$category->image);
$re = Storage::disk('s3')->put($filePath, file_get_contents($newname));
print_r($re);
$i++;
echo "  count".$i." file name ".$category->image." and  ";
}}//die;
}

public function forgotpassword(Request $request){
  $data = array('name'=>"Our Code World");
        // Path or name to the blade template to be rendered
        $template_path = 'email_template';

        Mail::send(['text'=> $template_path ], $data, function($message) {
            // Set the receiver and subject of the mail.
            $message->to('mahima.mathur@brsoftech.org', 'Mahima')->subject('Laravel First Mail');
            // Set the sender
            $message->from('info@brsoftech.org','Our Code World');
        });

        return "Basic email sent, check your inbox.";
  
}

public function sendpasswordform(){
   return view('pages.sendpassword');
}

public function sendpassword(Request $request){
 //echo "<pre>"; print_r($request->all()); die;


        $data = User::where(['phone_number'=>$request->mobile])->first();
         if(!empty($data)){
         $password = rand('000000','999999');
         $data->password = Hash::make($password);
         $data->update();  

        // $customerID='2369';
        // $userName='ZADCART';
        // $userPassword='J5L@wfB7OhrX8';
        // $smsText='Your OTP is '.$data->otp;
        // $recipientPhone=$data->phone_code.$data->phone_number;
        // $client = new Client();
        // $response = $client->request('GET',  "https://messaging.ooredoo.qa/bms/soap/Messenger.asmx/HTTP_SendSms?customerID=".$customerID."&userName=".$userName."&userPassword=".$userPassword."&originator=Zadcart&smsText=".$smsText."&recipientPhone=".$recipientPhone."&messageType=0&defDate=&blink=false&flash=false&Private=false");
        // $statusCode = $response->getStatusCode();
         Session::flash('success',"Message send to you Mobile number");
         return redirect('/login');
}else{
  Session::flash('danger',trans('user.check_details'));
  return redirect("url('/')");
}}


public function phpinfofunc(){

  echo phpinfo(); die;
}

}