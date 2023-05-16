<?php

namespace App\Http\Controllers\Api;


use App\Category;
use App\DeliveryDay;
use App\DeliveryLocation;
use App\Helpers\Helper;
use App\Scopes\StatusScope;
use App\Traits\ResponceTrait;
use App\Traits\RestControllerTrait;
use App\User;
use App\VendorProduct;
use App\Zone;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OfferController extends Controller
{
    use RestControllerTrait,ResponceTrait;

    const MODEL = 'App\VendorProduct';
    /**
     * @var Contact
     */
    private $vendorProduct;
    /**
     * @var string
     */
    protected $method;
    /**
     * @var
     */
    protected $validationRules;

    public function __construct(Request $request,VendorProduct $vendorProduct)
    {

        parent::__construct();
        $this->vendorProduct = $vendorProduct;
        $this->method=$request->method();
        $this->validationRules = $this->vendorProduct->rules($this->method);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'lat'=>'required',
            'lng' => 'required'

        ]);
        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);

        }


        try {
            $zone_id = Zone::whereRaw('CONTAINS(point, point('.$request->lat.','.$request->lng.'))')->firstOrFail()->id;

            $user  = User::whereRaw('FIND_IN_SET('.$zone_id.', zone_id) ')->where(['user_type'=>'vendor'])->firstOrFail();

            $vendorProduct = $user->vendorProduct()
                ->with([
                    'product.MeasurementClass',
                    'product.image','cart'=>function($q){
                        $q->where(['user_id'=>Auth::guard('api')->user()->id,'zone_id'=>Auth::guard('api')->user()->zone_id]);
                    },'wishList'=>function($q){
                        $q->where(['user_id'=>Auth::guard('api')->user()->id]);
                    }]);

                if($request->has('category')){
                    $category=explode(',',$request->category);
                   // $category=$request->category;
                    $vendorProduct->with('product')->whereHas(
                        'product',function($q) use($category){

                            //$q->whereIn('category_id', $category);
                            $condition = ' ';

                            foreach ($category as $cat){
                                $condition.="FIND_IN_SET('".$cat."',category_id) or ";
                            }

                        $condition =  rtrim($condition,' or ');
                           // echo $condition;die;
                             $q->whereRaw($condition);
                             //dd($q->toSql());
                        });
                }

                if($request->filled('search')){
                    $search = $request->search;
                    $vendorProduct->whereHas('Product.translations', function($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
                }
            $vendorProduct= $vendorProduct->paginate(config('setting.pagination_limit'))
                ->toArray();
            $data=[];
            foreach ($vendorProduct['data'] as $rec){
                $rec['product']['image'] = $rec['product']['image']['name'];
                unset($rec['product']['related_products']/*,$rec['product']['category_id']*/);
                $data[]=$rec;
            }

            unset($vendorProduct['data']);
            $vendorProduct['product'] = $data;
            $subcategory =[];
            if($request->filled('category')){
                $subcategory = Category::whereIn('parent_id',explode(',',$request->category))->listsTranslations('name','id')->get();
            }
            $vendorProduct['subcategory'] =  $subcategory;
            return $this->showResponse($vendorProduct);

        } catch (\Exception $e) {
            return $this->clientErrorResponse($e);
        }


    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        try {

            $vendorProduct = $this->vendorProduct->whereHas('product')->with(['product.MeasurementClass','product.images','cart'=>function($q){
                $q->where(['user_id'=>Auth::guard('api')->user()->id,'zone_id'=>Auth::guard('api')->user()->zone_id]);
            },'wishList'=>function($q){
                $q->where(['user_id'=>Auth::guard('api')->user()->id]);
            }])->findOrFail($id);
            $vendorProduct->related_products= Helper::relatedProducts($vendorProduct->product->related_products,$vendorProduct->user_id);

           // $image =  $vendorProduct->product->image->name;
            unset(/*$vendorProduct['offer'],*/$vendorProduct['product']['related_products']/*,$vendorProduct['product']['image']*/);
            //$vendorProduct['product']['image'] =$image;
            return $this->showResponse($vendorProduct);

        } catch (\Exception $e) {
            return $this->clientErrorResponse($e);
        }
    }


}
