<?php

namespace App\Http\Controllers\Admin;


use App\AccessLevel;
use App\Policies\AccessLevelPolicy;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class AccessLevelController extends Controller
{
    protected $accessLevel;
    protected $user;
    protected $method;
    function __construct(Request $request,AccessLevel $accessLevel,User $user)
    {
        parent::__construct();
        $this->accessLevel=$accessLevel;
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
        if ($this->user->can('view', AccessLevel::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/access_level/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', AccessLevel::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->accessLevel->rules('POST'));
        return view('admin/pages/access_level/add')->with('validator',$validator);
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
        $validator = Validator::make($request->all(),$this->accessLevel->rules($this->method),$this->accessLevel->messages($this->method));

        if ($validator->fails()) {
            return redirect('admin/access_level/create')
                ->withErrors($validator)
                ->withInput();
        }else{

            try {
                $this->accessLevel->create($input);
                Session::flash('success','accessLevel create successful');
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
        $validator = JsValidatorFacade::make($this->accessLevel->rules('PUT'));
        $access_level=$this->accessLevel->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        return view('admin/pages/access_level/edit')->with('access_level',$access_level)->with('validator',$validator);
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
        $validator = Validator::make($request->all(),$this->accessLevel->rules($this->method),$this->accessLevel->messages($this->method));


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{


            $this->accessLevel->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('access_level.index')->with('success','accessLevel Upload successful');
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

        $flight = $this->accessLevel->withoutGlobalScope(StatusScope::class)->findOrFail($id);
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

        $accessLevel = $this->accessLevel->get();

        return DataTables::of($accessLevel)
             ->addColumn('image',function ($accessLevel){
                return '<img src="'.$accessLevel->image.'" height="75" width="75"/>';
            })
               ->addColumn('created_at',function ($accessLevel){
                return date('d/m/Y',strtotime($accessLevel->created_at));
            })
             ->addColumn('action',function ($accessLevel){
                return '<a href="'.route("access_level.edit",$accessLevel->id).'" class="btn btn-success">Edit</a><button type="button" onclick="deleteRow('.$accessLevel->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" accessLevel-id="'.$accessLevel->id.'" '.(($accessLevel->status==1) ? "checked" : "") . ' value="'.$accessLevel->status.'" >';
            })
            ->addColumn('name',function ($accessLevel){
                if($accessLevel->name=='Vendor') {
                    return 'Store';
                } else {
                    return $accessLevel->name;
                }
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

        $user= $this->accessLevel->withoutGlobalScope(StatusScope::class)->findOrFail($request->id)->update(['status'=>$status]);

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
