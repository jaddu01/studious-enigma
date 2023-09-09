<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 21/7/17
 * Time: 10:29 AM
 */

namespace App\Helpers;

use App;
use DB;
use App\User;
use App\Cart;
use App\CategoryTranslation as Category;
use App\DeliveryTime;
use App\PermissionAccess;
use App\CountryPhoneCode;
// use App\RecipeTranslation as Recipe ;
// use App\RecipeCategoryTranslation as RecipeCategory ;
use App\ProductOrder;
use App\ProductOrderItem;
use App\Product;
use App\ProductTranslation;
use App\Offer;
use App\VendorProduct;
use App\Setting;
use App\AppSetting;
use App\SiteSetting;
use App\SocialMedia;
use App\PaymentMode;
use App\Zone;
use App\SlotTime;
use App\Notification;
use App\UserWallet;
use App\FirstOrder;
use App\Medias;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

//use resource
use App\Http\Resources\VendorProductResource;
use App\ZoneTranslation;

class Helper
{

	public static $gender = ['male' => 'Male', 'female' => 'Female'];
	public static $membership_durations = ['month' => 'month(s)', 'year' => 'year(s)'];
	public static $user_type = ['vendor' => 'Store', 'driver' => 'Driver', 'shoper' => 'shopper', 'subadmin' => 'Sub-admin'];

	public static $status = ['1' => 'Active', '0' => 'Inactive'];
	public static $offer_type = ['percentages' => 'Percentages', 'amount' => 'Amount'];
	public static $maintenance = ['true' => 'True', 'false' => 'False'];
	public static $debug = ['true' => 'True', 'false' => 'False'];
	public static $env = ['production' => 'Production', 'local' => 'Local'];
	// public  static $locale =['en'=>'English','in'=>'Indonesian'];
	public static $is_status = ['y' => 'Yes', 'n' => 'No'];
	public static $assigned_status = ['A' => 'Assigned', 'R' => 'Removed', 'U' => 'Updated'];
	public static $permission_modals = ['users' => 'users', 'post' => 'post'];
	public static $order_status = ['N' => 'New', 'CF' => 'Confirmed', 'O' => 'Collected', 'S' => 'On the way', 'A' => 'At Doorstep', 'D' => 'Delivered', 'R' => 'Returned', 'C' => 'Canceled', 'UP' => 'Updated'];
	public static $order_status_new = ['N' => 'New', 'E' => 'En Route', 'D' => 'Delivered', 'C' => 'Cancelled By Customer'];
	public static $new_order_status = ['Accepted' => 'Accepted', 'En Route' => 'En Route', 'Returned' => 'Returned', 'Cancelled' => 'Cancelled', 'Delivered' => 'Delivered'];
	public static $product_status = ['N' => 'New', 'O' => 'Collected', 'U' => 'Unavailable', '' => ''];
	public static $transaction_status = ['0' => 'Pending', '1' => 'Paid Out', '2' => 'Disputed', '3' => 'Failed'];

	public static function userShortName($name)
	{
		$firstLetter = substr($name, 0, 1);
		$letterAfterSpace = '';
		$secondPos = strpos($name, ' ');
		if ($secondPos) {
			$letterAfterSpace = substr($name, $secondPos + 1, 1);
		}
		return $firstLetter . $letterAfterSpace;
	}

	public $categories = array();

	public static function cat_list($collection = array(), $p_cid = 0, $space = '', $id = array(0))
	{

		$q = collect($collection)->where('parent_id', $p_cid);

		if ($p_cid == 0) {
			$space = '';
		} else {
			$space .= "-";
		}
		if (count($q) > 0) {
			foreach ($q as $rec) {

				echo '<option value="' . $rec->id . '" ' . (in_array($rec->id, count($id) > 0 ? $id : array(0)) ? "selected" : "") . '>' . $space . $rec->name . '</option>';

				self::cat_list($collection, $rec->id, $space, $id);
			}
		}
	}


	public static function categoryArray($collection = array(), $p_cid = 0)
	{
		$category_array = [];
		$q = collect($collection)->where('parent_id', $p_cid);
		if (count($q) > 0) {
			foreach ($q as $key => $value) {
				$category_array[$key] = ['id' => $value->id, 'name' => $value->name, 'slug' => $value->slug, 'sub_category' => []];
				$q1 = collect($collection)->where('parent_id', $value->id);
				if (count($q1) > 0) {
					foreach ($q1 as $key1 => $value1) {
						$category_array[$key]['sub_category'][$key1] = ['id' => $value1->id, 'name' => $value1->name, 'slug' => $value1->slug, 'sub_category' => []];
						$q2 = collect($collection)->where('parent_id', $value1->id);
						if (count($q2) > 0) {
							foreach ($q2 as $key2 => $value2) {
								$category_array[$key]['sub_category'][$key1]['sub_category'][$key2] = ['id' => $value2->id, 'name' => $value2->name, 'slug' => $value2->slug, 'sub_category' => []];
							}
						}
					}
				}
			}
		}
		return $category_array;
	}

	public $Category = array();


	public static function Category_arr()
	{
		$q = Category::join('categories', 'categories.id', '=', 'category_translations.category_id')->select('categories.id', 'image', 'name', 'category_translations.slug')->where(['locale' => 'en'])->where(['categories.parent_id' => '0'])->where(['categories.status' => '1'])->where(['categories.is_show' => '1'])->whereNull('categories.deleted_at')->orderBy('sort_no', 'ASC')->get();
		return $q;
	}

	public static function Category_list_f5()
	{
		$q = Category::join('categories', 'categories.id', '=', 'category_translations.category_id')->select('categories.id', 'image', 'name', 'category_translations.slug')->where(['locale' => 'en'])->where(['categories.parent_id' => '0'])->where(['categories.status' => '1'])->whereNull('categories.deleted_at')->orderBy('sort_no', 'ASC')->take(7)->get();
		return $q;
	}
	public static function Category_list_a5()
	{
		$q = Category::join('categories', 'categories.id', '=', 'category_translations.category_id')->select('categories.id', 'image', 'name', 'category_translations.slug')->where(['locale' => 'en'])->where(['categories.parent_id' => '0'])->where(['categories.status' => '1'])->whereNull('categories.deleted_at')->orderBy('sort_no', 'ASC')->skip(7)->take(10)->get();
		return $q;
	}

	public static function SubCategory_arr($id)
	{
		$q = Category::join('categories', 'categories.id', '=', 'category_translations.category_id')->select('categories.id', 'image', 'name', 'category_translations.slug')->where(['locale' => 'en'])->where(['categories.parent_id' => $id])->where(['categories.status' => '1'])->where(['categories.is_show' => '1'])->whereNull('categories.deleted_at')->orderBy('sort_no', 'ASC')->get();
		return $q;
	}

	// public static function RecipeCategory_arr() {
	// 	$q = RecipeCategory::join('recipe_categories','recipe_categories.id','=','recipe_category_translations.recipe_category_id')->select('recipe_categories.id','image','name','recipe_category_translations.slug')->where(['locale' => 'en'])->get();
	// 	return $q;
	// }

	public static function get_category_id($slug)
	{
		//DB::enableQueryLog();


		$q = Category::join('categories', 'categories.id', '=', 'category_translations.category_id')->select('categories.id', 'image', 'name', 'category_translations.slug')->where(['slug' => $slug, 'deleted_at' => NULL])->first();
		if (!empty($q)) {
			return $q->id;
		} else {
			return 0;
		}
		//print_r(DB::getQueryLog());die();

	}

	public static function getParentCategories($slug)
	{
		$slug_array = [];
		$q = Category::join('categories', 'categories.id', '=', 'category_translations.category_id')->select('categories.parent_id')->where(['slug' => $slug])->first();
		if (!empty($q)) {
			if ($q->parent_id != 0) {
				$p1 = Category::join('categories', 'categories.id', '=', 'category_translations.category_id')->select('categories.parent_id', 'category_translations.slug')->where(['categories.id' => $q->parent_id])->first();
				if (!empty($p1)) {
					array_push($slug_array, $p1->slug);
					if ($p1->parent_id != 0) {
						$p2 = Category::join('categories', 'categories.id', '=', 'category_translations.category_id')->select('categories.parent_id', 'category_translations.slug')->where(['categories.id' => $p1->parent_id])->first();
						if (!empty($p2)) {
							array_push($slug_array, $p2->slug);
						}
					}
				}
			}/* else {
				array_push($slug_array,$slug);
			}*/
			array_push($slug_array, $slug);
		}
		return $slug_array;
	}
	// public static function get_recipe_category_id($slug){
	// 	$q = RecipeCategory::join('recipe_categories','recipe_categories.id','=','recipe_category_translations.recipe_category_id')->select('recipe_categories.id','name','image','recipe_category_translations.slug')->where(['slug' => $slug])->first();
	// 	return $q->id;
	// }


	public static function get_product_id($slug)
	{
		$q = ProductTranslation::join('products', 'products.id', '=', 'product_translations.product_id')->select('products.id', 'name', 'slug')->where(['slug' => $slug])->first();
		return $q->id;
	}
	// public static function get_recipe_id($slug){
	//     $q = Recipe::join('recipes','recipes.id','=','recipe_translations.recipe_id')->select('recipes.id','name','recipe_translations.slug')->where(['slug' => $slug])->first();
	// 	return $q->id;
	// }

	public static function Zone_list()
	{
		$zone = DB::table('zones')
			->join('zone_translations', 'zones.id', '=', 'zone_translations.zone_id')
			->select('zones.id', 'zone_translations.name')
			->groupBy('zones.id')
			->get();

		return $zone;
	}

	public static function delete_cat($collection = array(), $p_cid = 0, $space = '')
	{
		$result = array();
		$q = $collection->where('parent_id', $p_cid);

		if (count($q) > 0) {
			foreach ($q as $rec) {
				$result[] = $rec->id;
				self::cat_list($collection, $rec->id, $space);
			}
		}
		$result[] = $p_cid;
		return $result;
	}

	public static function paymentmodebyid($id = 0)
	{
		$result = PaymentMode::find($id);

		return (!empty($result->name)) ? $result->name : "";
	}


	public static function hasImage($imageName)
	{
		return (Storage::disk('upload')->exists($imageName) ? asset('storage/app/public/upload') . "/" . $imageName : null);
	}

	public static function imageNotFound($imageName)
	{
		return (Storage::disk('upload')->exists($imageName) ? asset('storage/app/public/upload') . "/" . $imageName : asset('storage/app/public/upload') . "/404.jpeg");
	}



	public static function hasUserPermission($modelId)
	{

		$user = Auth::guard('admin')->user();
		$PermissionAccess = PermissionAccess::where(['access_level_id' => $user->access_user_id, 'permission_modal_id' => $modelId, 'type' => 'Y'])->first();

		if ($PermissionAccess) {
			return false;
		}
		return true;
	}

	public static function sendOtp($phone_number, $otp)
	{
		try {
			// $client = new Client();
			$authkey = env('AUTHKEY');
			$senderid = env('SENDERID');
			$hash = env('SMSHASH');
			// //$message="Your OTP for Darbaar Mart is ".$otp;

			$message = urlencode("Dear Customer, use OTP ($otp) to log in to your DARBAAR MART account and get your grocery essentials safely delivered at your home.\n\r \n\rStay Home, Stay Safe.\n\rTeam Darbaar Mart, Beawar e8Pwa8UjCOy");
			$url = "http://control.yourbulksms.com/api/sendhttp.php?authkey=36346e6768313136333766&mobiles=".$phone_number."&sender=DMAART&route=2&country=91&DLT_TE_ID=1207162028126071690&message=".$message;
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			curl_close($ch);
			// $response = $client->request('GET', "http://control.yourbulksms.com/api/sendhttp.php?authkey=" . $authkey . "&mobiles=" . $phone_number . "&message=" . $message . "&sender=" . $senderid . "&route=6&country=91&DLT_TE_ID=1207162028126071690&response=json");
			// $statusCode = $response->getStatusCode();
			// dd($response);
			return true;
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			return false;
		}
		// try {
		// 	return $opt = rand(100, 999);
		// } catch (\Exception $e) {
		// 	throw $e;
		// }

	}

	public static function getcountryPhoneCode()
	{
		try {
			$countryPhoneCode  = CountryPhoneCode::orderBy('phonecode')->pluck('phonecode', 'phonecode');
			return $countryPhoneCode;
		} catch (\Exception $e) {
			throw $e;
		}
	}

	public static function fileUpload($files, $is_array = false)
	{
		if ($is_array) {
			foreach ($files as $file) {
				$imageName = time() . rand(1, 99) . '.' . $file->getClientOriginalExtension();

				$file->storeAs('public/upload', $imageName);
				Image::make($file)->resize(300, 200)->save(storage_path('app/public/upload/Thumbnail/' . $imageName));

				$responce[] = ['name' => $imageName];
			}
			return $responce;
		} else {
			$imageName = time() . rand(1, 99) . '.' . $files->getClientOriginalExtension();

			$files->storeAs('public/upload', $imageName);
			Image::make($files)->resize(300, 200)->save(storage_path('app/public/upload/Thumbnail/' . $imageName));
			return $imageName;
		}
	}

	public static function relatedProducts($related_products = array(), $user_id, $match_in_zone = null, $zone_id)
	{
		try {
			if (is_array($related_products)) {
				$product = Product::whereIn('id', $related_products)->with(['MeasurementClass', 'image'])->get()->pluck('id')->toArray();
				$data = [];
				if (Auth::guard('api')->user()) {
					$related_products = VendorProduct::With([
						'product', 'product.MeasurementClass', 'product.images',
						'cart' => function ($q) {
							$q->where(
								[
									'user_id' => Auth::guard('api')->user()->id,
									'zone_id' => Auth::guard('api')->user()->zone_id
								]
							);
						}
					])->whereIn('product_id', $product)->limit(20)->get();
				} else {
					$related_products = VendorProduct::With(['product', 'product.MeasurementClass', 'product.images'])->whereIn('product_id', $product)->limit(20)->get();
				}
				return VendorProductResource::collection($related_products);

				//dd($related_products);

				// if (!empty($product)) {
				// 	foreach ($product as $rec) {
				// 		$is_related = VendorProduct::With(['product','product.MeasurementClass','product.images','User','cart' => function ($q) {
				// 		$q->where(['user_id' => Auth::guard('api')->user()->id, 'zone_id' => Auth::guard('api')->user()->zone_id]);
				// 		}])->whereIn('user_id',$useridarray)->where('product_id',$rec['id'])->first();
				// 			if(!empty($is_related)){
				// 	   $image = isset($rec['image']['name']) ? $rec['image']['name'] : Helper::imageNotFound(null);
				// 		unset($is_related['product']['offer'],$is_related['product']['image'], $is_related['product']['related_products']/*,$rec['product']['category_id']*/);
				// 		$is_related['product']['image'] = $image;
				// 		$is_related['product']['match_in_zone'] = $match_in_zone;
				// 		  $data[] = $is_related;
				// 	   }

				// 	}
				// }
				//   return $data;
			}
			// return [];
		} catch (Exception $e) {
			return $e;
		}
	}

	public static function similarProducts($category_id, $zone_id)
	{
		try {
			$vendorProduct = VendorProduct::with([
				'product.MeasurementClass', 'product.image',
				'cart' => function ($q) use ($zone_id) {
					$q->where(['zone_id' => $zone_id]);
				}
			])
				->whereHas('product', function ($q) {
					$q->where('status', '1');
				})->whereHas('product.category', function ($q) use ($category_id) {
					$q->whereRaw('FIND_IN_SET(' . $category_id . ', category_id) ');
				})->paginate(20);

			return VendorProductResource::collection($vendorProduct);
		} catch (Exception $e) {
			return $e;
		}
	}
	public static function checkProducInCart($user_id, $zone_id = null)
	{
		try {
			$cart = Cart::select('*')->where('user_id', '=', $user_id);
			if ($zone_id != null) {
				$cart->where(['zone_id' => $zone_id]);
			}

			$cart = $cart->has('Product')->get()->toArray();
			return $cart;
		} catch (\Exception $e) {
			return [];
		}
	}
	// public static function outOfStock($productId,$zone_id) {
	// 	try {

	// 	$product = VendorProduct::select('qty')->where('id', '=', $productId)->firstOrFail();
	// 	return $product['qty'];
	// 	} catch (\Exception $e) {
	// 	}
	// 	return 0;
	// }

	public static function outOfStock($Id, $zone_id)
	{
		try {
			// if(empty($zone_id)){ $zone_id = Auth::guard('api')->user()->zone_id; }
			//    $users = User::select('*')->where('zone_id','LIKE', '%'.$zone_id.'%')->where(['user_type' => 'vendor'])->where(['access_user_id' => '7'])->whereNull('deleted_at')->get()->toArray();
			$qty = 0;
			//    foreach($users as $key=>$value){
			$product = VendorProduct::select('*')->where('id', '=', $Id)->first();
			if (!empty($product)) {
				if ($product['qty'] > 0) {
					$qty = $product->qty;
				}
			}
			//	    }
			return $qty;
		} catch (\Exception $e) {
			return $e;
		}
		return 0;
	}

	public static function cartTotal($user_id, $zone_id)
	{
		$AppSetting = AppSetting::select('mim_amount_for_order', 'mim_amount_for_free_delivery', 'mim_amount_for_free_delivery_prime')->firstOrfail();
		$cartRec = Cart::whereHas('vendorProduct', function ($q) {
			$q->where('status', '1');
		})
			->with(['vendorProduct.Product', 'zone'])
			->where(['user_id' => $user_id, 'zone_id' => $zone_id])->get();

		/**First ordrer product free **/
		$is_free_product = false;
		$flag = 0;
		$order_count = ProductOrder::where('user_id', $user_id)->count();

		if ($order_count == 0) {
			//$zone_id = implode(',', $zone_id);
			$user  = User::select('*')->whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id)')->where(['user_type' => 'vendor'])->get();
			$useridarray = $freeproduct = [];
			foreach ($user as $userkey => $uservalue) {
				$useridarray[] = $uservalue->id;
			}
			$first_order = FirstOrder::first();
			if (!empty($first_order->free_product)) {
				$free_product = $first_order->free_product;
				foreach ($free_product as $kk => $vv) {
					$dd = VendorProduct::with(['Product'])->where('product_id', $vv)->whereIn('user_id', $useridarray)->first();
					if (!empty($dd)) {
						$freeproduct[$kk] = $dd->product_id;
					}
				}
				$first_order->free_product_data = $freeproduct;
			}
			foreach ($cartRec as $dk => $dv) {
				foreach ($freeproduct as $fk => $fv) {
					if ($fv == $dv['vendorProduct']['product_id']) {
						$is_free_product = true;
					}
				}
			}
		} else {
			$flag = 1;
		}
		/**End First ordrer product free **/
		$offer_price_total = 0;
		$total = 0;
		$dc = 0;
		$result = [];
		foreach ($cartRec as $Rec) {
			if (!empty($Rec['vendorProduct'])) {
				if ((isset($freeproduct)) && ($flag == 0) && (in_array($Rec['vendorProduct']['product']['id'], $freeproduct))) {
					if ($Rec['qty'] == 1) {
						$Rec['vendorProduct']['offer_price'] = $Rec['vendorProduct']['price'] = 0;
					} else {
						$Rec['qty'] = $Rec['qty'] - 1;
					}
					$Rec['vendorProduct']['is_offer'] = false;
					$Rec['vendorProduct']['offer'] = [];
					$flag = 1;
				} else {
					$Rec['vendorProduct']['offer_price'] = $Rec['vendorProduct']['price'];
					if (!empty($Rec['vendorProduct']['offer_id'])) {
						$offer = Offer::where('id', $Rec['vendorProduct']['offer_id'])->where('from_time', '<=', date('Y-m-d'))->where('to_time', '>=', date('Y-m-d'))->first();
						if (!empty($offer)) {
							$offer->toArray();
							if ($offer['offer_type'] == 'amount') {
								$Rec['vendorProduct']['offer_price'] = $Rec['vendorProduct']['offer_price'] - $offer['offer_value'];
							} else if ($offer['offer_type'] == 'percentages') {
								$Rec['vendorProduct']['offer_price'] = $Rec['vendorProduct']['offer_price'] - (($Rec['vendorProduct']['offer_price'] * $offer['offer_value']) / 100);
							}
							$Rec['vendorProduct']['offer_price'] = number_format($Rec['vendorProduct']['offer_price'], 2, '.', '');
							$Rec['vendorProduct']['is_offer'] = true;
							$Rec['vendorProduct']['offer'] = $offer;
						}
					} else {
						$Rec['vendorProduct']['is_offer'] = false;
						$Rec['vendorProduct']['offer'] = [];
					}
				}
			}
			//echo "<pre>"; print_r($Rec); die;
			$total = $total + ($Rec['vendorProduct']['price'] * $Rec['qty']);
			// code change by Abhishek Bhatt for check the minimum amount for free delivery zone wise //
			//$dc = $Rec['Zone']['delivery_charges'];
			if(!empty($Rec['vendorProduct']['offer_price'])){
				//$Rec['vendorProduct']['offer_price']
				$offer_price_total = $offer_price_total + ($Rec['vendorProduct']['offer_price'] * $Rec['qty']);
			}else{
				$offer_price_total = $offer_price_total + ($Rec['vendorProduct']['best_price'] * $Rec['qty']);
			}
			$result[] = $Rec;
		}
		$total_saving = $total - $offer_price_total;
		$mim_amount_for_free_delivery = 0;

		$dc = $Rec['Zone']['delivery_charges'];
		//echo "<pre>"; print_r(Auth::guard('api')->user()); die;
		if (!empty(Auth::guard('api')->user()->membership) && (Auth::guard('api')->user()->membership_to >= date('Y-m-d H:i:s'))) {
			if (floatval($offer_price_total) >= floatval($AppSetting->mim_amount_for_free_delivery_prime)) {
				$dc = 0;
			}
			$mim_amount_for_free_delivery = $AppSetting->mim_amount_for_free_delivery_prime;
		} else {
			// $mim_amount_for_free_delivery = (isset($Rec['Zone']['minimum_order_amount']) && $Rec['Zone']['minimum_order_amount'] > 0) ? $Rec['Zone']['minimum_order_amount'] : $AppSetting->mim_amount_for_free_delivery;
			$mim_amount_for_free_delivery = $AppSetting->mim_amount_for_free_delivery;
			if (floatval($offer_price_total) >= floatval($mim_amount_for_free_delivery)) {
				$dc = 0;
			}

			// dd(floatval($offer_price_total), floatval($mim_amount_for_free_delivery), $dc);
			//$mim_amount_for_free_delivery = $AppSetting->mim_amount_for_free_delivery;
		}
		// dd($dc);
		return $result = [
			// 'cartRec' => $parr,
			'cart_list' => $result,
			'count' => count($result),
			'min_amount_for_order' => $AppSetting->mim_amount_for_order,
			'min_amount_for_free_delivery' => $mim_amount_for_free_delivery,
			'delivery_charge' => $dc,
			'currency' => 'Rs',
			'is_free_product' => $is_free_product,
			'offer_price_total' => number_format($offer_price_total, 2, '.', ''),
			'total' => number_format($total, 2, '.', ''),
			'total_saving' => number_format($total_saving, 2, '.', ''),
			'total_saving_percentage' => number_format(($total > 0 ? (($total_saving * 100) / $total) : 0), 2, '.', ''),

		];
	}

	public static function orderCode($delivery_date, $zone_id, $time, $curr = null)
	{
		// $date = Carbon::createFromFormat('Y-m-d', $delivery_date)->format('ymd');
		$date = Carbon::parse($delivery_date)->format('ymd');
		$zone_name = Zone::findOrFail($zone_id)->name;
		if (empty($curr) && !empty($time)) {
			$time = SlotTime::findOrFail($time);
			// $time->from_time;
			$hourTime = date("H:i", strtotime($time->from_time));
		} else {
			$hourTime = date("H:i", strtotime($curr));
		}
		//$newtime = Carbon::createFromFormat('g:ia', $time->from_time)->format('gi');
		$htime = str_replace(':', '', $hourTime);
		$num_padded = sprintf("%04d", $htime);
		$order_count_dy_date = ProductOrder::where(['delivery_date' => $delivery_date])->count();
		$three_digit_format = str_pad($order_count_dy_date + 1, 3, '0', STR_PAD_LEFT);
		// return $date .$num_padded.$three_digit_format . strtoupper(substr($zone_name, 0, 4));
		//new
		//if($order_count_dy_date > 0){
		$order_desc = ProductOrder::orderBy('id', 'desc')->first();
		$numg = $order_desc->id ?? '01';
		// }else{
		// 	$numg = '01';
		// }
		$order = 'D_' . substr($zone_name, 0, 4) . $numg;
		return $order;
	}

	public static function checkAvailabilityInTimeSlot($delivery_time_id, $delivery_date)
	{
		$deliveryTime = SlotTime::findOrFail($delivery_time_id);
		//$deliveryTime = DeliveryTime::findOrFail($delivery_time_id);
		return $deliveryTime->total_order - (ProductOrder::where(['delivery_time_id' => $delivery_time_id, 'delivery_date' => $delivery_date])->count());
	}
	public static function unavailableProducts()
	{

		try {

			$product = VendorProduct::select('*')->where('qty', '=', 0)->count();

			return $product;
		} catch (\Exception $e) {
		}
		return 0;
	}

	/*send push notification*/

	public static function sendNotificationCustomer($device_token, $type, $product_type, $title, $body, $device_type)
	{
		//return 'hi';
		$push_message = "Test message";
		$fields = [];

		if ($device_token && !empty($device_token)) {

			//$url = env('FCM_URL');
			$url = 'https://fcm.googleapis.com/fcm/send';
			$server_key = 'AIzaSyDh6vQ7cAqaMRP7r-tzrDZwi9w2EGDcGE0';
			//$server_key = env('FCM_API_KEY');
			if ($device_type == 'I') {

				$fields = array(
					//'priority' => "high" ,
					'notification' => array("title" => $title, "body" => $body, "sound" => "mySound", 'badge' => count(1), 'vibrate' => 1),
					'data' => $dataArray,
				);
			} else if ($device_type == 'A') {
				$fields = array(
					//'priority' => "high" ,
					'data' => array(
						'type' => $type,
						'order_type' => $product_type,
						'title' => $title,
						'body' => $body
					),
				);
			}

			$fields['registration_ids'] = $device_token;
			//$fields = json_encode($fields);
			//echo '<pre>';print_r($fields);
			$headers = array(
				'Content-Type:application/json',
				'Authorization:key=' . $server_key
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

			$result = curl_exec($ch);
			echo "hello";
			echo '<pre>';
			print_r($result);
			die;
			if ($result === FALSE) {
				die('FCM Send Error:' . curl_error($ch));
			}

			curl_close($ch);
			return true;
		}
	}
	public static function sendNotification($user_id_array, $dataArray, $device_type)
	{
		//echo "Hi"; die;
		$fields = [];
		$register_id[] = $user_id_array;
		//echo "<pre>"; print_r($register_id); die;
		if (isset($register_id) && !empty($register_id)) {
			if (count($register_id) == 1) {
				$register_id = $register_id[0];
			}
			$url = env('FCM_URL');
			//$url = 'https://fcm.googleapis.com/fcm/send';
			//$server_key = 'AIzaSyDh6vQ7cAqaMRP7r-tzrDZwi9w2EGDcGE0';
			$server_key = env('FCM_API_KEY');
			if ($device_type == 'I') {

				$fields = array(
					//'priority' => "high" ,
					'notification' => array("title" => $dataArray['title'], "body" => $dataArray['body'], "sound" => "newOrderNotification.wav", 'badge' => count($dataArray), 'vibrate' => 1),
					'data' => $dataArray,
				);
			} else if ($device_type == 'A') {

				$fields = array(
					//'priority' => "high" ,
					'data' => $dataArray
				);
			}

			$fields['registration_ids'] = $register_id;
			//$fields = json_encode($fields);
			//echo '<pre>';print_r($fields);
			$headers = array(
				'Content-Type:application/json',
				'Authorization:key=' . $server_key
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

			$result = curl_exec($ch);
			//echo "hello"; echo '<pre>';print_r($result); print_r($fields[ 'registration_ids' ]); die;
			if ($result === FALSE) {
				die('FCM Send Error:' . curl_error($ch));
			}

			curl_close($ch);
			return true;
		}
	}


	public static function getDeliveryTimeById($delivery_time_id)
	{
		$deliveryTime = SlotTime::findOrFail($delivery_time_id);
		return $deliveryTime;
	}

	public static function getNotificationCount($url, $notify)
	{
		$count = 0;
		$message = '';
		$link = url('admin/notification/order');
		$notification = [];
		if ($notify == 'notification') {
			if ($url == 'address') {
				$message = 'Address update';
				$notification = Notification::selectRaw('*,id as id1')->whereIn('type', ['App\Notifications\AddressUpdate'])->whereNull('read_at')->get();
				$count = $notification->count('id');
				$link = url('admin/notification/address');
			}
			if ($url == 'unavailable') {
				$notification = Notification::selectRaw('*,id as id1')->whereIn('type', ['App\Notifications\ProductStatus'])->whereNull('read_at')->get();
				$count = $notification->count('id');
				$link = url('admin/notification/unavailable');
			}
			if ($url == 'shopper') {
				$notification = Notification::selectRaw('*,id as id1')->whereIn('type', ['App\Notifications\ProductUpdate', 'App\Notifications\ProductOutStockStatus', 'App\Notifications\NewProduct', 'App\Notifications\ManageProductUpdate', 'App\Notifications\ManageOutStock'])->whereNull('read_at')->get();
				$count = $notification->count('id');
				$link = url('admin/notification/shopper');
			}
			if ($url == 'order') {

				$notification = Notification::selectRaw('*,id as id1')->whereIn('type', ['App\Notifications\OrderStatus'])->whereNull('read_at')->get();
				$count = $notification->count('id');
				$link = url('admin/notification/order');
			}
		} else {
			$notification = Notification::selectRaw('*,id as id1')->whereNull('read_at')->get();
			$count = $notification->count('id');
			$link = url('admin/notification/unavailable');
			$uNotification = Notification::selectRaw('*,id as id1')->whereIn('type', ['App\Notifications\ProductStatus'])->whereNull('read_at')->get();
			$uCount = $uNotification->count('id');
			$ulink = url('admin/notification/unavailable');
		}
		$notifyArray = array('count' => $count, 'uCount' => $uCount, 'notification' => $notification, 'uNotification' => $uNotification, 'message' => $message, 'link' => $link, 'ulink' => $ulink);

		return $notifyArray;
	}


	public static function globalSetting()
	{

		$data  = SiteSetting::select('free_delivery_charge', 'phone', 'whats_up', 'currency', 'express_delivery_charges', 'express_delivery_time', 'standard_delivery_charges', 'standard_delivery_time', 'referred_by_amount', 'referral_amount', 'youtube')->firstOrFail();
		$Setting = Setting::firstOrFail();
		$SocialMedia = SocialMedia::firstOrFail();
		$AppSetting = AppSetting::select('mim_amount_for_order', 'mim_amount_for_free_delivery', 'ios_app_store', 'android_play_store', 'update_shopper_location', 'update_driver_location', 'update_shopper_app', 'update_driver_app')->firstOrFail();
		$prime_memebership_image = Medias::select('medias_translations.title', 'medias_translations.image')->leftJoin('medias_translations', 'medias.id', '=', 'medias_translations.medias_id')->where('medias.media_type', '=', 'prime_membership')->where('medias.status', '=', 1)->where('medias_translations.locale', '=', 'en')->firstOrfail();
		$data["min_price"] =  $AppSetting->mim_amount_for_order;
		$data["max_price"] =  $AppSetting->mim_amount_for_free_delivery;

		/*change min in seconds*/
		$data["update_shopper_location"] =  (int) filter_var($AppSetting->update_shopper_location, FILTER_SANITIZE_NUMBER_INT) * 60;
		$data["update_driver_location"] =  (int) filter_var($AppSetting->update_driver_location, FILTER_SANITIZE_NUMBER_INT) * 60;
		$data["update_shopper_app"] =  (int) filter_var($AppSetting->update_shopper_app, FILTER_SANITIZE_NUMBER_INT) * 60;
		$data["update_driver_app"] =  (int) filter_var($AppSetting->update_driver_app, FILTER_SANITIZE_NUMBER_INT) * 60;
		$data["android_play_store"] =  $AppSetting->android_play_store;
		$data["ios_play_store"] =  $AppSetting->ios_app_store;
		$data["mim_amount_for_order"] =  $AppSetting->mim_amount_for_order;
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

		$data["email"] =  $Setting->email;
		$data["phone"] =  $Setting->phone;
		$data["mobile"] =  $Setting->mobile;
		$data["address"] =  $Setting->address;
		$data["app_name"] =  $Setting->app_name;
		$data["app_url_android"] =  $Setting->app_url_android;
		$data["app_url_ios"] =  $Setting->app_url_ios;
		$data["youtube_page"] =  !empty($data->youtube) ? $data->youtube : "";
		$data["prime_memebership_image"] = $prime_memebership_image;
		return $data;
	}


	public static function getUpdatedWalletData($user_id)
	{
		$data = User::select('wallet_amount')->where('id', '=', $user_id)->firstOrFail();
		return $data;
	}


	public static function checkWallet($user_id, $amount)
	{
		$data = array();

		$customerWalletData = $this->getUpdatedWalletData($user_id);

		$wallet_amount = $customerWalletData->wallet_amount;

		$need_pay = $amount;
		if ($need_pay > 0) {
			if ($wallet_amount > 0) {
				if ($wallet_amount > $amount) {
					$need_pay = 0;
				} else {
					$need_pay -= $wallet_amount;
				}
			}
		}


		$output['wallet']		= $customerWalletData->wallet_amount;
		$output['need_pay']		= $need_pay;
		$output['entry_fee']	= $amount;
		if ($need_pay > 0) {
			$output['low_balance'] = true;
		} else {
			$output['low_balance'] = false;
		}
		return $output;
	}

	public static function getUpdatedCoinData($user_id)
	{
		$data = User::select('coin_amount')->where('id', '=', $user_id)->firstOrFail();
		return $data;
	}

	public static function checkCoin($user_id, $amount)
	{
		$data = array();

		$customerWalletData = $this->getUpdatedCoinData($user_id);

		$coin_amount = $customerWalletData->coin_amount;

		$need_pay = $amount;
		if ($need_pay > 0) {
			if ($coin_amount > 0) {
				if ($coin_amount > $amount) {
					$need_pay = 0;
				} else {
					$need_pay -= $coin_amount;
				}
			}
		}


		$output['wallet']		= $customerWalletData->coin_amount;
		$output['need_pay']		= $need_pay;
		$output['entry_fee']	= $amount;
		if ($need_pay > 0) {
			$output['low_balance'] = true;
		} else {
			$output['low_balance'] = false;
		}
		return $output;
	}


	public static function updateCustomerWallet($customer_id, $amount, $transaction_type, $type, $transaction_id, $description, $json_data = null, $order_id = null)
	{
		$user = User::findOrFail($customer_id);
		if (isset($user)) {
			if ($transaction_type == "CREDIT") {
				$wallet_amount = $user->wallet_amount;
				$wallet_amount += $amount;
				$user->wallet_amount = $wallet_amount;
				$user->save();
			} else if ($transaction_type == "DEBIT") {
				$wallet_amount = $user->wallet_amount;
				$wallet_amount -= $amount;
				$user->wallet_amount = $wallet_amount;
				$user->save();
			}
			if (!empty($order_id)) {
				$transaction_id = "DAR" . time() . $order_id;
			} else {
				$transaction_id = "DAR" . time() . $customer_id;
			}

			$wh 					= new UserWallet();
			$wh->user_id 		    = $customer_id;
			$wh->amount 			= $amount;
			$wh->transaction_type 	= $transaction_type;
			$wh->type 				= $type;
			$wh->transaction_id 	= $transaction_id;
			$wh->description 		= $description;
			$wh->data 				= $json_data;
			$wh->order_id 			= $order_id;
			$wh->wallet_type		= 'amount';

			$wh->save();

			return true;
		} else {
			return false;
		}
	}

	public static function updateCustomerCoins($customer_id, $amount, $transaction_type, $type, $transaction_id, $description, $json_data = null, $order_id = null)
	{
		$user = User::findOrFail($customer_id);
		if (isset($user)) {
			if ($transaction_type == "CREDIT") {
				$darbaar_coin_price = $user->coin_amount;
				$darbaar_coin_price += $amount;
				$user->coin_amount = $darbaar_coin_price;
				$user->save();
			} else if ($transaction_type == "DEBIT") {
				$darbaar_coin_price = $user->coin_amount;
				$darbaar_coin_price -= $amount;
				$user->coin_amount = $darbaar_coin_price;
				$user->save();
			}
			if (!empty($order_id)) {
				$transaction_id = "DAR" . time() . $order_id;
			} else {
				$transaction_id = "DAR" . time() . $customer_id;
			}

			$wh 					= new UserWallet();
			$wh->user_id 		    = $customer_id;
			$wh->amount 			= $amount;
			$wh->transaction_type 	= $transaction_type;
			$wh->type 				= $type;
			$wh->transaction_id 	= $transaction_id;
			$wh->description 		= $description;
			$wh->data 				= $json_data;
			$wh->order_id 			= $order_id;
			$wh->wallet_type		= 'coin';

			$wh->save();

			return true;
		} else {
			return false;
		}
	}

	public static function cronForUpdateMembership()
	{
		$date = date('Y-m-d');
		$users = User::where('user_type', '=', 'user')->where('membership_to', '>=', $date)->get();

		if (!empty($users)) {
			foreach ($users as $user) {
				$um = UserMembership::where('user_id', '=', $user->id)->first();
				echo $um['start_date'];
			}
		}
	}

	public static function checkIfValidReferralCode($code)
	{
		$result = User::where('referral_code', $code)->count();

		return $result;
	}
	public static function generateRandomString($length = 10)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	public static function checkFirstOrder($vendor_product_id, $zone_id, $user_id)
	{
		$is_offer = 1;

		$category_name = 'SUPER DUPER OFFER';
		//$category_name = 'BABY CARE';
		$category = Category::join('categories', 'categories.id', '=', 'category_translations.category_id')->where('category_translations.name', '=', $category_name)->where('categories.status', '=', '1')->first();
		if (isset($category) && !empty($category)) {
			$category_id = $category->category_id;
			$vendor_product = VendorProduct::with(['Product'])->where('id', '=', $vendor_product_id)->where('status', '=', '1')->firstOrFail();
			$product = Product::where('id', '=', $vendor_product->product_id)->firstOrFail();
			//echo $product->category_id.' == '. $category_id;
			if (in_array($category_id, $product->category_id)) {
				$product_order_items = ProductOrder::select(['product_orders.id', 'product_orders.order_code', 'product_orders.zone_id', 'product_order_items.vendor_product_id', 'product_orders.created_at as created_at'])
					->join('product_order_items', 'product_order_items.order_id', '=', 'product_orders.id')
					->join('vendor_products', 'vendor_products.id', '=', 'product_order_items.vendor_product_id')
					->join('products', 'products.id', '=', 'vendor_products.product_id')
					//->where('product_order_items.vendor_product_id','=',$vendor_product_id)
					->whereNotIn('product_orders.order_status', ['N', 'O', 'S', 'A', 'U', 'UP'])
					->where('product_orders.user_id', '=', $user_id)
					->where('product_orders.zone_id', '=', $zone_id)
					->whereIn('products.category_id', [$category_id])
					->get()->toArray();
				if (isset($product_order_items) && !empty($product_order_items)) {
					$is_offer = 0;
				}
			}
		}
		/*$superDealProducts = Helper::superDealProducts($zone_id);
		$product_order_items = ProductOrder::select(['product_orders.id','product_orders.order_code','product_orders.zone_id','product_order_items.vendor_product_id','product_orders.created_at as created_at'])
				->join('product_order_items','product_order_items.order_id','=','product_orders.id')
				->whereIn('product_order_items.vendor_product_id',[$superDealProducts])
				->whereNotIn('product_orders.order_status',['N','O','S','A','U','UP'])
				->where('product_orders.user_id','=',$user_id)
				->where('product_orders.zone_id','=',$zone_id)
				->get();
				echo '<pre>';
				print_r($product_order_items);
				echo '</pre>';
		if(isset($product_order_items) && !empty($product_order_items)) {
			$is_offer = 0;
		}*/

		return $is_offer;
	}

	public static function superDeal($zone_id)
	{
		$vendorProduct = [];
		$user  = User::select('*');
		$user->whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor']);
		$user = $user->get()->toArray();

		$user_id_array = [];
		foreach ($user as $kk => $vv) {
			$user_id_array[] = $vv['id'];
		}
		if (count($user_id_array) > 0) {
			$category_name = 'SUPER DUPER OFFER';
			$category = Category::join('categories', 'categories.id', '=', 'category_translations.category_id')->where('category_translations.name', '=', $category_name)->where('categories.status', '=', '1')->first();
			if (isset($category) && !empty($category)) {
				$category_id = $category->category_id;
				$offerProduct = VendorProduct::with(['product.MeasurementClass', 'product.image'])->whereHas('product', function ($q) use ($category_id) {
					$q->where('status', '1')->where('category_id', $category_id);
				})->whereIn('user_id', $user_id_array)->get();
				if (!empty($offerProduct)) {
					$offerProduct = $offerProduct->toArray();
					// echo "<pre>"; print_r($offerProduct); die;
					foreach ($offerProduct as $key => $rec) {
						$offerProduct[$key]['price'] = number_format($rec['price'], 2, '.', '');
						$offerProduct[$key]['offer_price'] = number_format($rec['price'], 2, '.', '');
						$offerProduct[$key]['mrp'] = number_format(!empty($rec['offer_price']) ? $rec['price'] : $rec['best_price'], 2, '.', '');
						$vendorProduct = $offerProduct;
					}
				}
			}
		}
		return $vendorProduct;
	}

	public static function superDealProducts($zone_id)
	{
		$vendorProduct = [];
		$user  = User::select('*');
		$user->whereRaw('FIND_IN_SET(' . $zone_id . ', zone_id) ')->where(['user_type' => 'vendor']);
		$user = $user->get()->toArray();
		foreach ($user as $kk => $vv) {
			$user_id_array[] = $vv['id'];
		}
		$category_name = 'SUPER DUPER OFFER';
		$category = Category::join('categories', 'categories.id', '=', 'category_translations.category_id')->where('category_translations.name', '=', $category_name)->where('categories.status', '=', '1')->first();
		if (isset($category) && !empty($category)) {
			$category_id = $category->category_id;
			$offerProduct = VendorProduct::select('id')->whereHas('product', function ($q) use ($category_id) {
				$q->where('status', '1')->where('category_id', $category_id);
			})->whereIn('user_id', $user_id_array)->get();
			if (!empty($offerProduct)) {
				$offerProduct = $offerProduct->toArray();
				foreach ($offerProduct as $key => $rec) {
					array_push($vendorProduct, $rec['id']);
				}
			}
		}

		return $vendorProduct;
	}
	public static function cartTotalAdmin($user_id)
	{
		$AppSetting = AppSetting::select('mim_amount_for_order', 'mim_amount_for_free_delivery', 'mim_amount_for_free_delivery_prime')->firstOrfail();
		$cartRec = Cart::whereHas('vendorProduct', function ($q) {
			$q->where('status', '1');
		})->with([
			'vendorProduct.Product' => function ($query) { //$query->withTrashed();
			}, 'zone' => function ($query) { // $query->withTrashed();
			},
		])->where(['user_id' => $user_id])->get();
		/**First ordrer product free **/
		$is_free_product = false;
		$flag = 0;
		$order_count = ProductOrder::where('user_id', $user_id)->count();
		if ($order_count == 0) {
			//$zone_id = implode(',', $zone_id);
			$user  = User::select('*')->where(['user_type' => 'vendor'])->get();
			$useridarray = $freeproduct = [];
			foreach ($user as $userkey => $uservalue) {
				$useridarray[] = $uservalue->id;
			}
			$order_count = ProductOrder::where('user_id', $user_id)->count();
			if ($order_count == 0) {
				$first_order = FirstOrder::first();
				if (!empty($first_order->free_product)) {
					$free_product =  $first_order->free_product;
					$freeproductdata = [];
					foreach ($free_product as $kk => $vv) {
						$dd = VendorProduct::with(['Product'])->where('product_id', $vv)->whereIn('user_id', $useridarray)->first();
						if (!empty($dd)) {
							$freeproduct[$kk] = $dd->product_id;
						}
					}
					$first_order->free_product_data = $freeproduct;
				}
				foreach ($cartRec as $dk => $dv) {
					foreach ($freeproduct as $fk => $fv) {
						if ($fv == $dv['vendorProduct']['product_id']) {
							$is_free_product = true;
							$free_product_id = $fv;
						}
					}
				}
			}
		} else {
			$flag = 1;
		}
		/**End First ordrer product free **/
		$offer_price_total = 0;
		$total = 0;
		$dc = 0;
		$parr = $result = [];
		foreach ($cartRec as $Rec) {
			if (!empty($Rec['vendorProduct'])) {

				if ((isset($freeproduct)) && ($flag == 0) && (in_array($Rec['vendorProduct']['product']['id'], $freeproduct))) {
					if ($Rec['qty'] == 1) {
						$Rec['vendorProduct']['offer_price'] = $Rec['vendorProduct']['price'] = 0;
					} else {
						$Rec['qty'] = $Rec['qty'] - 1;
					}
					$Rec['vendorProduct']['is_offer'] = false;
					$Rec['vendorProduct']['offer'] = [];
					$flag = 1;
				} else {
					$Rec['vendorProduct']['offer_price'] = $Rec['vendorProduct']['price'];
					if (!empty($Rec['vendorProduct']['offer_id'])) {
						$offer = Offer::where('id', $Rec['vendorProduct']['offer_id'])->where('from_time', '<=', date('Y-m-d'))->where('to_time', '>=', date('Y-m-d'))->first();
						if (!empty($offer)) {
							$offer->toArray();
							if ($offer['offer_type'] == 'amount') {
								$Rec['vendorProduct']['offer_price'] = $Rec['vendorProduct']['offer_price'] - $offer['offer_value'];
							} else if ($offer['offer_type'] == 'percentages') {
								$Rec['vendorProduct']['offer_price'] = $Rec['vendorProduct']['offer_price'] - (($Rec['vendorProduct']['offer_price'] * $offer['offer_value']) / 100);
							}
							$Rec['vendorProduct']['offer_price'] = number_format($Rec['vendorProduct']['offer_price'], 2, '.', '');
							$Rec['vendorProduct']['is_offer'] = true;
							$Rec['vendorProduct']['offer'] = $offer;
						}
					} else {
						$Rec['vendorProduct']['offer_price'] = $Rec['vendorProduct']['offer_price'];
						$Rec['vendorProduct']['is_offer'] = false;
						$Rec['vendorProduct']['offer'] = [];
					}
				}
			}
			//echo "<pre>"; print_r($Rec); die;
			$total = $total + ($Rec['vendorProduct']['price'] * $Rec['qty']);
			// code change by Abhishek Bhatt for check the minimum amount for free delivery zone wise //
			//$dc = $Rec['Zone']['delivery_charges'];
			$offer_price_total = $offer_price_total + ($Rec['vendorProduct']['offer_price'] * $Rec['qty']);
			if ($offer_price_total >= $Rec['Zone']['minimum_order_amount']) {
				$dc = 0;
			} else {
				$dc = $Rec['Zone']['delivery_charges'];
			}

			$result[] = $Rec;
		}

		$total_saving = $total - $offer_price_total;
		$mim_amount_for_free_delivery = 0;
		//echo "<pre>"; print_r(Auth::guard('api')->user()); die;
		if (!empty(Auth::guard('api')->user()->membership) && (Auth::guard('api')->user()->membership_to >= date('Y-m-d H:i:s'))) {
			if ($offer_price_total >= $AppSetting->mim_amount_for_free_delivery_prime) {
				$dc = 0;
			} else {
				$dc = $dc;
			}
			$mim_amount_for_free_delivery = $AppSetting->mim_amount_for_free_delivery_prime;
		} else {
			$mim_amount_for_free_delivery = (isset($Rec['Zone']['minimum_order_amount']) && $Rec['Zone']['minimum_order_amount'] > 0) ? $Rec['Zone']['minimum_order_amount'] : $AppSetting->mim_amount_for_free_delivery;
			if ($offer_price_total >= $mim_amount_for_free_delivery) {
				$dc = 0;
			} else {
				$dc = $dc;
			}
			//$mim_amount_for_free_delivery = $AppSetting->mim_amount_for_free_delivery;
		}

		return $result = [
			// 'cartRec' => $parr,
			'cart_list' => $result,
			'count' => count($result),
			'min_amount_for_order' => $AppSetting->mim_amount_for_order,
			'min_amount_for_free_delivery' => $mim_amount_for_free_delivery,
			'delivery_charge' => $dc,
			'currency' => 'Rs',
			'is_free_product' => $is_free_product,
			'offer_price_total' => number_format($offer_price_total, 2, '.', ''),
			'total' => number_format($total, 2, '.', ''),
			'total_saving' => number_format($total_saving, 2, '.', ''),
			'total_saving_percentage' => number_format(($total > 0 ? (($total_saving * 100) / $total) : 0), 2, '.', ''),

		];
	}
	public static function getproductDtaa($id)
	{
		return DB::table('product_translations')->where('product_id', $id)->first();
	}

	public static function sendOnesignalNotification($to, $title, $message, $data=array())
	{
		$app_id = config('services.onesignal.ONESIGNAL_APP_ID');
		$rest_api_key = config('services.onesignal.ONESIGNAL_REST_API_KEY');
		$content = array(
			"en" => $message
		);
		$headings = array(
			"en" => $title
		);
		$fields = array(
			'app_id' => "9c09a409-7856-48da-a14e-19d0c39311c4",
			'android_channel_id' => '587a8ded-27d4-4482-b184-7f1cd98a062a',
			"headings" => $headings,
			'include_player_ids' => $to,
			'contents' => $content,
			'content_available' => true, 
			'ios_badgeType'=> 'Increase',
			'ios_badgeCount' => 1,
			'isIos' => true,
			'data' => $data,
			'big_picture' => $data['image_path'] ?? '',
			'large_icon' => $data['image_path'] ?? '',
		);
		

		$headers = array(
			'Authorization: key=YWUzYzIwOGEtYmVhNy00MzNlLWI0YjktOTA5ZmI3ZGYyMTQy',
			'Content-Type: application/json; charset=utf-8'
		);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		$result = curl_exec($ch);
		Log::info($result);
		curl_close($ch);
		return $result;
	}

	public static function getZoneData($lat, $lng)
	{
		$zone_id = '';

		$zonedata = DB::table('zones')->select('id', DB::raw("ST_AsGeoJSON(point) as json","delivery_charges", "minimum_order_amount"))->where('deleted_at', null)->where('status', '=', '1')->get();

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

			$is_exist = self::isPointInPolygon($lat, $lng, $lat_array, $lng_array);

			if ($is_exist) {
				$zData = ZoneTranslation::where('zone_id', $zone_id)->where('locale', App::getLocale())->first();
				$data['match_in_zone'] = true;
				$data['zone_id'] = $zone_id;
				$data['zone_name'] = $zData->name;
				$data['delivery_charges'] = $zvalue['delivery_charges'];
				$data['minimum_order_amount'] = $zvalue['minimum_order_amount'];
				return $data;
			}
		}
		$zone = Zone::where('status', '=', '1')->where('is_default', '=', '1')->withoutGlobalScope(StatusScope::class)->first();
		$zone_id_default = $zone ? $zone->id : 1;
		$zData = ZoneTranslation::where('zone_id', $zone_id_default)->where('locale', App::getLocale())->first();
		$data['match_in_zone'] = false;
		$data['zone_id'] = $zone_id_default;
		$data['zone_name'] = $zData->name;
		$data['delivery_charges'] = $zone->delivery_charges;
		$data['minimum_order_amount'] = $zone->minimum_order_amount;
		return $data;
	}
	public static function isPointInPolygon($latitude, $longitude, $latitude_array, $longitude_array)
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
				if (
					$longitude < (($longitude_array[$k] - $longitude_array[$j]) * ($latitude - $latitude_array[$j])) / ($latitude_array[$k] - $latitude_array[$j]) +
					$longitude_array[$j]
				) {
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
