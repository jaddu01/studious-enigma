<?php

namespace App\Http\Controllers\Admin;

use App\Ads;
use App\Helpers\Helper;
use App\Scopes\StatusScope;
use App\User;
use App\Category;
use App\Zone;
use App\Product;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;

class AdsController extends Controller
{
    protected $user;
    protected $model;
    protected $method;
    function __construct(Request $request,Ads $model,User $user)
    {
        parent::__construct();
        $this->model=$model;
        $this->user=$user;
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->user->can('view', Ads::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/ads/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', Ads::class)) {
            return abort(403,'not able to access');
        }
         $zones=Zone::get()->pluck('name','id');
        $category = Category::where('parent_id',0)->get();
        $category = $category->pluck('name','id');
        $subCategory = [];
        $product = [];
        $validator = JsValidatorFacade::make($this->model->rules('POST'),$this->model->messages('POST'));
        return view('admin/pages/ads/add',compact('zones','category','subCategory','product'))->with('validator',$validator);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(),$this->model->rules($this->method),$this->model->messages($this->method));
        if($request->link_type == 'external'){
            if($request->input('link') == ''){
                return back()
                ->withErrors($validator->errors()->add('link', 'Please enter link url'))
                ->withInput();
            }
        }
        if ($validator->fails()) {
            return redirect('admin/ads/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            $locales = config('translatable.locales');
             $input['zone_id'] = implode(',', $request->zone_id);
               if($request->link_type == 'internal'){
                    if($request->vendor_product_id != ''){
                        $input['link_url_type']= 'product';
                    }
                    if($request->vendor_product_id == '' && $request->sub_cat_id!=''){
                        $input['link_url_type']= 'subcategory';
                    }
                    if($request->vendor_product_id == '' && $request->sub_cat_id == ''){
                        $input['link_url_type']= 'category';
                    }
                }
            foreach ($locales as $locale){
                if($request->hasFile('image:'.$locale)){
                    $imageName = Helper::fileUpload($request->file('image:'.$locale));
                    $input['image:'.$locale]=$imageName;

                }
            }
            try {
                $this->model->create($input);
                Session::flash('success',trans('ads.create_success'));
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
            }
            return back();
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $validator = JsValidatorFacade::make($this->model->rules('PUT'));
        $ads=$this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $categories=$this->model->all();
        $zones=Zone::get()->pluck('name','id');
        $selectedZone =  explode(',' , $ads->zone_id);
        $category = Category::where('parent_id',0)->get();
        $category = $category->pluck('name','id');
        $subCategory = [];
        $product = [];
        $products = [];
        if($ads->link_type=='internal'){
            $subCategory = Category::where('parent_id',$ads->cat_id)->get();
            $subCategory=  $subCategory->pluck('name','id');
            if($ads->sub_cat_id != ''){
                $subCatId = $ads->sub_cat_id; 
            }else{
                $subCatId =  $ads->cat_id; ;
            }
            $query = DB::table('products AS p')
                                ->join('vendor_products AS vp','vp.product_id', '=', 'p.id')
                                ->join('product_translations AS pt','pt.product_id', '=', 'p.id')
                                ->where('pt.locale','en');
                $query->whereRaw("find_in_set( $subCatId,p.category_id)");
                $products = $query->select('vp.id  as vendor_product_id','pt.name')->groupBy('p.id')->get();
                $products = collect($products)->pluck('name','vendor_product_id');
        }
       
        return view('admin/pages/ads/edit',compact('zones','category','subCategory','product','selectedZone','products'))->with('ads',$ads)->with('categories',$categories)->with('validator',$validator);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $input = $request->all();
        $validator = Validator::make($request->all(),$this->model->rules($this->method,$id),$this->model->messages($this->method,$id));
        if($request->link_type == 'external'){
            if($request->input('link') == ''){
                return back()
                ->withErrors($validator->errors()->add('link', 'Please enter link url'))
                ->withInput();
                
            }
        }

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            $locales = config('translatable.locales');
             $input['zone_id'] = implode(',', $request->zone_id);
            if($request->link_type == 'internal'){
                $input['link']= '';
                if($request->vendor_product_id != '' || $request->vendor_product_id != null){
                    $input['link_url_type']= 'product';
                }
                if($request->vendor_product_id == '' && $request->sub_cat_id!=''){
                    $input['link_url_type']= 'subcategory';
                }
                if($request->vendor_product_id == '' && $request->sub_cat_id == ''){
                    $input['link_url_type']= 'category';
                }
            } else{
                $input['link_url_type']= '';
                $input['cat_id']= '';
                $input['sub_cat_id']= '';
                $input['vendor_product_id']= '';
            }            
            foreach ($locales as $locale){
                if($request->hasFile('image:'.$locale)){
                    $imageName = Helper::fileUpload($request->file('image:'.$locale));
                    $input['image:'.$locale]=$imageName;

                }
            }

            $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('ads.index')->with('success',trans('ads.update_success'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $flight = $this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $flight->delete();
        $flight->deleteTranslations();
        if($flight){
            return response()->json([
                'status' => true,
                'message' => 'deleted'
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'some thing is wrong'
            ],400);
        }


    }

    /**
     * @return mixed
     */
    public function anyData()
    {

        $ads = $this->model->get();
        //$ads =Category::query();
        return Datatables::of($ads)
            ->addColumn('image',function ($ads){
                return '<img src="'.$ads->image.'" height="75" width="75"/>';
            })
            ->addColumn('link_type',function ($ads){
                return ucwords($ads->link_type);
            })
            ->addColumn('action',function ($ads){
                return '<a href="'.route("ads.edit",$ads->id).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$ads->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" ads-id="'.$ads->id.'" '.(($ads->status==1) ? "checked" : "") . ' value="'.$ads->status.'" >';
            })
            ->rawColumns(['image','action'])
            ->make(true);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus(Request $request){

        if($request->status==1){
            $status='0';
        }else{
            $status='1';
        }

        $user= $this->model->withoutGlobalScope(StatusScope::class)->findOrFail($request->id)->update(['status'=>$status]);

        if($request->ajax()){
            if($user){
                return response()->json([
                    'status' => true,
                    'message' => 'update'
                ],200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'some thing is wrong'
                ],400);
            }
        }
    }
}
