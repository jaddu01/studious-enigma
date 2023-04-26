<?php

/**
 * @Author: abhi
 * @Date:   2021-09-08 23:46:39
 * @Last Modified by:   abhi
 * @Last Modified time: 2021-09-16 17:42:21
 */
namespace App\Http\Controllers\Admin\Pos;

use App\Purchase;
use App\BrandTranslation;
use App\Brand;
use App\Expenses;
use App\ProductOrder;
use App\VendorProduct;
use App\Helpers\Helper;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class ReportsController extends Controller {
	protected $model;
    protected $user;
    protected $method;
    protected $sales;
    protected $purchases;
    protected $expenses;
    
    function __construct(Request $request,Purchase $purchases,User $user,ProductOrder $sales,Expenses $expenses)
    {
        parent::__construct();
        $this->sales=$sales;
        $this->purchases = $purchases;
        $this->expenses = $expenses;
        $this->user=$user;
        $this->method=$request->method();
    }

    public function sales()
    {
        if ($this->user->can('sales', Reports::class)) {
            return abort(403,'not able to access');
        }
        $title = 'Sales Report';
        return view('admin/pages/pos/reports/sales')->with('title',$title);
    }

    public function salesData(Request $request) {
        $date = $request->date;
        if(isset($date) && !empty($date)) {
            $date = $date;
        } else {
            $date = date('Y-m');
        }

        $sales = ProductOrder::selectRaw("SUM(total_amount) as  total_amount,DATE_FORMAT(created_at, '%Y-%m-%d') date")
        ->whereRaw('SUBSTR(created_at,1,7) = "'.$date.'"')->groupBy('date')->orderBy('date','asc')
        ->get();

        $total = ProductOrder::selectRaw("SUM(total_amount) as  total_amount")
        ->whereRaw('SUBSTR(created_at,1,7) = "'.$date.'"')
        ->first();
        $result = ['data'=>$sales,'total'=>$total];
        return response()->json($result);
    }

    public function expenses()
    {
        if ($this->user->can('expenses', Reports::class)) {
            return abort(403,'not able to access');
        }
        $title = 'Expenses Report';
        return view('admin/pages/pos/reports/expenses')->with('title',$title);
    }

    public function expensesData(Request $request) {
        $date = $request->date;
        if(isset($date) && !empty($date)) {
            $date = $date;
        } else {
            $date = date('Y-m');
        }

        $expenses = Expenses::select("title","description","date","price")
        ->whereRaw('SUBSTR(date,1,7) = "'.$date.'"')->orderBy('date','asc')
        ->get();
        if(isset($expenses) && !empty($expenses)) {
            foreach ($expenses as $key => $value) {
                $expenses[$key]['date'] = date('d M Y', strtotime($value['date']));
            }
        }
        $total = Expenses::selectRaw("SUM(price) as  total_amount")
        ->whereRaw('SUBSTR(date,1,7) = "'.$date.'"')
        ->first();
        $result = ['data'=>$expenses,'total'=>$total];
        return response()->json($result);
    }

    public function expensesData_bkp(Request $request) {
        $date = $request->date;
        if(isset($date) && !empty($date)) {
            $date = $date;
        } else {
            $date = date('Y-m');
        }

        $expenses = Expenses::selectRaw("SUM(price) as  total_amount,DATE_FORMAT(date, '%Y-%m-%d') date")
        ->whereRaw('SUBSTR(date,1,7) = "'.$date.'"')->groupBy('date')->orderBy('date','asc')
        ->get();
        if(isset($expenses) && !empty($expenses)) {
            foreach ($expenses as $key => $value) {
                $details[$key] = Expenses::select('title','description','price','date')->whereRaw('date = "'.$value->date.'"')
                ->orderBy('id','asc')
                ->get();
                $expenses[$key]['description'] = $details[$key];
                /*if(isset($details[$key]) && !empty($details[$key])) {
                    foreach($details[$key] as $key1=>$value1) {
                        $expenses[$key]['description'][$key1] = $expenses[$key]['description'].'<br/>'.$value1['description']
                    }
                }
                $expenses[$key]['description'] = */
                /*echo '<pre>';
                print_r($details[$key]);
                echo '</pre>';*/
            }
        }

        $total = Expenses::selectRaw("SUM(price) as  total_amount")
        ->whereRaw('SUBSTR(date,1,7) = "'.$date.'"')
        ->first();
        $result = ['data'=>$expenses,'total'=>$total];
        return response()->json($result);
    }

    public function purchase()
    {
        if ($this->user->can('purchase', Reports::class)) {
            return abort(403,'not able to access');
        }
        $title = 'Purchase Report';
        return view('admin/pages/pos/reports/purchases')->with('title',$title);
    }

    public function purchaseData(Request $request) {
        $date = $request->date;
        if(isset($date) && !empty($date)) {
            $date = $date;
        } else {
            $date = date('Y-m');
        }

        $purchases = Purchase::select("purchases.quantity","purchases.price","purchases.date","suppliers.company_name as supplier","users.name as vendor","product_translations.name as product","brand_translations.name as brand")->leftJoin('brand_translations','purchases.brand_id','=','brand_translations.id')->leftJoin('product_translations','purchases.product_id','=','product_translations.product_id')->leftJoin('suppliers','purchases.supplier_id','=','suppliers.id')->leftJoin('users','purchases.vendor_id','=','users.id')
        ->whereRaw('SUBSTR(purchases.date,1,7) = "'.$date.'"')->orderBy('purchases.date','asc')
        ->get();
        if(isset($purchases) && !empty($purchases)) {
            foreach ($purchases as $key => $value) {
                $purchases[$key]['date'] = date('d M Y', strtotime($value['date']));
            }
        }

        $total = Purchase::selectRaw("SUM(price) as  total_amount")
        ->whereRaw('SUBSTR(date,1,7) = "'.$date.'"')
        ->first();
        $result = ['data'=>$purchases,'total'=>$total];
        return response()->json($result);
    }

    public function purchaseData_bkp(Request $request) {
        $date = $request->date;
        if(isset($date) && !empty($date)) {
            $date = $date;
        } else {
            $date = date('Y-m');
        }

        $purchases = Purchase::selectRaw("SUM(price) as  total_amount,DATE_FORMAT(date, '%Y-%m-%d') date")
        ->whereRaw('SUBSTR(date,1,7) = "'.$date.'"')->groupBy('date')->orderBy('date','asc')
        ->get();

        $total = Purchase::selectRaw("SUM(price) as  total_amount")
        ->whereRaw('SUBSTR(date,1,7) = "'.$date.'"')
        ->first();
        $result = ['data'=>$purchases,'total'=>$total];
        return response()->json($result);
    }
}