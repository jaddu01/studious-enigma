<?php

namespace App\Http\Controllers\Admin;


use App\City;
use App\Category;
use App\Helpers\Helper;
use App\Scopes\StatusScope;
use App\Offer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class OfferController extends Controller
{
    protected $user;
    protected $offer;
    protected $method;
    function __construct(Request $request,User $user, Offer $offer)
    {
        parent::__construct();
        $this->user=$user;
        $this->offer=$offer;
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->user->can('view', Offer::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/offer/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', Offer::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->offer->rules('POST'));
        $vandors=$this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');

        return view('admin/pages/offer/add')->with('vandors',$vandors)->with('validator',$validator);
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
       //echo '<pre>'; print_r($input);die;
        $validator = Validator::make($request->all(),$this->offer->rules($this->method),$this->offer->messages($this->method));

        if ($validator->fails()) {
            return redirect('admin/offer/create')
                ->withErrors($validator)
                ->withInput();
        }else{
            DB::beginTransaction();
            try {
                $offer = $this->offer->create($input);
                if($request->hasFile('image')){
                    $imageName = Helper::fileUpload($request->file('image'));
                    $offer->images()->create(['name'=>$imageName]);
                }
                Session::flash('success','offer create successful');
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
        $validator = JsValidatorFacade::make($this->offer->rules('PUT'));
        $offer=$this->offer->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $vandors=$this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');
        return view('admin/pages/offer/edit')->with('offer',$offer)->with('vandors',$vandors)->with('validator',$validator);
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
        $validator = Validator::make($request->all(),$this->offer->rules($this->method),$this->offer->messages($this->method));


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            DB::beginTransaction();
            try {
                $offer = $this->offer->withoutGlobalScope(StatusScope::class)->FindOrFail($id);
                $offer->update($input);
                if ($request->hasFile('image')) {
                    $imageName = Helper::fileUpload($request->file('image'));

                    $imageNameKeyValue['name']=$imageName;

                    $offer->images()->updateOrCreate(['image_id' => $id],$imageNameKeyValue);
                }
                DB::commit();
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
                DB::rollBack();
            }
            return redirect()->route('offer.index')->with('success','offer update successful');
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

        $flight = $this->offer->withoutGlobalScope(StatusScope::class)->findOrFail($id);
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
          $category = $this->offer->with('User')->get();
        $start = $request->start;
         return Datatables::of($category)
             ->addColumn('Slno',function () use(& $start) {
                 return $start = $start+1;
             })
             ->addColumn('from_time',function ($offer){
                return date('d/m/Y',strtotime($offer->from_time));
            })
             ->addColumn('to_time',function ($offer){
                return date('d/m/Y',strtotime($offer->to_time));
            })
               ->addColumn('created_at',function ($offer){
                return date('d/m/Y',strtotime($offer->created_at));
            })
            ->addColumn('action',function ($offer){
                return '<a href="'.route("offer.edit",$offer->id).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$offer->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" offer-id="'.$offer->id.'" '.(($offer->status==1) ? "checked" : "") . ' value="'.$offer->status.'" >';
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

        $user= $this->offer->withoutGlobalScope(StatusScope::class)->findOrFail($request->id)->update(['status'=>$status]);

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
