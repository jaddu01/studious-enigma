<?php

namespace App\Http\Controllers\Admin;



use App\Scopes\StatusScope;
use App\SlotTime;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class SlotTimeController extends Controller
{
    protected $slotTime;
    protected $user;
    protected $method;
    function __construct(Request $request,SlotTime $slotTime,User $user)
    {
        parent::__construct();
        $this->slotTime=$slotTime;
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
        if ($this->user->can('view', SlotTime::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/slot-time/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', SlotTime::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->slotTime->rules('POST'));
        return view('admin/pages/slot-time/add')->with('validator',$validator);
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
        $validator = Validator::make($request->all(),$this->slotTime->rules($this->method),$this->slotTime->messages($this->method));

        if ($validator->fails()) {
            return redirect('admin/slot-time/create')
                ->withErrors($validator)
                ->withInput();
        }else{

            try {
                $this->slotTime->create($input);
                Session::flash('success','slot time create successful');
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
        $validator = JsValidatorFacade::make($this->slotTime->rules('PUT'));
        $deliverySlot=$this->slotTime->withoutGlobalScope(StatusScope::class)->findOrFail($id);

        return view('admin/pages/slot-time/edit')->with('validator',$validator)->with('deliverySlot',$deliverySlot);
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
        $validator = Validator::make($request->all(),$this->slotTime->rules($this->method),$this->slotTime->messages($this->method));


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{


            $this->slotTime->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('slot-time.index')->with('success','slot time Upload successful');
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {

        $flight = $this->slotTime->withoutGlobalScope(StatusScope::class)->findOrFail($id);
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
    public function anyData(Request $request)
    {
        //App::setLocale('in');
        //$region = $this->model->getNewTranslation('in')->get();
        $region = $this->slotTime->get();
        $start = $request->start;
        //$region =region::query();
        return Datatables::of($region)

            ->addColumn('Slno',function () use(& $start) {
                return $start = $start+1;
            })
              ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($offer){
                return '<a href="'.route("slot-time.edit",$offer->id).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$offer->id.')" class="btn btn-danger">Delete</button>';
            })
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

        $user= $this->slotTime->withoutGlobalScope(StatusScope::class)->findOrFail($request->id)->update(['status'=>$status]);

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
