<?php

namespace App\Http\Controllers\Pos;

use App\Category;
use App\Helpers\ResponseBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Response;

class CategoryController extends Controller
{
    public function index(Request $request){
        try{
            $categories = Category::where('parent_id', 0)->where('status', '1')->get();

            $this->response->categories = CategoryResource::collection($categories);

            return ResponseBuilder::success($this->response, 'Category list',$this->successStatus);

        }catch(\Exception $e){
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }

    //get subcategories
    public function subCategories(Request $request, $category_id){
        try{
            $categories = Category::with('children')->where('parent_id', $category_id)->where('status', '1')->get();

            $this->response->categories = CategoryResource::collection($categories);

            return ResponseBuilder::success($this->response, 'Sub Category list',$this->successStatus);

        }catch(\Exception $e){
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }
}
