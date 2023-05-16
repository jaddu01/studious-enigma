<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Input;
use App\Helpers\Helper;
use App\Offer;
use App\OfferTranslation;
use App\Membership;
use App\User;
use App\Scopes\StatusScope;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;
use Redirect;


class MembershipController extends Controller
{   
    protected $offer;
    protected $membership;
    protected $method;
    function __construct(Request $request,Membership $membership,User $user,Offer $offer  ){
        parent::__construct();
        $this->user=$user;
        $this->offer=$offer;
        $this->membership = $membership;
        $this->method=$request->method();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($user_id=null)
    {
		
		//echo date("2018-12-11", time() + 86400);
		
        if ($this->user->can('view', VendorProduct::class)) {
            return abort(403,'not able to access');
        }
        return view('admin/pages/membership/index');
    }
    
    
        
    
   /*
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $current_time = date("Y-m-d");
        $offerValue = [];
        if ($this->user->can('create', VendorProduct::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->membership->rules('POST'));
        $durations = Helper::$membership_durations;
      
        $offres = $this->offer->whereRaw('to_time >= CAST( "'.$current_time.'" AS DATE )')->listsTranslations('name','id')->pluck('name','id')->all();
        $offerValue = $this->offer->whereRaw('to_time >= CAST( "'.$current_time.'" AS DATE )')->where('offer_type','amount')->pluck('offer_value','id');
        return view('admin/pages/membership/add')->with(['offres'=>$offres,'offerValue'=>$offerValue,'durations'=>$durations,'validator'=>$validator]);
    }


    /*get offer value on change of offer*/
     public function getOfferValue(Request $request)
    {
        $offerId = $request->id;
        $offres = [];
        $offres = $this->offer->where('id',$offerId)->select('offer_value','offer_type')->first();
        if(isset($offres) && !empty($offres)) {
            return response()->json(['status' => 'true', 'data' => $offres]);
        }else{
            return response()->json(['status' => 'false', 'data' => []]);
        }
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
        $validator = Validator::make($request->all(),$this->membership->rules($this->method),$this->membership->messages($this->method));
        if ($validator->fails()) {
            Session::flash('danger',$validator->errors()->first());
            return redirect('admin/membership/create')->withErrors($validator)->withInput();
        }else{

            DB::beginTransaction();
            try {

                if($request->hasFile('image')){
                    $imageName = Helper::fileUpload($request->file('image'));
                    $input['image'] = $imageName;
                }
                if(isset($request->free_delivery)){    $input['free_delivery']='1';}
                else{    $input['free_delivery']='0';}
                $this->membership->create($input);
                 
                DB::commit();

                Session::flash('success','Membership created successfully');
            } catch (Exception $e) {
                return $e;
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
    public function show($id)
    {
        $offerName = '';
        $measurementName  = '';
        $membership=$this->membership->findOrFail($id);
        
        if(isset($membership->offer_id)){
            $offer = $this->offer->where('id',$membership->offer_id)->first();
            $offerName = $offer->name;
        }
        
        return view('admin/pages/membership/show')->with('membership',$membership)->with('offerName',$offerName);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $current_time = date("Y-m-d");
        $offerAmt = [];
        
          $durations = Helper::$membership_durations;
        
        $validator = JsValidatorFacade::make($this->membership->rules('PUT'));
        $membership = $this->membership->with('Offer')->findOrFail($id);
          $durations_arr =  explode(' ',$membership->duration);
        $membership->duration_value = $durations_arr[0];
        $membership->duration_class = $durations_arr[1];
        $offer =  $this->offer->whereRaw('to_time >= CAST( "'.$current_time.'" AS DATE )')->find($membership->id);
      if(isset($offer)){
            $membership->offer_name = $offer->name;
        }else{
            $membership->offer_name = "";
        }

   //     echo "<pre>"; print_r($membership); die;
        $offres=$this->offer->whereRaw('to_time >= CAST( "'.$current_time.'" AS DATE )')->listsTranslations('name','id')->pluck('name','id')->all();
        return view('admin/pages/membership/edit')->with('membership',$membership)->with('validator',$validator)->with('offres',$offres)->with('durations',$durations)->with('offerAmt',$offerAmt);
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
        $offerId = $request->offer_id;
    
        $offers = [];
        $offers = $this->offer->where('id',$offerId)->select('offer_value','offer_type')->first();
       
       
        $validator = Validator::make($request->all(),$this->membership->rules($this->method),$this->membership->messages($this->method));

         if(isset($request->offer) && !empty($request->offer)) {
                if((int)$request->price < (int)$request->offer_price){
                    $validator->getMessageBag()->add('price', 'Price can not be less than offer price');    
                    return Redirect::back()->withErrors($validator)->withInput();
                }
            }
        
        if ($validator->fails()) {

            Session::flash('danger',$validator->errors()->first());
            return redirect('admin/membership/create')->withErrors($validator)->withInput();
        }else{

            DB::beginTransaction();
            try {
                $membership = $this->membership->FindOrFail($id);
                if($request->hasFile('image')){
                    $imageName = Helper::fileUpload($request->file('image'));
                    $input['image'] = $imageName;
                }
                if(isset($request->free_delivery)){    $input['free_delivery']='1';}
                else{    $input['free_delivery']='0';}
              //  echo "<pre>"; print_r($input); die;
                $membership->update($input);

                DB::commit();
                Session::flash('success','Membership updated successfully');
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

        $flight = $this->membership->withoutGlobalScope(StatusScope::class)->findOrFail($id);
        $flight->delete();
        //$flight->deleteTranslations();
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

       $membership = $this->membership->with(['Offer'])->select('*');

        if($request->has('is_offer') and !empty($request->is_offer)){
            if($request->is_offer=='n'){
                $membership->whereNULL('offer_id');
            }else{
                $membership->whereNOTNULL('offer_id');
            }

        }
        if ($request->has('to_date') and !empty($request->to_date)) {
            $membership->whereDate('created_at','>=',$request->to_date." 00:00:00");
        }
        if ($request->has('from_date') and !empty($request->from_date)) {
            $membership->whereDate('created_at','<=',$request->from_date." 23:59:59");
        }
        $membership->get()->toArray();
      //  echo "<pre>"; print_r($membership); die;
        return Datatables::of($membership)   
            ->addColumn('offer_name',function ($membership){
            return //$membership['offer']['name'];
            ((isset($membership['offer']['name']) && !empty($membership['offer']['name']))  ? '<a href="'.route("offer.edit",$membership->Offer->id).'" class="">'.$membership->Offer->name.'</a>' : "Not applicable");
            })
            ->editColumn('duration',function ($membership){
            return $membership->duration."(s)";
            })
            /*->editColumn('free_delivery',function ($membership){
            return (!empty($membership->free_delivery))?'Yes':'No';
            })*/
              ->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })
            ->addColumn('action',function ($membership){
                return '<a href="'.route("membership.show",$membership->id).'" class="btn btn-success">Show</a><a href="'.route("membership.edit",$membership->id).'" class="btn btn-success">Edit</a></br><button type="button" onclick="deleteRow('.$membership->id.')" class="btn btn-danger">Delete</button><input class="data-toggle-coustom"  data-toggle="toggle" type="checkbox" membership-id="'.$membership->id.'" '.(($membership->status==1) ? "checked" : "") . ' value="'.$membership->status.'" >';
            })
            ->rawColumns(['offer_name','action'])
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
        $user= $this->membership->withoutGlobalScope(StatusScope::class)->findOrFail($request->id)->update(['status'=>$status]);

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
