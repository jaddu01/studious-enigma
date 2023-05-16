<?php

namespace App\Http\Controllers\Admin;


use App\AccessLevel;
use App\PermissionAccess;
use App\PermissionModal;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class PermissionAccessController extends Controller
{
    protected $permissionAccess;
    protected $accessLevel;
    protected $user;
    protected $method;
    function __construct(Request $request,PermissionAccess $permissionAccess,AccessLevel $accessLevel,User $user)
    {
        parent::__construct();
        $this->permissionAccess=$permissionAccess;
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
        if ($this->user->can('view', PermissionAccess::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/permission_access/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', PermissionAccess::class)) {
            return abort(403,'not able to access');
        }
      $validator = JsValidatorFacade::make($this->permissionAccess->rules('POST'));
      $accessLevels=$this->accessLevel->listsTranslations('name','id')->pluck('name','id')->all();
      foreach ($accessLevels as $key => $value) {
          if($value=='Vendor') {
            $accessLevels[$key] = 'Store';
          }
      }
      
      return view('admin/pages/permission_access/add')->with('validator',$validator)->with('accessLevels',$accessLevels);
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

      //echo '<pre>';
     //print_r($input); die;
        $validator = Validator::make($request->all(),$this->permissionAccess->rules($this->method),$this->permissionAccess->messages($this->method));

        if ($validator->fails()){
            return redirect('admin/permission_access/create')
                ->withErrors($validator)
                ->withInput();
        }else{

            try {
                $data = [];
                $models = PermissionModal::pluck('id')->all();
                foreach ($models as $model){

                    $dataTmp['access_level_id'] = $input['access_level_id'];
                    $dataTmp['permission_modal_id']=$model;
                    if(isset($input['permission_modal_id'][$model])){
                        $dataTmp['type']='Y';
                    }else{
                        $dataTmp['type']='N';
                    }

                   // $this->permissionAccess->create($dataTmp);
                    $permissionAccess = $this->permissionAccess->firstOrNew(array('access_level_id' =>$input['access_level_id'],'permission_modal_id'=>$model));
                    $permissionAccess->fill($dataTmp)->save();

                }


                Session::flash('success','permissionAccess create successful');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
            }
            return back();
        }
    }


    function getHtml(Request $request){ //die;
        $models = PermissionModal::pluck('name','id')->all();
        $permissionAccess =$this->permissionAccess->select('permission_modal_id')->where(['access_level_id'=>$request->access_level_id])->where(['type'=>'Y'])->pluck('permission_modal_id')->toArray();
        $html =  view('admin/pages/permission_access/ajax')->with('models',$models)->with('permissionAccess',$permissionAccess)->with('access_level_id',$request->access_level_id)->render();
        return response()->json([
            'status' => true,
            'message' => 'update',
            'data'=>array('html'=>$html),
        ],200);
    }

}
