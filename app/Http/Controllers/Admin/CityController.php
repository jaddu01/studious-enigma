<?php

namespace App\Http\Controllers\Admin;



use App\City;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class CityController extends Controller
{
    protected $model;
    protected $user;
    protected $method;
    function __construct(Request $request,City $model,User $user)
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
        if ($this->user->can('view', City::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/city/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', City::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->model->rules('POST'));
        $categories=$this->model->all();
        return view('admin/pages/city/add')->with('categories',$categories)->with('validator',$validator);
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
            return redirect('admin/city/create')
                ->withErrors($validator)
                ->withInput();
        }else{

            try {
                $this->model->create($input);
                Session::flash('success','city created successfully');
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
        $city=$this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $categories=$this->model->all();
        return view('admin/pages/city/edit')->with('city',$city)->with('categories',$categories)->with('validator',$validator);
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
            return redirect()->route('city.index')->with('success','city updated successfully');
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
            //$city = $this->model->getNewTranslation('in')->get();
            $city = $this->model->get();
        //$city =city::query();
        return Datatables::of($city)
             ->addColumn('image',function ($city){
                return '<img src="'.$city->image.'" height="75" width="75"/>';
            })
              ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($city){
                return '<a href="'.route("city.edit",$city->id).'" class="btn btn-success">Edit</a><button type="button" onclick="deleteRow('.$city->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" city-id="'.$city->id.'" '.(($city->status==1) ? "checked" : "") . ' value="'.$city->status.'" >';
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
