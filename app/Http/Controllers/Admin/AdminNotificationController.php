<?php

namespace App\Http\Controllers\Admin;
use App\Category;
use App\AdminNotification;
use App\Helpers\Helper;
use App\Scopes\StatusScope;
use Auth;
use App\User;
use App\VendorProduct;
use App\ProductOrder;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;
use DB;

class AdminNotificationController extends Controller
{
    protected $model;
    protected $user;
    protected $method;
    protected $productorder;
    function __construct(Request $request,AdminNotification $model,User $user,ProductOrder $productorder,VendorProduct $vendorproduct)
    {
        parent::__construct();
        $this->model=$model;
        $this->user=$user;
        $this->user=$user;
        $this->vendorproduct=$vendorproduct;
        $this->productOrder  = $productorder;
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         if ($this->user->can('view', Notification::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/admin-notification/index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', Notification::class)) {
            return abort(403,'not able to access');
        }
        $category = Category::where('parent_id',0)->get();
        $category = $category->pluck('name','id');
        $subCategory = [];
        $product = [];
        $validator = JsValidatorFacade::make($this->model->rules('POST'),$this->model->messages('POST'));
        $users=$this->user->where('user_type','user')->get()->pluck('name','id');
        //return $users;
        return view('admin/pages/admin-notification/add',compact('category','subCategory','product'))->with('users',$users)->with('validator',$validator);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function sendNotification ($user_id_array ,$dataArray, $device_type)
    {
        $push_message = "Test message";
        $fields = [];
        $iphone[] = collect($user_id_array)->pluck('device_token');
       
        if ( $iphone && !empty( $iphone ) ) {
            // echo count ( $iphone );die;
            if ( count ( $iphone ) == 1 ) {
                $iphone = $iphone[ 0 ];

            }
            $iphone = array_chunk(collect($iphone)->toArray(),900);
            if(count($iphone) > 0){
                foreach ($iphone as $key => $value) {
                    //echo "<pre>"; print_r($value); die();
                    //$url = env('FCM_URL');
                    $url = 'https://fcm.googleapis.com/fcm/send';
                    //$server_key = 'AIzaSyDplQtBPXtJwkI42VdkSH541Cxu0tIeckY';
                    $server_key = env('FCM_API_KEY');
                       
                    if ( $device_type == 'I' ) {

                        $fields = array
                        (
                            //'priority' => "high" ,
                            'notification' => array( "title" => $dataArray['message_heading'] ,"body" => $dataArray['message'] ,"sound" => "mySound" ,'badge' => count ( $dataArray ) ,'vibrate' => 1 ) ,
                            'data' => $dataArray ,
                        );
                    } else if ( $device_type == 'A' ) {
                        
                        $fields = array
                        (
                            //'priority' => "high" ,
                            /*'notification' => array( "title" => $dataArray['message_heading'] ,"body" => $dataArray['message'] ,"sound" => "mySound" ,'badge' => count ( $dataArray ) ,'vibrate' => 1 ) ,*/
                            'data' => array(
                                'type' => 'promotion' ,
                                'image' => $dataArray['image_path'] ,
                                'link' => $dataArray['message_url'] ,
                                'link_type' => $dataArray['link_type'] ,
                                'cat_id' => $dataArray['cat_id'] ,
                                'sub_cat_id' => $dataArray['sub_cat_id'] ,
                              //  'vendor_product_id' => $dataArray['vendor_product_id'] ,
                                'title' => $dataArray['message_heading'] ,
                                'body' => $dataArray[ 'message' ]
                            ) ,
                        );
                    }
                   
                    $fields[ 'registration_ids' ] = $value;
                    
                    // $fields = json_encode($fields);
                 
                    // echo '<pre>';print_r($fields);die;
                    $headers = array(
                        'Content-Type:application/json' ,
                        'Authorization:key=' . $server_key
                    );
                    $ch = curl_init ();
                    curl_setopt ( $ch ,CURLOPT_URL ,$url );
                    curl_setopt ( $ch ,CURLOPT_POST ,true );
                    curl_setopt ( $ch ,CURLOPT_HTTPHEADER ,$headers );
                    curl_setopt ( $ch ,CURLOPT_RETURNTRANSFER ,true );
                    curl_setopt ( $ch ,CURLOPT_SSL_VERIFYHOST ,0 );
                    curl_setopt ( $ch ,CURLOPT_SSL_VERIFYPEER ,false );
                    curl_setopt ( $ch ,CURLOPT_POSTFIELDS ,json_encode ( $fields ) );
                    $result = curl_exec ( $ch );
                    //echo "hello"; echo '<pre>';print_r($result);die;
                    if ( $result === FALSE ) {
                        die( 'FCM Send Error:' . curl_error ( $ch ) );
                    }

                    curl_close ( $ch );
                }
            }
            return true;
        }

    }
    public function store(Request $request)
    {
        $userData = User::get();
         
        $input = $request->all();
        $user_id_array = [];
        $user_ids = [];
       
        $validator = Validator::make($request->all(),$this->model->rules($this->method),$this->model->messages($this->method));

        if ($validator->fails()) {
            return redirect('admin/admin-notification/create')
                ->withErrors($validator)
                ->withInput();
        }else{

            if($request->hasFile('image')){
                $imageName = Helper::fileUpload($request->file('image'));
                $input['image']= $imageName;

            }
            $base_url = url('/');
            $input['image_path']= $base_url.'/storage/app/public/upload/'.$imageName;
            $data_array = $input;
            if($request->has('cat_id')){
                $input['cat_id'] =  $request->input('cat_id');
            }else{
                $input['cat_id'] = null;
            }
            if($request->has('sub_cat_id')){
                $input['sub_cat_id'] =  $request->input('sub_cat_id');
            }else{
                $input['sub_cat_id'] = null;
            }
            if($request->has('vendor_product_id')){
                $input['vendor_product_id'] =  $request->input('vendor_product_id');
            }else{
                $input['vendor_product_id'] = null;
            }
            //return  $data_array;
            if($request->filled('user_ids')){
                $input['user_ids']= implode(',',$request->user_ids);
                $user_ids = $request->user_ids;
                $user_id_array_iphone = $userData->where('device_type','I')->whereIn('id',$request->user_ids);
                $user_id_array_android = $userData->where('device_type','A')->whereIn('id',$request->user_ids);
            }

            if($request->has('selection')){
                $input['selection']='all';
                $user_id_array_android = $userData->where('device_type','=','A');
                $user_id_array_iphone = $userData->where('device_type','=','I');
            }
            //echo"<pre>";print_r($user_id_array_android);die;
            //echo"<pre>";print_r(collect($user_id_array_iphone)->pluck('device_token'));die();
            try {
                //echo"<pre>";print_r($user_id_array_android);die;
                $this->model->create($input);
                if(count($user_id_array_android) > 0){
                    $this->sendNotification($user_id_array_android, $data_array, 'A');
                }
                if(count($user_id_array_iphone) > 0){
                    $this->sendNotification($user_id_array_iphone, $data_array,'I');
                }
                Session::flash('success',trans('admin-notification.create_success'));

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
        $admin_notification=$this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $users=$this->user->all()->pluck('name','id');
        return view('admin/pages/admin-notification/show')->with('admin_notification',$admin_notification)->with('users',$users);
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
        $admin_notification=$this->model->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $users=$this->user->all()->pluck('name','id');
        return view('admin/pages/admin-notification/edit')->with('admin_notification',$admin_notification)->with('users',$users)->with('validator',$validator);
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
        $validator = Validator::make($request->all(),$this->model->rules($this->method,$id),$this->model->messages($this->method,$id));


        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }else{

            if($request->hasFile('image')){
                $imageName = Helper::fileUpload($request->file('image'));
                $input['image']=$imageName;

            }

            $this->model->withoutGlobalScope(StatusScope::class)->FindOrFail($id)->update($input);
            return redirect()->route('admin-notification.index')->with('success',trans('admin-notification.update_success'));
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
    public function unavailable()
    {

        //redirect()->route('admin-notification.unavailable');
       return view('admin/pages/admin-notification/unavailable');
      
            
    }
    
    
    
    
      public function unavailableanydata()
    {
    
    $product = $this->vendorproduct->with(['User','Offer','Product','Product.translations'])->where('qty','=',0)->select('*')->get();
 //$product->get()->toArray();
   //  echo "<pre>";print_r($product->toArray());
       //echo json_encode("sdf");
    //die;
        return Datatables::of($product)           
            ->addColumn('category_id',function ($product){
                $name='';
                // print_r($product->category_id);die;
                 $categories  = Category::whereIn('id',$product->product->category_id)->get();
                 foreach ($categories as $key=>$category){
                     $name.=++$key.') '.$category->name.'</br>';
                }
                return $name;
            })
            ->addColumn('user.full_name',function ($product){                
                return (isset($product->user->full_name) ? $product->user->full_name:'---');
            })
            ->addColumn('offer_name',function ($product){
				
 return ((isset($product->Offer->name) && !empty($product->Offer->name) && date('Y-m-d',strtotime("today")) > date('Y-m-d',strtotime($product->Offer->from_time)) )? 'No Offer':((isset($product->Offer->name) && !empty($product->Offer->name))  ? '<a href="'.route("offer.edit",$product->Offer->id).'" class="">'.$product->Offer->name.'</a>' : "No Offer"));
            })
              ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($product){
                return '<a href="'.route("vendor-product.show",$product->id).'" class="btn btn-success">Show</a><a href="'.route("vendor-product.edit",$product->id).'" class="btn btn-success">Edit</a></br>';
            })
            ->rawColumns(['offer_name','action','category_id'])
            ->make(true);
    
    
}
    
    
    

    /**
     * @return mixed
     */
    public function anyData()
    {
        //DB::enableQueryLog();
        $admin_notification = $this->model->orderBy('created_at','DESC')->get();
        //$slider =Category::query();
        //print_r(DB::getQueryLog()); die();
        return Datatables::of($admin_notification)
            ->addColumn('image',function ($admin_notification){
                return '<img src="'.$admin_notification->image.'" height="75" width="75"/>';
            })
            ->addColumn('user',function ($admin_notification){
                $name='';
                if($admin_notification->selection == null){
                    $users  = User::whereIn('id',$admin_notification->user_ids)->get();
                    foreach ($users as $key=>$user){
                        $name.=++$key.') '.$user->name.'<br>';
                    }
                }else{
                    $name = 'all';
                }
                return $name;
            })
            ->addColumn('created_at',function ($user){
                //return date('d/m/Y', strtotime($user->created_at));
                return $user->created_at->timestamp;
            })
            ->addColumn('action',function ($admin_notification){
                return '<a href="'.route("admin-notification.show",$admin_notification->id).'" class="btn btn-success">View</a></br><button type="button" onclick="deleteRow('.$admin_notification->id.')" class="btn btn-danger">Delete</button>';
            })
            ->rawColumns(['image','action','user','message'])
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
    
    
        public function orderNotification(Request $request){
	//echo $session_id = session()->getId();
	$user = Auth::user();
	//echo "<pre>"; print_r(Auth::guard('admin')->user());
	//echo Auth::id();
	//echo Auth::guard('admin')->user()->id;
	//print_r($user);
	//die("fgftu");
        $user= $this->productOrder->where('shopper_id','=',Auth::guard('admin')->user()->id)->get()->toArray();
        
    //   echo "<pre>"; print_r($user);
        
     //   die();

      return view('admin/pages/admin-notification/ordernotification');
    }

    
    
    
    
    
    
    
    
    
    
    
    
}
