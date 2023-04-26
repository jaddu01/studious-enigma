<?php
namespace App\Http\Controllers\Admin;


use App\Category;
use App\Image;
use App\Helpers\Helper;
use App\MeasurementClass;
use App\Product;
use App\VendorProduct;
use App\Brand;
use App\BrandTranslation;
use App\Scopes\StatusScope;
use App\User;
use App\Variant;
use App\MeasurementClassTranslation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;
use Excel;
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

class ProductController extends Controller
{
    protected $model;
    protected $user;
    protected $measurementClass;
    protected $method;
    function __construct(Request $request,Product $model,MeasurementClass $measurementClass,User $user,VendorProduct $VendorProduct, Variant $Variant)
    {
        parent::__construct();
        $this->model=$model;
        $this->user=$user;
        $this->Variant=$Variant;
        $this->VendorProduct=$VendorProduct;
        $this->measurementClass=$measurementClass;
        $this->method=$request->method();
        $this->gst = ['0'=>'0%','5'=>'5%','12'=>'12%','18'=>'18%'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if ($this->user->can('view', Product::class)) {
            return abort(403,'not able to access');
        }
        $products = $this->model->with(['MeasurementClass'])->get();
        $brands=Brand::where('status','=','1')->listsTranslations('name','id')->pluck('name','id')->all();
        if(isset($products) && !empty($products)) {
            foreach ($products as $key => $value) {
                $product[$key] = [
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'category_id' => $value['category_id'],
                    'sku_code' => $value['sku_code'],
                    'barcode' => $value['barcode'],
                    'brand_id' => $value['brand_id'],
                    'gst' => ($value['gst']!=null && $value['gst']!='null') ? $value['gst'].' %' : '',
                    'created_at' => date('d/m/Y',strtotime($value['created_at'])),
                    'measurement_class' => $value['measurement_class'],
                    'measurement_value' => $value['measurement_value'],
                    'status' => $value['status']
                ];
                $brand_name = '';
                if($value['brand_id']!='' &&  $value['brand_id']!=null){
                    $brand = BrandTranslation::where('brand_id',$value['brand_id'])->first();
                    //if($brand->isNotEmpty()) {
                        $brand_name = $brand->name;
                    //}
                }
                $product[$key]['brand_name'] = $brand_name;
                $product_category_name ='';
                 $product_categories  = Category::whereIn('id',$value['category_id'])->get();
                 foreach ($product_categories as $key1=>$product_category){
                     $product_category_name.=++$key1.') '.$product_category->name.'<br>';
                }
                $product[$key]['category_name'] = $product_category_name;

            }
        }
        $category=Category::all();
        if(isset($category) && !empty($category)) {
            foreach ($category as $key => $value) {
                $categories[$value['id']] = $value['name'];
            }
            
        }
        return view('admin/pages/product/index')->with('products',$product)->with('categories',json_encode($categories,JSON_HEX_APOS))->with('brands',json_encode($brands,JSON_HEX_APOS));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // print_r('one');exit;
        if ($this->user->can('create', Product::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->model->rules('POST'));
        $related_products = $this->model->where('status','=','1')->listsTranslations('name','id')->pluck('name','id')->all();
        $measurementClass = $this->measurementClass->where('status','=','1')->listsTranslations('name','id')->pluck('name','id')->all();
        $categories=Category::all();
        $brands=Brand::where('status','=','1')->listsTranslations('name','id')->pluck('name','id')->all();
        $gst = $this->gst;
        return view('admin/pages/product/add')->with('categories',$categories)->with('validator',$validator)->with('related_products',$related_products)->with('measurementClass',$measurementClass)->with('brands',$brands)->with('gst',$gst);
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

        if ($validator->fails()) {

            Session::flash('danger',$validator->errors()->first());
            return redirect('admin/product/create')->withErrors($validator)->withInput();
        }else{

            DB::beginTransaction();
            try {

                $input['sku_code'] = $this->getSku();
                $input['disclaimer:en'] = 'While we work to ensure that product information is correct, on occasion manufacturers may alter their ingredient lists. Actual product packaging and materials may contain more and/or different information than that shown on our web site. We recommend that you do not solely rely on the information presented and that you always read labels, warnings, and directions before using or consuming a product. For additional information about a product, please contact the manufacturer.';
                $product = $this->model->create($input);
                if($request->hasFile('image')){
                    $imageName = Helper::fileUpload($request->file('image'),true);
                    $product->images()->createMany($imageName);
                }

                DB::commit();

                Session::flash('success','product create successful');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
                DB::rollBack();

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
        $product=$this->model->with(['images'])->findOrFail($id);
        //echo"<pre>"; print_r($product->toArray());
        $related_products = $this->model->whereIn('id',$product->related_products)->get();
        return view('admin/pages/product/show')->with('product',$product)->with('related_products',$related_products);
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
        $product=$this->model->findOrFail($id);
        $related_products = $this->model->where('products.id','!=',$id)->listsTranslations('name','id')->get()->pluck('name','id');
        $measurementClass = $this->measurementClass->listsTranslations('name','id')->pluck('name','id')->all();
        $categories=Category::all();
        $brands=Brand::where('status','=','1')->listsTranslations('name','id')->pluck('name','id')->all();
        $gst = $this->gst;


        return view('admin/pages/product/edit')->with('product',$product)->with('categories',$categories)->with('validator',$validator)->with('related_products',$related_products)->with('measurementClass',$measurementClass)->with('brands',$brands)->with('gst',$gst);
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
        $input['disclaimer:en'] = 'While we work to ensure that product information is correct, on occasion manufacturers may alter their ingredient lists. Actual product packaging and materials may contain more and/or different information than that shown on our web site. We recommend that you do not solely rely on the information presented and that you always read labels, warnings, and directions before using or consuming a product. For additional information about a product, please contact the manufacturer.';
       //echo "<pre>"; print_r($input); die;
        $product=$this->model->with(['images'])->findOrFail($id);
        //return $product->images;
        if(empty($product->images)){
            return 'hi';
        }

        $validator = Validator::make($request->all(),$this->model->rules($this->method),$this->model->messages($this->method));

        if ($validator->fails()) {

            Session::flash('danger',$validator->errors()->first());
            return redirect('admin/product/create')->withErrors($validator)->withInput();
        }else{

            DB::beginTransaction();
            try {
                $product = $this->model->FindOrFail($id);
                $product->update($input);
                if($request->hasFile('image')){
                    $imageName = Helper::fileUpload($request->file('image'),true);

                    $product->images()->createMany($imageName);
                }

                DB::commit();
                 Session::flash('success','Product updated successfully');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
                DB::rollBack();
            }

            return back();

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
        
        $vproduct = $this->VendorProduct->where('product_id',$id)->get();

        foreach($vproduct as $k=>$v){
          $vp = $this->VendorProduct->withoutGlobalScope(StatusScope::class)->findOrFail($v->id);
          $vp->delete();
        }

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
    public function anyData(Request $request)
    {
        //App::setLocale('in');
            //$product = $this->model->getNewTranslation('in')->get();
        //$product = $this->model->with(['MeasurementClass'])->get();
        //$product = $this->model->get();
        $products = $this->model->with(['MeasurementClass'])->get();
        if(isset($products) && !empty($products)) {
            foreach ($products as $key => $value) {
                $product[$key] = (object)[
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'category_id' => $value['category_id'],
                    'sku_code' => $value['sku_code'],
                    'barcode' => $value['barcode'],
                    'brand_id' => $value['brand_id'],
                    'gst' => $value['gst'],
                    //'created_at' => $value['created_at'],
                    'measurement_class' => $value['measurement_class'],
                    'measurement_value' => $value['measurement_value'],
                    'status' => $value['status']
                ];

            }
        }
        /*echo '<pre>';
        print_r($product);
        echo '</pre>';*/

            return Datatables::of($product)
            ->editColumn('category_name',function ($product){
                $name='';
                 $categories  = Category::whereIn('id',$product->category_id)->get();
                 foreach ($categories as $key=>$category){
                     $name.=++$key.') '.$category->name;
                     $name.= PHP_EOL;
                }
                return $name;
            })->editColumn('brand', function($product) {
                $brand_name = '';
                if($product->brand_id!='' &&  $product->brand_id!=null){
                    $brand = BrandTranslation::where('brand_id',$product->brand_id)->first();
                    //if($brand->isNotEmpty()) {
                        $brand_name = $brand->name;
                    //}
                }
                return $brand_name;
            })
             ->addColumn('barcode',function ($product){
                return $product->barcode;
            })
             ->editColumn('gst',function ($product){
                return ($product->gst!=null && $product->gst!='null') ? $product->gst.' %' : '';
            })
            ->editColumn('measurement_class',function ($product){
                $measurement_class = MeasurementClassTranslation::where('measurement_class_id',$product->measurement_class)->where('locale','en')->first();
                return $measurement_class->name;
            })
            /* ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })*/
            ->addColumn('action',function ($product){
                return '<a href="'.route("product.show",$product->id).'" class="btn btn-success">Show</a><a href="'.route("product.edit",$product->id).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$product->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" product-id="'.$product->id.'" '.(($product->status==1) ? "checked" : "") . ' value="'.$product->status.'" >';
                //return '<a href="'.route("product.show",$product->id).'" class="btn btn-success">Show</a><a href="'.route("product.edit",$product->id).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$product->id.')" class="btn btn-danger">Delete</button>';
            })
            ->rawColumns(['category_id','action'])
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


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteImage(Request $request){

        // if($request->status==1){
        //     $status='0';
        // }else{
        //     $status='1';
        // }  
          
        $productImageCount=Image::where('image_id', $request->product_id)->whereNull('deleted_at')->count();
        if($productImageCount < 2){
              return response()->json([
                    'status' => false,
                    'message' => 'One image is required. You can not delete this image'
                ],400);
        }
       
         
        $image=Image::where('id', $request->id)->first();
        $user= $image->withoutGlobalScope(StatusScope::class)->findOrFail($request->id)->delete();;

        if($request->ajax()){
            if($user){
                return response()->json([
                    'status' => true,
                    'message' => 'Deleted Succefully'
                ],200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'some thing is wrong'
                ],400);
            }
        }
    }

    public function import(){ return view('admin/pages/product/import');}

    public function importExcel(Request $request){
        try{
        $request->validate([
            'import_file' => 'required'
        ]);
        $path = $request->file('import_file')[0]->getRealPath();
        $data = Excel::load($path)->get()->toArray();

        //echo "<pre>"; print_r($data); die;
        if(isset($data[0][0])){
           $valuess=$data[0];
        }else{
          $valuess=$data;
        }

        $catid=array();
        foreach ($valuess as $key => $value) {
        $cates = explode(', ',$value['category']);
        foreach ($cates as $key=>$cate){
        $catid[$key]=$cate;
        }
        $related_products = explode(',',$value['related_products']);
        foreach ($related_products as $key=>$related_product){
        $related_product_id[$key]=$related_product;
        }
        //$measurement_class = MeasurementClassTranslation::where('name',$value['measurement_class'])->where('locale','en')->get()->first()->toArray();
        if(isset($value['measurement_class']) && $value['measurement_class']!='') {
            $measurement_class = MeasurementClassTranslation::where('measurement_class_id',$value['measurement_class'])->where('locale','en')->get()->first()->toArray();
        } else {
            $measurement_class['measurement_class_id'] = 4;
            $value['measurement_value'] = '1';
        }
        $value['measurement_value'] = (isset($value['measurement_value']) && $value['measurement_value']!='') ? $value['measurement_value'] : '1';
        if(isset($value['sku_code']) && $value['sku_code']!='') {
        $input =[
              '_token'=>csrf_token(),
              'category_id'=>$catid,
              'sku_code'=>$value['sku_code'],
              'measurement_class'=>$measurement_class['measurement_class_id'],
              'measurement_value'=>$value['measurement_value'],
              'name:en'=>$value['name'],
              'description:en'=>$value['description'],
              'keywords:en'=>$value['keywords'].' , '.$value['barcode'],
              'hsn_code'=>$value['hsn_code'],
              'is_returnable'=>$value['returnable'],
              'status'=>'1',
              'image'=>array('0'=>''),
              'gst'=>$value['gst'],
              'brand_id'=>(isset($value['brand']) && $value['brand']!='') ? $value['brand'] : 0,
              'disclaimer:en'=>'While we work to ensure that product information is correct, on occasion manufacturers may alter their ingredient lists. Actual product packaging and materials may contain more and/or different information than that shown on our web site. We recommend that you do not solely rely on the information presented and that you always read labels, warnings, and directions before using or consuming a product. For additional information about a product, please contact the manufacturer.',
              'self_life:en'=>'BEST BEFOR '.$value['self_life'].' MONTHS',
              'manufacture_details:en'=>$value['manufacture_details'],
              'marketed_by:en'=>$value['marketed_by'],
              'related_products'=>$related_product_id,
              'barcode'=>$value['barcode'],
              'print_name'=>(isset($value['print_name']) && $value['print_name']!='') ? $value['print_name'] : $value['name']
            ];

            //echo "<pre>"; print_r($input); die;
            DB::beginTransaction();
            try {
                $product = $this->model->where('sku_code', '=', $value['sku_code'])->first();
                if ($product === null) {
                    $catid=array();
                    $cates = explode(', ',$value['category']);
                    foreach ($cates as $key=>$cate){
                        $catid[$key]=$cate;
                    }
                    $input['category_id'] = $catid;
                    $product = $this->model->create($input);
                } else {
                    $catid=array();
                    $cates = explode(', ',$value['category']);
                    foreach ($cates as $key=>$cate){
                        $catid[$key]=$cate;
                    }
                    $input['category_id'] = $catid;
                    $product = $this->model->FindOrFail($product->id);
                    $product->update($input);
                    /*echo '<pre>';
                    print_r($product);
                    echo '</pre>';
                    $product = $product->update($input);*/
                }
                //exit();
            
            if($request->hasFile('image')){
              $imageName = Helper::fileUpload($request->file('image'),true);
              $product->images()->createMany($imageName);
            }
            DB::commit();
            Session::flash('success','product create successful');
            } catch (\Exception $e) {
            Session::flash('danger',$e->getMessage());
            DB::rollBack();
            }
         }
                //return back()->with('success', 'Insert Record successfully.');
            }
            }catch(Exception $e){
              return $e;

            }
            return back()->with('success', 'Insert Record successfully.');
  }


  public function importTest(){ return view('admin/pages/product/importtest');}
  public function editProductData(Request $request) {
    $input = $request->all();
    $input['gst'] = trim(str_replace('%', '', $input['gst']));
    if($input['action']=='edit') {
        DB::beginTransaction();
        try {
            $product = $this->model->FindOrFail($input['id']);
            $product->update($input);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'update'
            ],200);
        } catch (\Exception $e) {
            Session::flash('danger',$e->getMessage());
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'some thing is wrong'
            ],200);
        }
    }
  }

  public function importExcelToUpdateBestPrice(Request $request){
    //echo "<pre>"; print_r($request->all()); die;
    try{
      $request->validate([
        'import_file' => 'required'
      ]);
      $path = $request->file('import_file')[0]->getRealPath();
      $data = Excel::load($path)->get()->toArray();

      //echo "<pre>"; print_r($data); die;
      if(isset($data[0][0])){
        $valuess = $data[0];
      }else{
        $valuess = $data;
      }
      //echo "<pre>"; print_r($valuess); die();
      $catid=array();
      foreach ($valuess as $key => $value) {
        

        //echo "<pre>"; print_r($input); die;
        DB::beginTransaction();
        try {
          $page = VendorProduct::find($value['id']);
          // Make sure you've got the Page model
          if($page) {
              $page->best_price = $value['price_value'];
              $page->price = $value['final_price'];
              $page->save();
          }
          
          DB::commit();
          Session::flash('success','Product best price updated successful');
        } catch (\Exception $e) {
          Session::flash('danger',$e->getMessage());
          DB::rollBack();
        }
      }
      return back()->with('success', 'Insert Record successfully.');
    }catch(Exception $e){
      return $e;

    }
  }

  public function getSku() {
    $data = Product::select('sku_code')->latest()->first();
    $old_sku = $data->sku_code;
    $sku_num = str_replace('DAR-', '', $old_sku);
    $new_sku_num = $sku_num + 1;
    $new_sku_num = str_pad($new_sku_num,6,"0",STR_PAD_LEFT);
    echo $new_sku_num;
    $sku = 'DAR-'.$new_sku_num;
    return $sku;
  }

    public function addVariant(Request $request)
    {
        if ($this->user->can('create', Product::class)) {
            return abort(403,'not able to access');
        }
         
        $validator = JsValidatorFacade::make($this->model->rules('POST'));
        $products=Product::listsTranslations('name','id')->pluck('name','id')->all();

         
        return view('admin/pages/product/addvariant')->with('products',$products)->with('validator',$validator);
    }
    public function savevariant(Request $request)
    {
        try{
            $data = $request->all();
            $product = $this->Variant->create($data);
            Session::flash('success','product create successful');
        } catch (\Exception $e) {
            Session::flash('danger',$e->getMessage());
            DB::rollBack();

        }
        return redirect()->route('admin.product.savevariant')->with('success');
    }
    public function variantview(){
        die("hihihi");
    }
}

