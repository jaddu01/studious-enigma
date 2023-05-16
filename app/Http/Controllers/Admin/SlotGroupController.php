<?php

namespace App\Http\Controllers\Admin;



use App\Scopes\StatusScope;
use App\SlotGroup;
use App\SlotTime;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class SlotGroupController extends Controller
{
    protected $slotGroup;
    protected $slotTime;
    protected $user;
    protected $method;
    function __construct(Request $request,SlotGroup $slotGroup,SlotTime $slotTime,User $user)
    {
        parent::__construct();
        $this->slotGroup=$slotGroup;
        $this->user=$user;
        $this->slotTime=$slotTime;
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->user->can('view', SlotGroup::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/slot-group/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', SlotGroup::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->slotGroup->rules('POST'));
        $slotTime=$this->slotTime->get()->pluck('name','id');
        return view('admin/pages/slot-group/add')->with('slotTime',$slotTime)->with('validator',$validator);
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
        $validator = Validator::make($request->all(),$this->slotGroup->rules($this->method),$this->slotGroup->messages($this->method));

        if ($validator->fails()) {
            dd($validator);
            return redirect('admin/slot-group/create')
                ->withErrors($validator)
                ->withInput();
        }else{

            try {
                $this->slotGroup->create($input);
                Session::flash('success','slot group create successful');
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
        $validator = JsValidatorFacade::make($this->slotGroup->rules('PUT'));
        $slotGroup=$this->slotGroup->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $slotTime=$this->slotTime->get()->pluck('name','id');
        return view('admin/pages/slot-group/edit')->with('slotTime',$slotTime)->with('validator',$validator)->with('slotGroup',$slotGroup);
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
        $validator = Validator::make($request->all(),$this->slotGroup->rules($this->method),$this->slotGroup->messages($this->method));


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{


            $this->slotGroup->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('slot-group.index')->with('success','slot group Upload successful');
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

        $flight = $this->slotGroup->withoutGlobalScope(StatusScope::class)->findOrFail($id);
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
        $region = $this->slotGroup->get();

        $start = $request->start;
        return Datatables::of($region)

            ->addColumn('Slno',function () use(& $start) {
                return $start = $start+1;
            })

            ->editColumn('slot_ids',function ( $region)  {

                $html = '';
                foreach ($region->getSlotTimes() as $key =>$slotTime){
                    $html.='('.$key.')'.$slotTime->name.'<br>';
                }
                return $html;
            })
              ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($region){
                return '<a href="'.route("slot-group.edit",$region->id).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$region->id.')" class="btn btn-danger">Delete</button>';
            })
            ->rawColumns(['slot_ids','action'])
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
