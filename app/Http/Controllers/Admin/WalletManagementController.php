<?php

/**
 * @Author: Abhi Bhatt
 * @Date:   2021-12-30 23:54:14
 * @Last Modified by:   Abhi Bhatt
 * @Last Modified time: 2022-01-02 01:28:42
 */
namespace App\Http\Controllers\Admin;

use App\ProductOrder;
use App\UserWallet;
use App\Helpers\Helper;
use App\Scopes\StatusScope;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use DataTables;

class WalletManagementController extends Controller {
	public function __construct(User $user,ProductOrder $productorder, UserWallet $userwallet){
        parent::__construct();
        $this->user = $user;
        $this->order = $productorder;
        $this->user_wallet = $userwallet;
    }

    public function index()
    {
        if ($this->user->can('view', WalletManagement::class)) {
            return abort(403,'not able to access');
        }
        //$slug =  \Request::segment(2);
        $title = 'Wallet Management';
        return view('admin.pages.wallet-management.index')->with('title',$title);
    }

    public function anyData(Request $request) {
        $data = User::select('id','name','phone_code','phone_number','email','wallet_amount','coin_amount')->where('status','1')->where('user_type','user');
        $data->get();
        return Datatables::of($data)
            /*->addColumn('created_at',function ($user){
                return date('d/m/Y',strtotime($user->created_at));
            })*/
            ->editColumn('phone_number', function($coin){
                return '+'.$coin->phone_code.''.$coin->phone_number;
            })
            ->addColumn('action',function ($coin){
                return '<a href="'.route("wallet-management.wallet-history",$coin->id).'" class="btn btn-success">Wallet History</a><a href="'.route("wallet-management.add-wallet-entry",$coin->id).'" class="btn btn-success">Add Wallet Entry</a><a href="'.route("wallet-management.darbaar-coin-history",$coin->id).'" class="btn btn-success">Darbaar Coin History</a><a href="'.route("wallet-management.add-coin-entry",$coin->id).'" class="btn btn-success">Add Coin Entry</a>';
            })
            /*->rawColumns(['image','action'])*/
            ->make(true);

    }

    public function walletHistory($id){
        if ($this->user->can('viewWallet', WalletManagement::class)) {
            return abort(403,'not able to access');
        }
        $user_id = $id;

      return view('admin.pages.wallet-management.wallethistory',compact('user_id'));

    }

    public function walletHistorydata(Request $request){
 
      $wallet_histories = $this->user_wallet->where('user_id',$request->user_id)->where('wallet_type','amount')->orderBy('created_at','DESC')->get();
       return Datatables::of($wallet_histories)
              ->addColumn('id',function ($wallet_histories){
                return $wallet_histories->id;
             })->addColumn('created_at',function ($wallet_histories){
                return date('d/m/Y', strtotime($wallet_histories->created_at));
             })
              ->editColumn('customer_id',function($wallet_histories){
               $cust_data = $this->user->where('id',$wallet_histories->user_id)->first();
               if(!empty($cust_data)){
                return $cust_data->name;
               }
                return "--";
              })
            ->rawColumns(['id'])
            ->make(true);

    }

    public function coinHistory($id){
        if ($this->user->can('viewCoin', WalletManagement::class)) {
            return abort(403,'not able to access');
        }
        $user_id = $id;

      return view('admin.pages.wallet-management.darbaarCoinHistory',compact('user_id'));

    }

    public function coinHistoryData(Request $request){
 
      $wallet_histories = $this->user_wallet->where('user_id',$request->user_id)->where('wallet_type','coin')->orderBy('created_at','DESC')->get();
       return Datatables::of($wallet_histories)
              ->addColumn('id',function ($wallet_histories){
                return $wallet_histories->id;
             })->addColumn('created_at',function ($wallet_histories){
                return date('d/m/Y', strtotime($wallet_histories->created_at));
             })
              ->editColumn('customer_id',function($wallet_histories){
               $cust_data = $this->user->where('id',$wallet_histories->user_id)->first();
               if(!empty($cust_data)){
                return $cust_data->name;
               }
                return "--";
              })
            ->rawColumns(['id'])
            ->make(true);

    }

    public function addWalletEntry(Request $request,$id) {
        if ($this->user->can('addWallet', WalletManagement::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->user_wallet->rules('POST'));
        if ($request->isMethod('post')) {
            $input = $request->all();
            $transaction_id = "DAR".time();
            $order_id = NULL;
            $description = $input['description'];
            $json_data = json_encode(['user_id'=>$id]);
            $wallet_amount = $input['amount'];
            $type = 'Amount '.$input['transaction_type'].' by Admin';
            Helper::updateCustomerWallet($id,$wallet_amount,$input['transaction_type'],$type,$transaction_id,$description,$json_data,$order_id);
            return redirect('admin/wallet-management');
        }
        return view('admin.pages.wallet-management.add-wallet-entry',compact('id','validator'));
    }

    public function addCoinEntry(Request $request,$id) {
        if ($this->user->can('addCoin', WalletManagement::class)) {
            return abort(403,'not able to access');
        }
        $validator = JsValidatorFacade::make($this->user_wallet->rules('POST'));
        if ($request->isMethod('post')) {
            $input = $request->all();
            $transaction_id = "DAR".time();
            $order_id = NULL;
            $description = $input['description'];
            $json_data = json_encode(['user_id'=>$id]);
            $wallet_amount = $input['amount'];
            $type = 'Amount '.$input['transaction_type'].' by Admin';
            Helper::updateCustomerCoins($id,$wallet_amount,$input['transaction_type'],$type,$transaction_id,$description,$json_data,$order_id);
            return redirect('admin/wallet-management');
        }
        return view('admin.pages.wallet-management.add-coin-entry',compact('id','validator'));
    }
}