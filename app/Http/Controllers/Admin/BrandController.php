<?php

/**
 * @Author: abhi
 * @Date:   2021-08-30 17:01:51
 * @Last Modified by:   abhi
 * @Last Modified time: 2021-09-14 02:02:40
 */
namespace App\Http\Controllers\Admin;

use App\Brand;
use App\Helpers\Helper;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class BrandController extends Controller
{
    protected $model;
    protected $user;
    protected $method;
    function __construct(Request $request,Brand $model,User $user)
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
        if ($this->user->can('view', Brand::class)) {
        	return abort(403,'not able to access');
        }
        //$slug =  \Request::segment(2);
        $title = 'Brands';
        return view('admin/pages/brand/index')->with('title',$title);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', Brand::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->model->rules('POST'));
        return view('admin/pages/brand/add')->with('validator',$validator);
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
            return redirect('admin/brand/create')
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
                $input['slug:'.$locale] = str_slug($input['name:'.$locale]);
            }
            try {
                $this->model->create($input);
                Session::flash('success','Brand create successful');
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
        $brand=$this->model->findOrFail($id);
        return view('admin/pages/brand/edit')->with('brand',$brand);
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
                $input['slug:'.$locale] = str_slug($input['name:'.$locale]);
            }

            $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('brand.index')->with('success','Brand Upload successful');
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
        $brand =$this->model->select('*');
        

        $brand->get();
        return Datatables::of($brand)
             ->addColumn('image',function ($brand){
                return '<img src="'.$brand->image.'" height="75" width="75"/>';
            })
              ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($brand){
                return '<a href="'.route("brand.edit",$brand->id).'" class="btn btn-success">Edit</a><button type="button" onclick="deleteRow('.$brand->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" brand-id="'.$brand->id.'" '.(($brand->status==1) ? "checked" : "") . ' value="'.$brand->status.'" >';
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