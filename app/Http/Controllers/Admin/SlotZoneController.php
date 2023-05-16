<?php

namespace App\Http\Controllers\Admin;


use App\City;
use App\Category;
use App\DeliveryDay;
use App\DeliveryTime;
use App\Helpers\Helper;
use App\Notifications\OrderStatus;
use App\ProductOrderItem;
use App\Scopes\StatusScope;
use App\ProductOrder;
use App\User;
use App\VendorProduct;
use App\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;


class SlotZoneController extends Controller
{
    protected $zone;
    protected $user;
    protected $method;
    function __construct(Request $request,Zone $zone,User $user)
    {
        parent::__construct();
        $this->zone=$zone;
        $this->user=$user;
        $this->method=$request->method();
    }


    public function index(Request $request)
    {
        if ($this->user->can('view', Zone::class)) {
            return abort(403,'not able to access');
        }
        if($request->ajax()){

            $dataArray = [];
            $current_date = $request->current_date;
            $today_date = $current_date;
            $to_day = Carbon::createFromFormat('Y-m-d',$current_date)->format('l');

            $tomorrow_date = Carbon::createFromFormat('Y-m-d',$current_date)->addDay();
            $tomorrow_day = $tomorrow_date->format('l');
            $next_tomorrow_date = Carbon::createFromFormat('Y-m-d',$current_date)->addDays(2);
            $next_tomorrow_day = $next_tomorrow_date->format('l');

            $zone = $this->zone->find($request->zone_id);
            $zonePoints = $this->zone->selectRaw(' AsText(point) as points')->withoutGlobalScope(StatusScope::class)->where('id','$request->zone_id')->get();

            $today_data = $zone->weekPackage->$to_day->getSlotTimes();
            $tomorrow_data = $zone->weekPackage->$tomorrow_day->getSlotTimes();
            $next_tomorrow_data = $zone->weekPackage->$next_tomorrow_day->getSlotTimes();

            $dataArray=[
                ['day'=>$to_day,'date'=>$today_date,'data'=>$today_data],
                ['day'=>$tomorrow_day,'date'=>$tomorrow_date->format('Y-m-d'),'data'=>$tomorrow_data],
                ['day'=>$next_tomorrow_day,'date'=>$next_tomorrow_date->format('Y-m-d'),'data'=>$next_tomorrow_data]
            ];


            $data =   view('admin/pages/load-slot-zone/ajax/modify-delivery-date-or-slot',compact(['dataArray']))->render();


            return response()->json([
                'data'=>$data
            ],200);
        }
        $zones =$this->zone->get()->pluck('name','id');
        $zonePoints = $this->zone->selectRaw(' AsText(point) as points')->withoutGlobalScope(StatusScope::class)->get();
        return view('admin/pages/load-slot-zone/index',compact(['zones','zonePoints']));
    }

}
