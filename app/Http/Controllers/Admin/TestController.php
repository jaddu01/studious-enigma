<?php

/**
 * @Author: Younet Digital Life
 * @Date:   2021-10-23 00:29:54
 * @Last Modified by:   Abhi Bhatt
 * @Last Modified time: 2022-11-17 14:57:00
 */
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\ProductTranslation;
use App\CategoryTranslation;
use App\BrandTranslation;
use App\MeasurementClassTranslation;
use App\VendorProduct;
use App\Image;
use Excel;

class TestController extends Controller
{
	function __construct(Request $request) {
		parent::__construct();
	}

	public function updateSku() {
		echo 'Sorry ! You cannot access this page';
		exit();
		$number = 1;
		$products = Product::withTrashed()->get();
		if(isset($products) && !empty($products)) {
			foreach ($products as $key => $value) {
				echo $value->sku_code.'<br/>';
				$sku = str_pad($number, 6, "0", STR_PAD_LEFT);
				$sku = 'DAR-'.$sku;
				//Product::where('id',$value->id)->update(['sku_code'=>$sku]);
				Product::withTrashed()->find($value->id)->update(['sku_code'=>$sku]);
				$number++;
			}
		}
		exit();
	}

	public function exportProductData() {
		$products = Product::all();
		if(isset($products) && !empty($products)) {
			foreach ($products as $key => $value) {
				$product_transaltion = ProductTranslation::where('product_id',$value->id)->where('locale','en')->first();
				/*echo '<pre>';
				print_r($product_transaltion);
				echo '</pre>';*/
				//$categories = implode(', ', $value->category_id);
				$categories = $this->getCategoryName($value->category_id);
				$related_products = implode(',', $value->related_products);
				$store_product = $this->getStoreProductData($value->id);
				$product_images = $this->getProductImages($value->id);
				$data[$key]['Sku Code'] = $value->sku_code;
				$data[$key]['HSN Code'] = $value->hsn_code;
				$data[$key]['Barcode'] = $value->barcode;
				$data[$key]['Name'] = $product_transaltion->name;
				$data[$key]['Print name'] = $product_transaltion->print_name;
				$data[$key]['Brand'] = $this->getBrandName($value->brand_id);
				$data[$key]['Category'] = $categories;
				$data[$key]['Description'] = $product_transaltion->description;
				$data[$key]['Disclaimer'] = $product_transaltion->disclaimer;
				$data[$key]['Manufacture Details'] = $product_transaltion->manufacture_details;
				$data[$key]['Marketed By'] = $product_transaltion->marketed_by;
				$data[$key]['Keywords'] = $product_transaltion->keywords;
				$data[$key]['Self Life'] = $product_transaltion->self_life;
				$data[$key]['Slug'] = $product_transaltion->slug;
				$data[$key]['Measurement value'] = $value->measurement_value;
				$data[$key]['Measurement class'] = $this->getMeasurementName($value->measurement_class);
				$data[$key]['Mrp'] = $store_product['mrp'];
				$data[$key]['Best Price'] = $store_product['best_price'];
				$data[$key]['Gst'] = $value->gst;
				$data[$key]['Images'] = $product_images;
				//$data[$key]['Max Order'] = $value->measurement_value;
				
				//$data[$key]['Returnable'] = 'yes';
				
				
				
				//$data[$key]['Related Products'] = $related_products;
				/*echo '<pre>';
				print_r($value);
				echo '<pre>';*/
			}
		}
		Excel::create('products',function($excel) use ($data){
            $excel->sheet('Sheet 1',function($sheet) use ($data){
                $sheet->fromArray($data);
            });
        })->export('xlsx');
        echo 'Products data export successfully.';
		exit();
	}

	public function getCategoryName($category_id,$seprator=',') {
		$categories = '';
		if(isset($category_id) && !empty($category_id)) {
			foreach ($category_id as $key => $value) {
				$categoryData = CategoryTranslation::where('category_id',$value)->where('locale','en')->first();
				if(isset($categoryData) && !empty($categoryData)) {
					$categories .= $categoryData->name.''.$seprator;
				}
			}
		}
		return $categories;
	}

	public function getBrandName($brand_id) {
		$brand_name = '';
		if(isset($brand_id) && $brand_id!='') {
			$brandData = BrandTranslation::where('brand_id',$brand_id)->where('locale','en')->first();
			if(isset($brandData) && !empty($brandData)) {
				$brand_name = $brandData->name;
			}
		}
		return $brand_name;
	}

	public function getMeasurementName($measurement_id) {
		$measurement_name = '';
		if(isset($measurement_id) && $measurement_id!='') {
			$measurementData = MeasurementClassTranslation::where('measurement_class_id',$measurement_id)->where('locale','en')->first();
			if(isset($measurementData) && !empty($measurementData)) {
				$measurement_name = $measurementData->name;
			}
		}
		return $measurement_name;
	}

	public function getStoreProductData($product_id) {
		$product_data = [
			'mrp'=>'',
			'best_price'=>''
		];
		if(isset($product_id) && $product_id!='') {
			$productData = VendorProduct::where('product_id',$product_id)->where('user_id','608')->first();
			if(isset($productData) && !empty($productData)) {
				$product_data = [
					'mrp'=>$productData->price,
					'best_price'=>$productData->best_price
				];
			}
		}

		return $product_data;
	}

	public function getProductImages($product_id,$seprator=',') {
		$images = '';
		if(isset($product_id) && !empty($product_id)) {
			$imageData = Image::where('image_id',$product_id)->where('image_type','App\Product')->get();
			if(isset($imageData) && !empty($imageData)) {
				foreach ($imageData as $key => $value) {
					$images .= $value->name.''.$seprator;
				}
			}
		}
		return $images;
	}
}