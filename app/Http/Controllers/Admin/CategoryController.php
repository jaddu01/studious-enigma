<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Helpers\Helper;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class CategoryController extends Controller
{
    protected $model;
    protected $user;
    protected $method;
    function __construct(Request $request,Category $model,User $user)
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
    public function index($id=null)
    {
        if ($this->user->can('view', Category::class)) {
            return abort(403,'not able to access');
        }
        $slug =  \Request::segment(2);
        if($slug == 'sub-category'){
            $title = 'Sub Category';
        }else{
            $title = 'Categories';
        }
        return view('admin/pages/category/index')->with('cat_td',$id)->with('title',$title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', Category::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->model->rules('POST'));
        $categories=$this->model->where(['parent_id'=>0])->get();
        if(isset($categories) && !empty($categories)) {
            foreach ($categories as $key => $value) {
                $sub_categories = $this->model->where(['parent_id'=>$value->id])->get();
                $categories[$key]['sub_categories'] = $sub_categories;
            }
        }
        return view('admin/pages/category/add')->with('categories',$categories)->with('validator',$validator);
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
            return redirect('admin/category/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            $locales = config('translatable.locales');
           //print_r($locales); die;
            foreach ($locales as $locale){
                if($request->hasFile('image:'.$locale)){

                    Helper::fileUpload($request->file('image:'.$locale));

                    $input['image:'.$locale]=Helper::fileUpload($request->file('image:'.$locale));
                }
                if($request->hasFile('banner_image:'.$locale)){

                    Helper::fileUpload($request->file('banner_image:'.$locale));

                    $input['banner_image:'.$locale]=Helper::fileUpload($request->file('banner_image:'.$locale));
                }
                $input['slug:'.$locale] = str_slug($input['name:'.$locale]);
            }
            if(isset($input['is_show'])) {
                $input['is_show'] = '1';
            } else {
                $input['is_show'] = '0';
            }

            if(isset($input['is_home'])) {
                $input['is_home'] = '1';
            } else {
                $input['is_home'] = '0';
            }
            if(isset($input['is_checkout'])) {
                $input['is_checkout'] = '1';
            } else {
                $input['is_checkout'] = '0';
            }
            try {
                
                $this->model->create($input);
                Session::flash('success','Category create successful');
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
        $category=$this->model->findOrFail($id);
        $categories=$this->model->where('id','!=',$id)->where(['parent_id'=>0])->get();
        if(isset($categories) && !empty($categories)) {
            foreach ($categories as $key => $value) {
                $sub_categories = $this->model->where(['parent_id'=>$value->id])->get();
                $categories[$key]['sub_categories'] = $sub_categories;
            }
        }
        
        return view('admin/pages/category/edit')->with('category',$category)->with('categories',$categories);
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
        /*echo '<pre>';
        print_r($input);
        echo '</pre>';
        exit();*/
        $validator = Validator::make($request->all(),$this->model->rules($this->method),$this->model->messages($this->method));


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            $locales = config('translatable.locales');

            foreach ($locales as $locale){
                if($request->hasFile('image:'.$locale)){
                    Helper::fileUpload($request->file('image:'.$locale));

                    $input['image:'.$locale]=Helper::fileUpload($request->file('image:'.$locale));
                }
                if($request->hasFile('banner_image:'.$locale)){

                    Helper::fileUpload($request->file('banner_image:'.$locale));

                    $input['banner_image:'.$locale]=Helper::fileUpload($request->file('banner_image:'.$locale));
                }
                $input['slug:'.$locale] = str_slug($input['name:'.$locale]);
            }

            if(isset($input['is_show'])) {
                $input['is_show'] = '1';
            } else {
                $input['is_show'] = '0';
            }
            if(isset($input['is_home'])) {
                $input['is_home'] = '1';
            } else {
                $input['is_home'] = '0';
            }
            if(isset($input['is_checkout'])) {
                $input['is_checkout'] = '1';
            } else {
                $input['is_checkout'] = '0';
            }


            $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('category.index')->with('success','Category Upload successful');
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
       /*print_r((new Helper())->delete_cat($this->model->all(),$id,'',''));*/
       $cat_id=Helper::delete_cat($this->model->all(),$id,'','');

        $flight = $this->model->whereIn('id',$cat_id)->delete();
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
    public function anyData(Request $request)
    {
        $category =$this->model->with(['translations'])->select('*');
        if($request->has('cat_id') && !empty($request->cat_id)){
            $category->where('parent_id','=',$request->cat_id);

        }else{
            $category->where('parent_id','=',0);

        }

        $category->get();
        return Datatables::of($category)
             ->addColumn('image',function ($category){
                return '<img src="'.$category->image.'" height="75" width="75"/>';
            })
              ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
              ->addColumn('is_show',function ($category){
                if($category->is_show=='1') {
                    return 'Yes';
                } else {
                    return 'No';
                }
            })
            ->addColumn('action',function ($category){
                return '<a href="'.route("category.edit",$category->id).'" class="btn btn-success">Edit</a><a href="'.route("sub-category.index",$category->id).'" class="btn btn-success">Sub Category</a><button type="button" onclick="deleteRow('.$category->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" category-id="'.$category->id.'" '.(($category->status==1) ? "checked" : "") . ' value="'.$category->status.'" >';
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
