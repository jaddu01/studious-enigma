<?php

namespace App\Http\Controllers\Admin;


use App\Category;
use App\Image;
use App\Helpers\Helper;
use App\MeasurementClass;
use App\Product;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class ProductController extends Controller
{
    protected $model;
    protected $user;
    protected $measurementClass;
    protected $method;
    function __construct(Request $request,Product $model,MeasurementClass $measurementClass,User $user)
    {
        parent::__construct();
        $this->model=$model;
        $this->user=$user;
        $this->measurementClass=$measurementClass;
        $this->method=$request->method();
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
        return view('admin/pages/product/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', Product::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->model->rules('POST'));
        $related_products = $this->model->listsTranslations('name','id')->pluck('name','id')->all();
        $measurementClass = $this->measurementClass->listsTranslations('name','id')->pluck('name','id')->all();
        $categories=Category::all();
        return view('admin/pages/product/add')->with('categories',$categories)->with('validator',$validator)->with('related_products',$related_products)->with('measurementClass',$measurementClass);
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




        return view('admin/pages/product/edit')->with('product',$product)->with('categories',$categories)->with('validator',$validator)->with('related_products',$related_products)->with('measurementClass',$measurementClass);
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
            $product = $this->model->with(['MeasurementClass'])->get();

        return Datatables::of($product)
            ->editColumn('category_id',function ($product){
                $name='';
                 $categories  = Category::whereIn('id',$product->category_id)->get();
                 foreach ($categories as $key=>$category){
                     $name.=++$key.') '.$category->name.'<br>';
                }
                return $name;
            })
            ->addColumn('action',function ($product){
                return '<a href="'.route("product.show",$product->id).'" class="btn btn-success">Show</a><a href="'.route("product.edit",$product->id).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$product->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" product-id="'.$product->id.'" '.(($product->status==1) ? "checked" : "") . ' value="'.$product->status.'" >';
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

}
