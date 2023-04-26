<?php

namespace App\Http\Controllers;

use App\Category;
use App\DeliveryDay;
use App\DeliveryLocation;
use App\Helpers\Helper;
use App\Scopes\StatusScope;
use App\SiteSetting;
use App\AppSetting;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use App\User;
use App\VendorProduct;
use App\Product;
use App\Offer;
use App\Zone;
use App\ZoneTranslation;
use DB;
use App;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Wallet Controller
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

    use RestControllerTrait, ResponceTrait;

    const MODEL = 'App\VendorProduct';
    /**
     * @var Contact
     */
    private $Product;
    /**
     * @var string
     */
    protected $method;
    /**
     * @var
     */
    protected $validationRules;

    public function __construct(Request $request, VendorProduct $vendorProduct, Product $product, Offer $offer, User $user)
    {

        parent::__construct();

        $this->vendorProduct = $vendorProduct;
        $this->product = $product;
        $this->offer = $offer;
        $this->user = $user;
        $this->method = $request->method();
        $this->validationRules = $this->vendorProduct->rules($this->method);
         $this->middleware('auth');
    }


    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //    public function __construct()
    //    {
    //        $this->middleware('guest')->except('logout');
    //    }

    public function productdeatils(Request $request, $slug)
    {
        $zone_id = $request->session()->get('zone_id');
        $vendor_zone_id = $zone_id;
        $match_in_zone = true;
        $user_id = 277;
        if (!$zone_id) {
            $zonedata = $this->getZoneData($request->lat, $request->lng);
            $vendor_zone_id = $zone_id = $zonedata['zone_id'];

            //return $zone_id;
            $zone_name = $zonedata['zone_name'];
            $match_in_zone = $zonedata['match_in_zone'];
        }
        $id = Helper::get_product_id($slug);

        $Product = $this->product->with(['MeasurementClass','MeasurementClassTranslation', 'images', 'cart' => function ($q) use ($zone_id) {
            $q->where(['zone_id' => $zone_id]);
        },'wishList'=>function($q) use ($user_id) {
            $q->where(['user_id'=> $user_id]);
        }])->findOrFail($id);

        $user_id = User::where('zone_id', $zone_id)->get()->toArray();

       // $Product->related_products= Helper::relatedProducts($Product->related_products,$user_id, $match_in_zone);
        $Product->related_product_arr = Helper::relatedProductsWeb($Product->related_products,$user_id, $match_in_zone);

        //$Product->match_in_zone = $match_in_zone;

        $products = [];
        $products['product_id'] = $Product->id;
        $products['offer_id'] = '0';
        $products['per_order'] = $Product->per_order;
        $products['offer'] = null;
        $products['product'] = $Product;
        $products['user_wishlist'] = $Product->wishList;
        $qty = 0;

//        print_r($user_id);
        foreach ($user_id as $udk => $udv) {
            $qtyData = $this->vendorProduct->where('user_id', $udv['id'])->where('product_id', $Product->id)->first();
            if (!empty($qtyData)) {
                $qty = $qty + $qtyData['qty'];
            }
        }
        $products['qty'] = $qty;
        $products['price'] = $Product->price;
        $products['offer_price'] = $Product->price;
        $products['match_in_zone'] = $Product->match_in_zone;

        $zone_price = DB::table('zone_price')->where('zone_id', $zone_id)->where('product_id', $Product->id)->first();
        if (!empty($zone_price)) {
            //$zone_price->toArray();
            $products['price'] = $zone_price->price;
            if (!empty($zone_price->offer_id)) {
                $products['offer'] = $offer_data = $this->offer->where('id', $zone_price->offer_id)->first();
                if ($offer_data->offer_type == 'amount') {
                    $products['offer_price'] = $offer_data->offer_value;
                } else if ($offer_data->offer_type == 'percentages') {
                    $products['offer_price'] = $zone_price->price - (($zone_price->price * $offer_data->offer_value) / 100);
                }
            }
        }
        return view('pages.products.productdeatils', compact('products'));
    }

    public function productlisting(Request $request, $slug)
    {

        $zone_id = $request->session()->get('zone_id');
        $vendor_zone_id = $zone_id;
        $user_id = Auth::user()->id;

        if (!$zone_id) {
            $zonedata = $this->getZoneData($request->lat, $request->lng);
            $vendor_zone_id = $zone_id = $zonedata['zone_id'];

            //return $zone_id;
            $zone_name = $zonedata['zone_name'];
            $match_in_zone = $zonedata['match_in_zone'];
        }
        $products_collection = array();
        $user = User::select('*');
        $user->whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor']);
        $user = $user->get()->toArray();
        $product_data = [];
        foreach ($user as $kk => $vv) {
            $product_data[$vv['id']] = $this->vendorProduct->where('user_id', $vv['id'])->where('status', '1')->get()->toArray();
        }

        $product_id_array = [];
        $products = $product = [];
        foreach ($product_data as $kp => $vp) {
            foreach ($vp as $k1 => $v1) {
                $product_id_array[$kp . '_' . $k1] = $v1['product_id'];
                $vendorProductdata[$v1['product_id']][$kp] = ['user_id' => $v1['user_id'], 'qty' => $v1['qty']];
            }
        }
        $product_list = $this->product->whereNull('deleted_at')
            ->with(['MeasurementClass', 'image', 'cart','wishList'=>function($q) use ($user_id) {
                $q->where(['user_id'=>$user_id ]);
            }])->get()->toArray();

        if (!empty($product_list)) {
            foreach ($product_list as $key => $value) {
                if (in_array($value['id'], $product_id_array)) {
                    $product[$key]['product_id'] = $value['id'];
                    $product[$key]['slug'] = $value['slug'];
                    $product[$key]['offer_id'] = '0';
                    $product[$key]['is_offer'] = false;
                    $product[$key]['per_order'] = $value['per_order'];
                    $product[$key]['offer'] = null;
                    $product[$key]['qty'] = 0;
                    $product[$key]['product'] = $value;
                    $product[$key]['price'] = $product[$key]['product']['price'];
                    $product[$key]['offer_price'] = $product[$key]['product']['price'];
                    $product[$key]['cart'] = $product[$key]['product']['cart'];
                    $product[$key]['wish_list'] = $product[$key]['product']['wish_list'];
                    unset($product[$key]['product']['cart']);
                    unset($product[$key]['product']['wish_list']);
                    $qty = 0;
                    foreach ($vendorProductdata[$value['id']] as $kq => $vq) {
                        $qty = $qty + $vq['qty'];
                    }
                    $product[$key]['qty'] = $qty;
                    $productData = DB::table('zone_price')->where('zone_id', $zone_id)->where('product_id', $value['id'])->first();
                    if (!empty($productData)) {
                        $product[$key]['product']['price_old'] = $product[$key]['product']['price'];
                        $product[$key]['product']['price'] = $productData->price;
                        $product[$key]['offer_price'] = $product[$key]['product']['price'];
                        $product[$key]['price'] = $product[$key]['product']['price'];
                        if (!empty($productData->offer_id)) {
                            $product[$key]['offer_id'] = $productData->offer_id;
                            $product[$key]['is_offer'] = true;
                            $product[$key]['offer'] = $this->offer->where('id', $productData->offer_id)->first();
                            if ($product[$key]['offer']['offer_type'] == 'amount') {
                                $product[$key]['offer_price'] = $product[$key]['offer']['offer_value'];
                            } elseif ($product[$key]['offer']['offer_type'] == 'percentages') {
                                $product[$key]['offer_price'] = $product[$key]['product']['price'] - (($product[$key]['offer']['offer_value'] * $product[$key]['product']['price']) / 100);
                            }
                        }
                    }
                    array_push($products_collection,$product[$key]);
                }

            }
        }

        if ($request->has('is_favourite') && $request->is_favourite == 1) {
            if (!empty($products)) {
                $products->has('wishList');
            }
        }

        if ($slug && !empty($slug)) {
            $category = Helper::get_category_id($slug);
            if(isset($category['category_id'])) {
                // $category = explode(',', $request->category);
                //echo "<pre>";print_r($product); exit;
                $category_product = [];
                if (!empty($products_collection)) {
                    foreach ($products_collection as $k2 => $v2) {

                        if (in_array($category['category_id'], $v2['product']['category_id'])) {
                            $category_product[$k2] = $v2;
                        }

                    }
                    unset($products_collection);
                    $products_collection = $category_product;
                }
            }
        }

        if ($request->filled('search') && !empty($request->search)) {
            $search = $request->search;
            if (!empty($products_collection)) {
                // $product->where('name', 'like', '%' . $search . '%')->orWhere('keywords', 'like', '%' . $search . '%');
                foreach ($products_collection as $k2 => $v2) {
                    // echo $v2['product']['name'].' and '.$v2['product']['keywords'];die;
                    if ((strpos($v2['product']['name'], $search) !== false) || (strpos($v2['product']['keywords'], $search) !== false)) {
                        $search_product[$k2] = $v2;
                    }
                }
                unset($products_collection);
                $products_collection = $search_product;
            }
        }


        $products = $this->paginate($products_collection);

       // return response()->json($products, 200);

        return view('pages.products.productlisting', ['products' => $products]);

    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $options = ['path' => url('list')];
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    public function getZoneData($lat, $lng)
    {
        $zone_id = '';
        $zoneArray = [];
        $zArray = [];
        $fArray = [];
        $finalArray = [];

        $zonedata = DB::table('zones')->select('id', DB::raw("ST_AsGeoJSON(point) as json"))->where('deleted_at', null)->where('status', '=', '1')->get();

        $json_arr = json_decode($zonedata, true);
        foreach ($json_arr as $zvalue) {
            $zone_id = $zvalue['id'];
            $json = json_decode($zvalue['json']);
            $coordinates = $json->coordinates;
            $new_coordinates = $coordinates[0];
            $lat_array = array();
            $lng_array = array();
            foreach ($new_coordinates as $new_coordinates_value) {
                $lat_array[] = $new_coordinates_value[0];
                $lng_array[] = $new_coordinates_value[1];


            }

            $is_exist = $this->isPointInPolygon($lat, $lng, $lat_array, $lng_array);

            if ($is_exist) {
                $zData = ZoneTranslation::where('zone_id', $zone_id)->where('locale', App::getLocale())->first();
                $data['match_in_zone'] = true;
                $data['zone_id'] = $zone_id;
                $data['zone_name'] = $zData->name;
                return $data;
            }

        }
        $zone = Zone::where('status', '=', '1')->where('is_default', '=', 1)->withoutGlobalScope(StatusScope::class)->first();
        $zone_id_default = $zone->id;
        $zData = ZoneTranslation::where('zone_id', $zone_id_default)->where('locale', App::getLocale())->first();
        $data['match_in_zone'] = false;
        $data['zone_id'] = $zone_id_default;
        $data['zone_name'] = $zData->name;
        return $data;


    }

    public function isPointInPolygon($latitude, $longitude, $latitude_array, $longitude_array)
    {
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


}
