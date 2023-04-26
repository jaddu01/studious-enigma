<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\User;
use App\ProductOrder;
use App\UserWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\App;
use Hash;
use DB;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class WalletController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Wallet Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles redirecting them to your home screen. 
    |
    */


    /**
     * Where to redirect users before login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user,ProductOrder $productorder, UserWallet $userwallet){
        parent::__construct();
        $this->user = $user;
        $this->order = $productorder;
        $this->user_wallet = $userwallet;
    }

    public function mywallet()
    {
        $user = auth()->user();
        $total_order = $this->order->with(['ProductOrderItem'])->where('user_id',$user->id)->count();
        $wallet_histories = $this->user_wallet->where(['user_id' => Auth::user()->id])->where('wallet_type','!=','coin')->orderBy('created_at','DESC')->paginate(100);
        $wallet_amount = Helper::getUpdatedWalletData(Auth::user()->id);
        return view('pages.mywallet',['user' => $user,'total_order' => $total_order,'wallet_histories' => $wallet_histories, 'wallet_amount' => $wallet_amount]);
    }

    public function mycoins()
    {
        $user = auth()->user();
        $total_order = $this->order->with(['ProductOrderItem'])->where('user_id',$user->id)->count();
        $wallet_histories = $this->user_wallet->where(['user_id' => Auth::user()->id])->where('wallet_type','=','coin')->orderBy('created_at','DESC')->paginate(100);
        $wallet_amount = Helper::getUpdatedWalletData(Auth::user()->id);
        $coin_amount = Helper::getUpdatedCoinData(Auth::user()->id);
        return view('pages.mycoins',['user' => $user,'total_order' => $total_order,'wallet_histories' => $wallet_histories, 'wallet_amount' => $wallet_amount, 'coin_amount' => $coin_amount]);
    }
}
