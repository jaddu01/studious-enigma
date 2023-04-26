<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Slider;
use App\Ads;
use App\Category;
use App\CategoryTranslation;
use App\User;
use App\VendorProduct;
use App\CountryPhoneCode;
use App\Product;
use App\ProductTranslation;
use App\Offer;
use App\Zone;
use App\AccessLevel;
use App\DeliveryLocation;
use App\Helpers\Helper;
use App\Tempcustomers;
use App\Providers\RouteServiceProvider;
use App\ZoneTranslation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Illuminate\Contracts\Auth\Authenticatable;
use App;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\UrlGenerator;

class SearchController extends Controller
{

	    /**
     * UserController constructor.
     * @param User $user
     */
    public function __construct(Request $request,CategoryTranslation $category,Tempcustomers $user,Product $product,Offer $offer,VendorProduct $vendorProduct) 
    {
        parent::__construct();
        $this->category=$category;
        $this->user=$user; 
        $this->offer=$offer;
        $this->product=$product;
        $this->vendorProduct=$vendorProduct;
      //  $this->middleware('auth');
    }


 /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
        public function index(Request $request){
        	$input =$request->all();
            $preurl = url()->previous();
            /*if(stripos($preurl,"list/")!==false){
                $arr = explode("?", $preurl, 2);
                $pre_url = $arr[0];
               
                   $link = $pre_url.'?search='.$request->searchbox;
                
            }else{
                $link = url('/list/all?search='.$request->searchbox);

            }*/

            $link = url('/list/all?search='.$request->searchbox);
            $request->session()->put('searchData',$request->searchbox);
            $request->session()->put('searchType','p');
               
            return redirect($link);
       

        }

        public function searchProduct(Request $request) {
            $request = $request->all();
            $products  = ProductTranslation::select('product_translations.id','product_translations.product_id','product_translations.locale','product_translations.name','product_translations.slug','images.name as image')
                      ->where('product_translations.name', 'like', '%' . $request['text'] . '%')
                      ->where('products.status','=','1')
                      ->join('images','images.image_id','product_translations.product_id')
                      ->join('products','products.id','product_translations.product_id')
                      ->groupBy('product_translations.id')
                      //->limit(10)
                      ->get();
            if(isset($products) && !empty($products)) {
              $products = $products->toArray();
              foreach($products as $key=>$value) {
                $vendorProduct = VendorProduct::select('id')
                                  ->where('product_id','=',$value['product_id'])
                                  ->first();
                if(isset($vendorProduct) && !empty($vendorProduct)) {
                  $products[$key]['vendor_product_id'] = $vendorProduct['id'];
                } else {
                  unset($products[$key]);
                }
              }
            }
            $categories = CategoryTranslation::select('category_translations.id','category_translations.name','category_translations.category_id','category_translations.image','category_translations.slug')
                      ->where('category_translations.name', 'like', '%' . $request['text'] . '%')
                      ->where('categories.status','=','1')
                      ->join('categories','categories.id','category_translations.category_id')
                      //->limit(10)
                      ->get();
            $data = ['products'=>$products,'categories'=>$categories,'products_count'=>count($products),'categories_count'=>count($categories)];
            return response($data,200);
          }



   }