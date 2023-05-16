<?php namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\User;
use App\ProductOrder;
use App\OrderStatusNew;
use App\ProductOrderItem;
use App\DeliveryLocation;
use App\Zone;

use App\CountryPhoneCode;
use App\CategoryTranslation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Helpers\Helper;

use App\Partner;


class JoinpartnerController extends Controller
{
    /**
     * Constructor method
     */
     public function __construct(User $user,Partner $partner)
   
    {
         parent::__construct();
         $this->partner=$partner;
        $this->middleware('auth');
    }



    public function index(){
        $countryPhoneCode  = CountryPhoneCode::orderBy('phonecode')->pluck('phonecode','phonecode');  
        $phone_code ='974'  ;
        $validator = JsValidatorFacade::make($this->partner->rules('POST'));
        return view('pages.joinpartner')->with('countryPhoneCode',$countryPhoneCode)->with('phone_code',$phone_code)->with('validator',$validator);
    }

   
    public function store(Request $request)
    {
       // print_r($request->all());die;
      
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'shopname' => 'required|min:3',
            'phone_code' => 'required',
            'phone_number' => 'required|regex:/^((?!(0))[0-9]{6,})$/|numeric|min:6',
            'address' => 'required|min:10',
           
        ]);
         if ($validator->fails()) {
            Session::flash('danger',$validator->errors()->first());
                return back()->withErrors($validator)->withInput();
        }else{
        $input = $request->all();
      
             $partner = $this->partner->findOrFail($this->partner->create($input)->id);
             Session::flash('success',"Your Request is sent");
        }
       return redirect(url('/joinpatner'));
    }

   
}