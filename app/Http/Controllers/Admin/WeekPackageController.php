<?php

namespace App\Http\Controllers\Admin;



use App\Scopes\StatusScope;
use App\SlotGroup;
use App\User;
use App\WeekPackage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class WeekPackageController extends Controller
{
    protected $slotGroup;
    protected $weekPackage;
    protected $user;
    protected $method;
    function __construct(Request $request,WeekPackage $weekPackage,SlotGroup $slotGroup,User $user)
    {
        parent::__construct();
        $this->user=$user;
        $this->slotGroup=$slotGroup;
        $this->weekPackage=$weekPackage;
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->user->can('view', WeekPackage::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/week-package/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', WeekPackage::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->weekPackage->rules('POST'));

        $slotGroup=$this->slotGroup->listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/week-package/add')->with('slotGroup',$slotGroup)->with('validator',$validator);
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
        $validator = Validator::make($request->all(),$this->weekPackage->rules($this->method),$this->weekPackage->messages($this->method));

        if ($validator->fails()) {
            dd($validator->errors()->first());
            return redirect('admin/week-package/create')
                ->withErrors($validator)
                ->withInput();
        }else{

            try {
                $this->weekPackage->create($input);
                Session::flash('success','week package create successful');
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
        $weekPackage=$this->weekPackage->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $slotGroup=$this->slotGroup->listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/week-package/show')->with('slotGroup',$slotGroup)->with('weekPackage',$weekPackage);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $validator = JsValidatorFacade::make($this->weekPackage->rules('PUT'));
        $weekPackage=$this->weekPackage->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $slotGroup=$this->slotGroup->listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/week-package/edit')->with('slotGroup',$slotGroup)->with('validator',$validator)->with('weekPackage',$weekPackage);
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
        $validator = Validator::make($request->all(),$this->weekPackage->rules($this->method),$this->weekPackage->messages($this->method));


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{


            $this->weekPackage->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('week-package.index')->with('success','week package Upload successful');
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

        $flight = $this->weekPackage->withoutGlobalScope(StatusScope::class)->findOrFail($id);
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
        $region = $this->weekPackage->get();

        $start = $request->start;
        return Datatables::of($region)

            ->addColumn('Slno',function () use(& $start) {
                return $start = $start+1;
            })
             ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($region){
                return '<a href="'.route("week-package.edit",$region->id).'" class="btn btn-success">Edit</a><button type="button" onclick="deleteRow('.$region->id.')" class="btn btn-danger">Delete</button><a href="'.route("week-package.show",$region->id).'" class="btn btn-success">View Details</a>';
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

        $user= $this->weekPackage->withoutGlobalScope(StatusScope::class)->findOrFail($request->id)->update(['status'=>$status]);

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
