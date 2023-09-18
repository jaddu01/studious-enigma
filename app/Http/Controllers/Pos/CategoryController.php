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
            $categories = Category::with('children')->where('status', '1')->get();

            $this->response->categories = CategoryResource::collection($categories);

            return ResponseBuilder::success($this->response, 'Category list',$this->successStatus);

        }catch(\Exception $e){
            return ResponseBuilder::error($e->getMessage(), $this->errorStatus);
        }
    }
}
