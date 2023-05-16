<?php

namespace App\Http\Controllers\Admin;


use App\Helpers\Helper;
use App\Scopes\StatusScope;
use App\User;
use App\Coupon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class CouponController extends Controller
{
    protected $user;
    protected $coupon;
    protected $method;
    function __construct(Request $request,User $user,Coupon $coupon)
    {
        parent::__construct();
          $this->user=$user;
        $this->coupon=$coupon;
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->user->can('view', cCupon::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/coupon/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        if ($this->user->can('create', Coupon::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->coupon->rules('POST'));
        $vandors=$this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');
        $users=$this->user->where(['status'=>'1','role'=>'user'])->where('name', '!=', '')->get()->pluck('name','id');
        
        return view('admin/pages/coupon/add')->with('vandors',$vandors)->with('validator',$validator)->with('users',$users);
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
       // unset($input['user_id']);
       //echo '<pre>'; print_r($input);die;
        $validator = Validator::make($request->all(),$this->coupon->rules($this->method),$this->coupon->messages($this->method));

        if ($validator->fails()) {
            return redirect('admin/coupon/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            DB::beginTransaction();
            try {
                $coupon = $this->coupon->create($input);
                if($request->hasFile('image')){
                    $imageName = Helper::fileUpload($request->file('image'));
                    $coupon->images()->create(['name'=>$imageName]);
                }
                Session::flash('success','coupon create successful');
                DB::commit();
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
        $validator = JsValidatorFacade::make($this->coupon->rules('PUT'));
        $coupon=$this->coupon->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $vandors=$this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');
        $users=$this->user->where(['status'=>'1','role'=>'user'])->where('name', '!=', '')->get()->pluck('name','id');
        
        return view('admin/pages/coupon/edit')->with('coupon',$coupon)->with('vandors',$vandors)->with('validator',$validator)->with('users',$users);
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
        $validator = Validator::make($request->all(),$this->coupon->rules($this->method),$this->coupon->messages($this->method));


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            DB::beginTransaction();
            try {
                $coupon = $this->coupon->withoutGlobalScope(StatusScope::class)->FindOrFail($id);
                $coupon->update($input);
                if ($request->hasFile('image')) {
                    $imageName = Helper::fileUpload($request->file('image'));

                    $imageNameKeyValue['name']=$imageName;

                    $coupon->images()->updateOrCreate(['image_id' => $id],$imageNameKeyValue);
                }
                DB::commit();
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
                DB::rollBack();
            }
            return redirect()->route('coupon.index')->with('success','coupon update successful');
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

        $flight = $this->coupon->withoutGlobalScope(StatusScope::class)->findOrFail($id);
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
          $category = $this->coupon->get();
        $start = $request->start;
         return Datatables::of($category)
             ->addColumn('Slno',function () use(& $start) {
                 return $start = $start+1;
             })
              ->addColumn('from_time',function ($coupon){
                return date('d/m/Y',strtotime($coupon->from_time));
            })
               ->addColumn('to_time',function ($coupon){
                return date('d/m/Y',strtotime($coupon->to_time));
            })
               ->addColumn('created_at',function ($coupon){
                return date('d/m/Y',strtotime($coupon->created_at));
            })
            ->addColumn('action',function ($coupon){
                return '<a href="'.route("coupon.edit",$coupon->id).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$coupon->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" coupon-id="'.$coupon->id.'" '.(($coupon->status==1) ? "checked" : "") . ' value="'.$coupon->status.'" >';
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

        $user= $this->coupon->withoutGlobalScope(StatusScope::class)->findOrFail($request->id)->update(['status'=>$status]);

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
