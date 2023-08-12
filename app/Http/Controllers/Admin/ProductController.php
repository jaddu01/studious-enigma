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
use App\ProductTranslation;
use App\Scopes\StatusScope;
use App\User;
use App\Variant;
use App\MeasurementClassTranslation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\ImportJob;
use App\Offer;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;
use Excel;
use Exception;
use Log;
use Storage;

ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

class ProductController extends Controller
{
    protected $model;
    protected $user;
    protected $measurementClass;
    protected $method;
    protected $offer;
    function __construct(Request $request,Product $model,MeasurementClass $measurementClass,User $user,VendorProduct $VendorProduct, Variant $Variant, ProductTranslation $ProductTranslation, Offer $offer)
    {
        parent::__construct();
        $this->model=$model;
        $this->user=$user;
        $this->Variant=$Variant;
        $this->VendorProduct=$VendorProduct;
        $this->producttranslation=$ProductTranslation;
        $this->measurementClass=$measurementClass;
        $this->method=$request->method();
        $this->gst = ['0'=>'0%','5'=>'5%','12'=>'12%','18'=>'18%'];
        $this->offer=$offer;
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
        $brands=Brand::where('status','=','1')->listsTranslations('name','id')->pluck('name','id')->all();
        
        
        return view('admin/pages/product/index')->with('brands',json_encode($brands,JSON_HEX_APOS));
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
        $offers = $this->offer->whereRaw('to_time >= CAST( "'.now()->toDateString().'" AS DATE )')->listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/product/add')->with('categories',$categories)->with('validator',$validator)->with('related_products',$related_products)->with('measurementClass',$measurementClass)->with('brands',$brands)->with('gst',$gst)->with('offers', $offers);
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
                $input['vendor_id'] = Auth::guard('admin')->user()->id;
                $input['sku_code'] = $this->getSku();
                $input['disclaimer:en'] = 'While we work to ensure that product information is correct, on occasion manufacturers may alter their ingredient lists. Actual product packaging and materials may contain more and/or different information than that shown on our web site. We recommend that you do not solely rely on the information presented and that you always read labels, warnings, and directions before using or consuming a product. For additional information about a product, please contact the manufacturer.';
                $product = $this->model->create($input);
                if($request->hasFile('image')){
                    $imageName = Helper::fileUpload($request->file('image'),true);
                    $product->images()->createMany($imageName);
                }

                //add vendor product
                $vendorProduct = $product->VendorProduct->create(['vendor_id'=>Auth::guard('admin')->user()->id,'price'=>$input['price'],'qty'=>$input['qty'],'status'=>1, 'offer_id'=>$input['offer_id'], 'per_order'=>$input['per_order'], 'best_price' => $input['best_price'], 'memebership_p_price' => $input['memebership_p_price']]);

                DB::commit();

                Session::flash('success','product create successful');
            } catch (\Exception $e) {
                dd($e);
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
        $offers = $this->offer->whereRaw('to_time >= CAST( "'.now()->toDateString().'" AS DATE )')->listsTranslations('name','id')->pluck('name','id')->all();


        return view('admin/pages/product/edit')->with('product',$product)->with('categories',$categories)->with('validator',$validator)->with('related_products',$related_products)->with('measurementClass',$measurementClass)->with('brands',$brands)->with('gst',$gst)->with('offers', $offers);
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
        // dd($input);
        $input['vendor_id'] = Auth::guard('admin')->user()->id;
        $input['disclaimer:en'] = 'While we work to ensure that product information is correct, on occasion manufacturers may alter their ingredient lists. Actual product packaging and materials may contain more and/or different information than that shown on our web site. We recommend that you do not solely rely on the information presented and that you always read labels, warnings, and directions before using or consuming a product. For additional information about a product, please contact the manufacturer.';
       //echo "<pre>"; print_r($input); die;
        $product=$this->model->with(['images'])->findOrFail($id);
        //return $product->images;
        // if(empty($product->images)){
        //     return 'hi';
        // }

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

                //update vendor product
                $vendorProduct = $product->VendorProduct->where('product_id',$id)->first();
                $vendorProduct->update(['vendor_id'=>Auth::guard('admin')->user()->id,'price'=>$input['price'],'qty'=>$input['qty'],'status'=>1, 'offer_id'=>$input['offer_id'], 'per_order'=>$input['per_order'], 'best_price' => $input['best_price'], 'memebership_p_price' => $input['memebership_p_price']]);

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
        // $products = $this->model->with(['MeasurementClass','brand']);
        /*echo '<pre>';
        print_r($product);
        echo '</pre>';*/

        try{
            $user = auth('admin')->user();
            // dd($user);
            // DB::enableQueryLog();
            $products = Product::with(['MeasurementClass','brand', 'translations'])->whereHas('translations')->where('vendor_id', $user->id)->select('*');
// dd(DB::getQueryLog());
                return Datatables::eloquent($products)
                ->editColumn('category_name',function ($product){
                    $name='';
                     $categories  = Category::whereIn('id',$product->category_id)->get();
                     foreach ($categories as $key=>$category){
                         $name.=++$key.') '.$category->name ?? '';
                         $name.= PHP_EOL;
                    }
                    return $name;
                })->editColumn('brand', function($product) {
                    $brand_name = '';
                    if($product->brand_id!='' &&  $product->brand_id!=null){
                        $brand = BrandTranslation::where('brand_id',$product->brand_id)->first();
                        //if($brand->isNotEmpty()) {
                            $brand_name = $brand->name ?? '';
                        //}
                    }
                    return $brand_name;
                })
                ->addColumn('name', function($product){
                    $name = '';
                    if($product->name!='' &&  $product->name!=null) {
                        $name = $product->name ?? '';
                    }
                    return $name;
                })
                 ->addColumn('barcode',function ($product){
                    return $product->barcode ?? '-';
                })
                 ->editColumn('price',function ($product){
                    $vproduct = VendorProduct::where('product_id',$product->id)->first();
                    return $vproduct->price ?? '0.00';
                   
                })
                ->editColumn('best_price',function ($product){
                    $vproduct = VendorProduct::where('product_id',$product->id)->first();
                    return $vproduct->best_price ?? '0.00';
                   
                })
                ->editColumn('qty',function ($product){
                    $vproduct = VendorProduct::where('product_id',$product->id)->first();
                    return $vproduct->qty ?? '0';
                   
                })
                ->editColumn('status',function ($product){
                    $vproduct = VendorProduct::where('product_id',$product->id)->first();
                    return $vproduct->status ?? '0';
                    
                   
                })
                ->editColumn('measurement_class',function ($product){
                    $measurement_class = MeasurementClassTranslation::where('measurement_class_id',$product->measurement_class)->where('locale','en')->first();
                    return $measurement_class->name ?? '';
                })
                /* ->addColumn('created_at',function ($user){
                    return date('d/m/Y',strtotime($user->created_at));
                })*/
                ->addColumn('action',function ($product){
                    return '<a href="'.route("product.show",$product->id).'" class="btn btn-success">Show</a><a href="'.route("product.edit",$product->id).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$product->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" product-id="'.$product->id.'" '.(($product->status==1) ? "checked" : "") . ' value="'.$product->status.'" >';
                    //return '<a href="'.route("product.show",$product->id).'" class="btn btn-success">Show</a><a href="'.route("product.edit",$product->id).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$product->id.')" class="btn btn-danger">Delete</button>';
                })
                ->rawColumns(['category_id','action'])
                ->toJson();
        }catch(\Exception $e){
            Log::error($e);
        }

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

    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function makeDefaultImage(Request $request){
        try{
            $image = Image::where('id', $request->id)->first();
            if(!$image){
                return response()->json([
                    'status' => false,
                    'message' => 'Image not found'
                ],400);
            }
    
            $image->is_default = '1';
            $image->save();
    
            return response()->json([
                'status' => true,
                'message' => 'updated Succefully'
            ],200);
        }catch(\Exception $e){
            Log::error($e);
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ],500);
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
              'self_life:en'=>'BEST BEFORE '.$value['self_life'].' MONTHS',
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
                    // dd($input);
                    $product = $this->model->create($input);
                } else {
                    $catid=array();
                    $cates = explode(', ',$value['category']);
                    foreach ($cates as $key=>$cate){
                        $catid[$key]=$cate;
                    }
                    $input['category_id'] = $catid;
                    // dd($input);
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
            // Session::flash('danger',$e);
            dd($e);
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

  //upload excel file and import to db
  public function excelUploadAndImport(Request $request){
    try{
        $request->validate([
            'import_file' => 'required'
        ]);

        if($request->hasFile('import_file')){
            try{
                dispatch_now(new ImportJob());
            }catch(Exception $e){
                Session::flash('danger',$e->getMessage());
               return $e;
            }
            Session::flash('success','File has been uploaded successfully. The file is being processed in the background');
            return back()->with([
                'message' =>
                    'File has been uploaded successfully. The file is being processed in the background',
            ]);
            // dd('File has been uploaded successfully. The file is being processed in the background');
        }else {
            Session::flash('danger','Kindly choose file to import.');
            return back()->with([
                'error_message' => 'Kindly choose file to import.',
            ]);
            // dd('Kindly choose file to import.');
        }

    }catch(Exception $e){
        Log::error($e);
        // dd($e->getMessage());
        Session::flash('danger',$e->getMessage());
        return back()->with([
            'error_message' => 'Oops! Something went wrong. Try again.',
        ]);
    }
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
        return redirect()->route('admin.product.variantview')->with('success');
    }
    public function variantview(){
        // die("hihihi");
        if ($this->user->can('view', Product::class)) {
            return abort(403,'not able to access');
        }
        $products = Variant::get();
        if(isset($products) && !empty($products)) {
            foreach ($products as $key => $value) {
                $p_name = $this->producttranslation->where('product_id', $value['product_id'])->get();
                $pname = '';
                if($p_name && count($p_name) > 0){
                    $pname = $p_name[0]['name'];
                }
                $product[$key] = [
                    'id' => $value['id'],
                    'name' => $pname,
                    'color' => $value['color'],
                    'size' => $value['size'],
                    'measurement' => $value['measurement'],
                    'qty' => $value['qty'],
                    'created_at' => date('d/m/Y',strtotime($value['created_at']))
                ];
                // $product[$key] = ['name'=>$p_name[0]['name']];
                 //echo '<pre>';print_r($product);exit;
            }
        }

        return view('admin/pages/product/variantview')->with('products',$product);
    }
    public function editvariant($id)
    {
        $variant = Variant::find($id);
        $p_name = $this->producttranslation->where('product_id', $variant->product_id)->get()->toArray();
        $variant['name'] = $p_name[0]['name'];
        return view('admin/pages/product/editvariant')->with('variant',$variant);
    }
    public function updateVariant(Request $request, $id)
    {
        $variant = Variant::find($id);
        $variant->color = $request->get('color');
        $variant->size = $request->get('size');
        $variant->measurement = $request->get('measurement');
        $variant->qty = $request->get('qty');
        $variant->update();
        return redirect()->route('admin.product.variantview');
    }
}