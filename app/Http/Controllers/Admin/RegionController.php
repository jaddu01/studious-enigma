<?php

namespace App\Http\Controllers\Admin;


use App\City;
use App\Region;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class RegionController extends Controller
{
    protected $region;
    protected $city;
    protected $user;
    protected $method;
    function __construct(Request $request,City $city,Region $region,User $user)
    {
        parent::__construct();
        $this->city=$city;
        $this->user=$user;
        $this->region=$region;
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->user->can('view', Region::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/region/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', Region::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->region->rules('POST'));
        $cities=$this->city->listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/region/add')->with('cities',$cities)->with('validator',$validator);
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
        $validator = Validator::make($request->all(),$this->region->rules($this->method),$this->region->messages($this->method));

        if ($validator->fails()) {
            dd($validator);
            return redirect('admin/region/create')
                ->withErrors($validator)
                ->withInput();
        }else{

            try {
                $this->region->create($input);
                Session::flash('success','region create successful');
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
        $validator = JsValidatorFacade::make($this->region->rules('PUT'));
        $region=$this->region->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $cities=$this->city->listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/region/edit')->with('region',$region)->with('validator',$validator)->with('cities',$cities);
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
        $validator = Validator::make($request->all(),$this->region->rules($this->method),$this->region->messages($this->method));


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{


            $this->region->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('region.index')->with('success','region Upload successful');
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

        $flight = $this->region->withoutGlobalScope(StatusScope::class)->findOrFail($id);
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
        //App::setLocale('in');
            //$region = $this->model->getNewTranslation('in')->get();
            $region = $this->region->with('City')->get();
        //$region =region::query();
        return Datatables::of($region)
             ->addColumn('image',function ($region){
                return '<img src="'.$region->image.'" height="75" width="75"/>';
            })
          ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($region){
                return '<a href="'.route("region.edit",$region->id).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$region->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" region-id="'.$region->id.'" '.(($region->status==1) ? "checked" : "") . ' value="'.$region->status.'" >';
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

        $user= $this->region->withoutGlobalScope(StatusScope::class)->findOrFail($request->id)->update(['status'=>$status]);

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
