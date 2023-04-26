<?php

/**
 * @Author: Abhi Bhatt
 * @Date:   2022-03-11 23:43:25
 * @Last Modified by:   Abhi Bhatt
 * @Last Modified time: 2022-03-17 01:27:12
 */
namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Scopes\StatusScope;
use App\Medias;
use App\User;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;

class MediasController extends Controller
{
    protected $model;
    protected $user;
    protected $method;
    function __construct(Request $request,Medias $model,User $user)
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
    public function primeMembershipImages()
    {
        if ($this->user->can('view', Medias::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/medias/prime-membership-images');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', Medias::class)) {
            return abort(403,'not able to access');
        }
      
        $validator = JsValidatorFacade::make($this->model->rules('POST'),$this->model->messages('POST'));
        return view('admin/pages/medias/add')->with('validator',$validator);
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
            return redirect('admin/medias/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            $locales = config('translatable.locales');
           
            foreach ($locales as $locale){
                if($request->hasFile('image:'.$locale)){
                    $imageName = Helper::fileUpload($request->file('image:'.$locale));
                    $input['image:'.$locale]=$imageName;

                }
            }
            try {
                $this->model->create($input);
                Session::flash('success',trans('slider.create_success'));
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
    public function primeMembershipEditImage($id)
    {
        $validator = JsValidatorFacade::make($this->model->rules('PUT'));
        $slider=$this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id);
       
        return view('admin/pages/medias/prime-membership-edit-image')->with('slider',$slider)->with('validator',$validator);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function primeMembershipUpdateImage(Request $request, $id)
    {

        $input = $request->all();
        $validator = Validator::make($request->all(),$this->model->rules($this->method,$id),$this->model->messages($this->method,$id));
       
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            $locales = config('translatable.locales');
            
            foreach ($locales as $locale){
                if($request->hasFile('image:'.$locale)){
                    $imageName = Helper::fileUpload($request->file('image:'.$locale));
                    $input['image:'.$locale]=$imageName;

                }
            }

            $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('admin.media.prime-membership-image')->with('success',trans('offer-slider.update_success'));
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

        $slider = $this->model::where('media_type','=',$_GET['media_type'])->get();
        //$slider =Category::query();
        return Datatables::of($slider)
            ->addColumn('image',function ($slider){
                return '<img src="'.$slider->image.'" height="75" width="75"/>';
            })
              ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($slider){
                if($_GET['media_type']=='prime_membership') {
                    $edit_url = 'admin.media.prime-membership-image.edit';
                }
                if($_GET['media_type']=='refer') {
                    $edit_url = 'admin.media.refer-image.edit';
                }
                //return '<a href="'.route($edit_url,$slider->id).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$slider->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" slider-id="'.$slider->id.'" '.(($slider->status==1) ? "checked" : "") . ' value="'.$slider->status.'" >';
                return '<a href="'.route($edit_url,$slider->id).'" class="btn btn-success">Edit</a></br><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" slider-id="'.$slider->id.'" '.(($slider->status==1) ? "checked" : "") . ' value="'.$slider->status.'" >';
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

    public function referImages()
    {
        if ($this->user->can('view', Medias::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/medias/refer-images');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function referEditImage($id)
    {
        $validator = JsValidatorFacade::make($this->model->rules('PUT'));
        $slider=$this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id);
       
        return view('admin/pages/medias/refer-edit-image')->with('slider',$slider)->with('validator',$validator);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function referUpdateImage(Request $request, $id)
    {

        $input = $request->all();
        $validator = Validator::make($request->all(),$this->model->rules($this->method,$id),$this->model->messages($this->method,$id));
       
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            $locales = config('translatable.locales');
            
            foreach ($locales as $locale){
                if($request->hasFile('image:'.$locale)){
                    $imageName = Helper::fileUpload($request->file('image:'.$locale));
                    $input['image:'.$locale]=$imageName;

                }
            }

            $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('admin.media.refer-image')->with('success',trans('offer-slider.update_success'));
        }
    }

  
}
