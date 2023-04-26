<?php

namespace App\Http\Controllers\Admin;



use App\DeliveryDay;
use App\DeliveryTime;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class DelivertTimeController extends Controller
{
    protected $deliveryDay;
    protected $deliveryTime;
    protected $user;
    protected $method;
    function __construct(Request $request,DeliveryDay $deliveryDay,DeliveryTime $deliveryTime,User $user)
    {
        parent::__construct();
        $this->deliveryTime=$deliveryTime;
        $this->user=$user;
        $this->deliveryDay=$deliveryDay;
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->user->can('view', DeliveryTime::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/delivery-time/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', DeliveryTime::class)) {
            return abort(403,'not able to access');
        }

        $validator = JsValidatorFacade::make($this->deliveryTime->rules('POST'));
        $deliveryDay=$this->deliveryDay->listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/delivery-time/add')->with('deliveryDay',$deliveryDay)->with('validator',$validator);
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
        $validator = Validator::make($request->all(),$this->deliveryTime->rules($this->method),$this->deliveryTime->messages($this->method));

        if ($validator->fails()) {
            dd($validator);
            return redirect('admin/delivery-time/create')
                ->withErrors($validator)
                ->withInput();
        }else{

            try {
                $this->deliveryTime->create($input);
                Session::flash('success','delivery-time create successful');
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
        $validator = JsValidatorFacade::make($this->deliveryTime->rules('PUT'));
        $deliveryTime=$this->deliveryTime->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $deliveryDay=$this->deliveryDay->listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/delivery-time/edit')->with('deliveryTime',$deliveryTime)->with('validator',$validator)->with('deliveryDay',$deliveryDay);
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
        $validator = Validator::make($request->all(),$this->deliveryTime->rules($this->method),$this->deliveryTime->messages($this->method));


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{


            $this->deliveryTime->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('delivery-time.index')->with('success','delivery-time Upload successful');
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

        $flight = $this->deliveryTime->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $flight->delete();
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
        $region = $this->deliveryDay->get();

        //$region =region::query();
        return Datatables::of($region)

            ->addColumn('details_url', function($region) {
                return url('admin/delivery-time/details/' . $region->id);
            })
            ->make(true);

    }
    public function anyDetailsData($id)
    {
        //App::setLocale('in');
        //$region = $this->model->getNewTranslation('in')->get();
        $region = $this->deliveryTime->where(['delivery_day_id'=>$id])->get();

        //$region =region::query();
        return Datatables::of($region)

            ->addColumn('action',function ($region){
                return '<a href="'.route("delivery-time.edit",$region->id).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$region->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" region-id="'.$region->id.'" '.(($region->status==1) ? "checked" : "") . ' value="'.$region->status.'" >';
            })
            ->rawColumns(['action'])
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

        $user= $this->deliveryTime->withoutGlobalScope(StatusScope::class)->findOrFail($request->id)->update(['status'=>$status]);

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
