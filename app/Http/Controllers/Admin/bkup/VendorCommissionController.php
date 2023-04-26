<?php

namespace App\Http\Controllers\Admin;


use App\Category;
use App\Helpers\Helper;
use App\Offer;
use App\Product;
use App\Scopes\StatusScope;
use App\User;
use App\VendorCommission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;



class VendorCommissionController extends Controller
{
    protected $product;
    protected $user;
    protected $offer;
    protected $category;
    protected $vendorController;
    protected $method;
    function __construct(Request $request,Product $product,VendorCommission $vendorCommission,User $user,Offer $offer,Category $category)
    {
        parent::__construct();
        $this->product=$product;
        $this->user=$user;
        $this->offer=$offer;
        $this->category=$category;
        $this->vendorCommission=$vendorCommission;
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id=null)
    {
		
		$product = $this->vendorCommission->select('*');
		$vendors = $this->vendorCommission->with(['User'])->select('*')->get()->toArray();
			//echo "<pre>" ;print_r($vendors);
			
			
			
			//die;
		
			
		//echo date("2018-12-11", time() + 86400);
		
        if ($this->user->can('view', VendorCommission::class)) {
            return abort(403,'not able to access');
        }
        $users=$this->user->where(['user_type'=>'vendor','role'=>'user'])->get()->pluck('full_name','id');
     //  print_r($users);
     //   die;
        $categories=$this->category->get()->pluck('name','id');
        return view('admin/pages/vendor-commission/index',compact(['user_id','users']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->user->can('create', VendorCommission::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->vendorCommission->rules('POST'));
		$users=$this->user->where(['user_type'=>'vendor','role'=>'user'])->pluck('name','id');
       // $products=$this->product->listsTranslations('name','id')->pluck('name','id')->all();
      //  $offres=$this->offer->listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/vendor-commission/add')->with('validator',$validator)->with('users',$users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),$this->vendorCommission->rules($this->method),$this->vendorCommission->messages($this->method));

        if ($validator->fails()) {

            Session::flash('danger',$validator->errors()->first());
            return redirect('admin/vendor-commission/create')->withErrors($validator)->withInput();
        }else{

            DB::beginTransaction();
            try {

                $this->vendorCommission->create($input);

                DB::commit();

                Session::flash('success','Vendor Commission added successfully');
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
                DB::rollBack();

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


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $validator = JsValidatorFacade::make($this->vendorCommission->rules('PUT'));
        $commission=$this->vendorCommission->findOrFail($id);
        
        
      //echo "<pre>";  print_r($commission->get()->toArray());
       // die;
        
        $users=$this->user->where(['user_type'=>'vendor','role'=>'user'])->pluck('name','id');
      
       
        //$offres=$this->offer->listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/vendor-commission/edit')->with('users',$users)->with('commission',$commission)
        ->with('validator',$validator);
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

//die;
        $validator = Validator::make($request->all(),$this->vendorCommission->rules($this->method),$this->vendorCommission->messages($this->method));

        if ($validator->fails()) {
//print_r($validator->fails());
//die;
            Session::flash('danger',$validator->errors()->first());
            return redirect('admin/vendor-commission/'.$id.'/edit')->withErrors($validator)->withInput();
        }else{


            DB::beginTransaction();
            try {
                $product = $this->vendorCommission->FindOrFail($id);
                $product->update($input);

                DB::commit();
            } catch (\Exception $e) {
                Session::flash('danger',$e->getMessage());
                DB::rollBack();
            }

            return back();


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

        $flight = $this->vendorCommission->findOrFail($id);
        $flight->delete();
       // $flight->deleteTranslations();
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
    public function anyData(Request $request=null)
    {
   //return $request->user_id;
   
       
      
       //$vendors = $this->vendorCommission->with(['User'])->select('*')->get();
      
     $vendors = $this->vendorCommission->with(['User'])->select('*');
   
		if( !empty($request->user_id)){

            $vendors->where(['vendor_id'=>$request->user_id]);
			}
		$vendors=$vendors->get();
       return Datatables::of($vendors) 
            ->addColumn('user.full_name',function ($vendors){                
                return (isset($vendors->user->full_name) ? $vendors->user->full_name:'---');
            }) 
            ->addColumn('action',function ($vendors){
                return '<a href="'.route("vendor-commission.edit",$vendors->id)
                .'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow
                ('.$vendors->id.')" class="btn btn-danger">Delete</button>';
            })
            ->make(true);

    }

    
    
}
