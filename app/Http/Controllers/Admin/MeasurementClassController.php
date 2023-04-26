<?php

namespace App\Http\Controllers\Admin;



use App\MeasurementClass;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class MeasurementClassController extends Controller
{
    protected $model;
    protected $user;
    protected $method;
    function __construct(Request $request,MeasurementClass $model,User $user)
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
        if ($this->user->can('view', MeasurementClass::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/measurement-class/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        if ($this->user->can('create', MeasurementClass::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->model->rules('POST'));
        $categories=$this->model->all();
        return view('admin/pages/measurement-class/add')->with('categories',$categories)->with('validator',$validator);
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
            dd($validator);
            return redirect('admin/measurement-class/create')
                ->withErrors($validator)
                ->withInput();
        }else{

            try {
                $this->model->create($input);
                Session::flash('success','measurement-class create successful');
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
        $validator = JsValidatorFacade::make($this->model->rules('PUT'));
        $measurement_class=$this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $categories=$this->model->all();
        return view('admin/pages/measurement-class/edit')->with('measurement_class',$measurement_class)->with('categories',$categories)->with('validator',$validator);
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
        $validator = Validator::make($request->all(),$this->model->rules($this->method,$id),$this->model->messages($this->method));


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{
            $locales = config('translatable.locales');

            foreach ($locales as $locale){
                if($request->hasFile('image:'.$locale)){
                    $image = $request->file('image:'.$locale);

                    $imageName = time().rand(0,99).'.'.$image->getClientOriginalExtension();

                    $request->file('image:'.$locale)->storeAs(
                        'public/upload', $imageName
                    );

                    $input['image:'.$locale]=$imageName;
                }
            }

            $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('measurement-class.index')->with('success','measurement-class Upload successful');
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
        //App::setLocale('in');
            //$measurement_class = $this->model->getNewTranslation('in')->get();
            $measurement_class = $this->model->tosql();
        //$measurement_class =measurement_class::query();
        return Datatables::of($measurement_class)
            ->addColumn('image',function ($measurement_class){
                return '<img src="'.$measurement_class->image.'" height="75" width="75"/>';
            })
              ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($measurement_class){
                return '<a href="'.route("measurement-class.edit",$measurement_class->id).'" class="btn btn-success">Edit</a><button type="button" onclick="deleteRow('.$measurement_class->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" measurement_class-id="'.$measurement_class->id.'" '.(($measurement_class->status==1) ? "checked" : "") . ' value="'.$measurement_class->status.'" >';
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
