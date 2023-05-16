<?php
namespace App\Http\Controllers\Admin;

use App\CountryPhoneCode;
use App\Zone;
use App\AccessLevel;
use App\Helpers\Helper;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class UserController extends Controller
{
    protected $user;
    protected $method;
    protected $zone;
    function __construct(Request $request, User $user, Zone $zone)
    {
        parent::__construct();
        $this->user=$user;
        $this->zone=$zone;
        $this->method=$request->method();
        $this->middleware('admin.auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if ($this->user->can('index', User::class)) {
           return abort(403,'not able to access');
        }
        
        $accessLevels = AccessLevel::listsTranslations('name','id')->pluck('name','id')->all();

        return view('admin/pages/user/index')->with('accessLevels',$accessLevels);
    }

    public function create(){
		//echo $this->user->can('create', User::class); die;
		 //die('asfd');
        if ($this->user->can('create', User::class)) {
            return abort(403,'not able to access');
        }
        $countryPhoneCode = CountryPhoneCode::pluck('phonecode','phonecode');
       
        $validator = JsValidatorFacade::make($this->user->rules('POST'));

        $zone=$this->zone->select('id')->get('name');
               
        $already_taken_vender_zones = array_collapse($this->user->select('zone_id')->where('user_type','=','vendor')->pluck('zone_id')->toArray());

        $accessLevels = AccessLevel::listsTranslations('name','id')->where('status','=', '1')->pluck('name','id')->all();
        return view('admin/pages/user/add')->with('zone',$zone)->with('validator',$validator)->with('accessLevels',$accessLevels)->with('already_taken_vender_zones',$already_taken_vender_zones)->with('countryPhoneCode',$countryPhoneCode);
    }

    public function store(Request $request)
    {
        if ($this->user->can('create', User::class)) {
            return abort(403,'not able to access');
        }
        if(!$request->has('password')){
            $request->request->add(['password'=>'123456']);
            $request->request->add(['password_confirmation'=>'123456']);
        }

        $input = $request->all();
        if($input['access_user_id'] = ''){
            $input['access_user_id']='0';
        }

        $validator = Validator::make($request->all(),$this->user->rules($this->method),$this->user->messages($this->method));

        if ($validator->fails()) {

            Session::flash('danger',$validator->errors()->first());
            if($request->ajax()){
                return response()->json([
                    'status' => true,
                    'error' => 'true',
                    'message' => $validator->errors()->first(),
                ],200);
            }else{
                return redirect('admin/user/create')->withErrors($validator)->withInput();
            }

        }else{

            try {
            $input['password'] = bcrypt($input['password']);
                $user = $this->user->create($input);
                $user_id = $user->id;
                Session::flash('success','User create successful');
                $message = 'User create successful';
                $type='success';
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
                $message = $e->getMessage();
                $type='error';
            }
            if($request->ajax()){
                return response()->json([
                    'status' => true,
                    'error' => 'false',
                    'message' => $message,
                    'type' => $type,
                    'user_id'=>$user_id,
                ],200);
            }else{
                return back();
            }

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
        if ($this->user->can('edit', User::class)) {
            return abort(403,'not able to access');
        }
        $user=$this->user->findOrFail($id);
        $countryPhoneCode = CountryPhoneCode::pluck('phonecode','phonecode');

        $zone=$this->zone->select('id')->get('name');
        $already_taken_vender_zones = array_collapse($this->user->select('zone_id')->where('user_type','=','vendor')->where('id','!=',$id)->pluck('zone_id')->toArray());

        $accessLevels = AccessLevel::listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/user/edit')->with('zone',$zone)->with('user',$user)->with('already_taken_vender_zones',$already_taken_vender_zones)->with('countryPhoneCode',$countryPhoneCode)->with('accessLevels',$accessLevels);
    }

    /**
     * @param Request $request
     * @param $id
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {

        if ($this->user->can('edit', User::class)) {
            return abort(403,'not able to access');
        }

        $input = $request->all();
        $validator = Validator::make($request->all(), $this->user->rules($this->method,$id),$this->user->messages($this->method));

        if ($validator->fails()) {

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }else{
           $user= $this->user->FindOrFail($id)->fill($input)->save();
            return redirect()->route('user.index')->with('success','User update successful');
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
       
        if ($this->user->can('delete', User::class)) {
            return abort(403,'not able to access');
        }
       /*print_r((new Helper())->delete_cat($this->user->all(),$id,'',''));*/
       $cat_id=Helper::delete_cat($this->user->all(),$id,'','');

        $flight = $this->user->whereIn('id',$cat_id)->delete();

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
    
    public function changePassword($id)
    {
        if ($this->user->can('edit', User::class)) {
            return abort(403,'not able to access');
        }
        $user = $this->user->findOrFail($id);
        return view('admin/pages/user/change-password')->with('user',$user);
    }
     public function updatePassword(request $request , $id)
    {
      
        if ($this->user->can('edit', User::class)) {
            return abort(403,'not able to access');
        }
         $input = $request->all();
         

        $validator = Validator::make($request->all(), [
        'user_password' => 'required|string|min:6|confirmed',
        ]);
         //$input['password'] = $request->input('user-password');

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }else{
            

            $input['password'] = bcrypt($request->input('user_password'));
            
            //return bcrypt($request->input('user-password'));
            if($this->user->FindOrFail($id)->fill($input)->save()){
                return redirect()->back()->with('success','User update successful');
            }else{
                return redirect()->back()->with('error','Something went wrong');
            }
           
        }
    }

    public function anyData(Request $request)
    {  
        // print_r($request->user_type);die;
        //die;
        // ,['user_type', '!=', 'user']]
        // $product = $this->vendorProduct->with(['User','Offer','Product','Product.translations'])->select('*');
        
        // print_r($accessLevels);die;
        $user =$this->user->where([['id', '!=', 1],['user_type', '!=', 'user']]);
        if($request->has('user_type') && !empty($request->user_type) ){
            $user->where(['user_type'=>$request->user_type]);
        }

        if($request->has('access_user_id') && !empty($request->access_user_id) ){
            $user->where(['access_user_id'=>$request->access_user_id]);
        }

        $user->orderBy('created_at','DESC')->get();
        // print_r($user);die;
        return Datatables::of($user)
            ->addColumn('accesslevel',function ($user){
                $name='';                
                // return $user->access_user_id;
                if($user->access_user_id!=''){
                    $accessLevels = AccessLevel::listsTranslations('name','id')->where('access_levels.id',$user->access_user_id)->pluck('name')->first();
                    // $accessLevels = AccessLevel::listsTranslations('name','id')->pluck('name','id')->all();
                    // $accessLevels = AccessLevel::listsTranslations('name','id')->pluck('name','id')->all();
                    // $accesslevel  = AccessLevel::whereIn('id',$user->access_user_id)->get(); 
                    $name=$accessLevels;
                    // return print_r($accessLevels);
                    // foreach ($accessLevels as $key=>$access){
                    //      $name.=++$key.') '.$access->name.'</br>';
                    // }    
                }else{
                    $name='---';
                }                
                return $name;
            })
             ->addColumn('image',function ($user){
                return '<img src="" height="75" width="75"/>';
            })
            ->addColumn('action',function ($user){
                return '<a href="'.route("user.edit",$user->id).'" class="btn btn-success btn-xs">Edit</a>'.(($user->user_type=='vendor' and $user->role=='user') ? '<a href="'.url("admin/user/product",$user->id).'" class="btn btn-success btn-xs">Product</a>' : '').'<button type="button" onclick="deleteRow('.$user->id.')" class="btn btn-danger btn-xs">Delete</button><input class="data-toggle-coustom " data-size="mini"  data-toggle="toggle" type="checkbox" user-id="'.$user->id.'" '.(($user->status==1) ? "checked" : "") . ' value="'.$user->status.'" ><a href="'.route("user.change-password",$user->id).'" class="btn btn-success btn-xs">Change Password</a>';
            })
            ->rawColumns(['image','action'])
            ->make(true);

    }


    public function changeStatus(Request $request){
        if ($this->user->can('edit', User::class)) {
            return abort(403,'not able to access');
        }
        if($request->status==1){
            $status='0';
        }else{
            $status='1';
        }
        
        $user= $this->user->findOrFail($request->id)->update(['status'=>$status]);

        if($request->ajax()){
            if($user){
                return response()->json([
                    'status' => true,
                    'message' => 'successfully updated'
                ],200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'some thing is wrong'
                ],400);
            }
        }
    }

    public function getUserByPhone(Request $request){
        $request->request->remove('_token');
        $user = $this->user->select('*')->with(['deliveryLocation']);
        foreach ($request->all() as $key=>$item){
            $user->where([$key=>$item]);
        }
        $user = $user->first();
        if ($user){
            $user->deliveryLocation = $user->deliveryLocation->keyBy('id');
        }

        if($user){
            return response()->json([
                'status' => true,
                'message' => 'successfully',
                'data'=>$user
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'no record found'
            ],400);
        }
    }
     public function autoLogout(Request $request){

        $user_id = $request->id;
        $user = User::where('id',$user_id)->first();
        if(isset($user)){
            $deleted = $user->deleted_at;
            $inactive = $user->status;
        }
        if($deleted != null || $deleted != '' || $inactive == 0){
            Auth::guard('admin')->logout();
            $request->session()->flush();
            $request->session()->regenerate();
            return response()->json(['status'=>'inactive']);
        }else{
            return response()->json(['status'=>'active']);
        }

     }

    
}
