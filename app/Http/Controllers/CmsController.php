<?php

namespace App\Http\Controllers;

use App\Cms;
use App\ProductOrder;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class CmsController extends Controller
{

    /**
     * Where to redirect users before login.
     *
     * @var string
     */
     protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Cms $cms,ProductOrder $order)
    {
           $this->cms=$cms;
           $this->order = $order;
      //     $this->middleware('guest')->except('logout');
    }

    public function privacypolicy()
    {
       $data =  $this->cms->where('name','privacy-policy')->first();
       $pageName = "Privacy Policy";
        $user = auth()->user();
        if(Auth::user()){
        $total_order = $this->order->where('user_id',$user->id)->count();
        }else{   $total_order = 0; }
        return view('pages.cmspage')->with('data',$data->description)->with(['user' => $user,'total_order'=>$total_order,'pageName'=>$pageName]);
    }

    public function faq()
    {
        $data =  $this->cms->where('name','faq')->first(); 
        $pageName = "FAQ's";
        $user = auth()->user();
         if(Auth::user()){
        $total_order = $this->order->where('user_id',$user->id)->count();
        }else{   $total_order = 0; }
        return view('pages.faq')->with('data',$data->description)->with(['user' => $user,'total_order'=>$total_order,'pageName'=>$pageName]);
    }

    public function aboutus()
    {
       $data =  $this->cms->where('name','about-us')->first();
       $pageName = "About DARBAAR MART";
        $user = auth()->user();
        if(Auth::user()){
        $total_order = $this->order->where('user_id',$user->id)->count();
        }else{   $total_order = 0; }
        return view('pages.cmspage')->with('data',$data->description)->with(['user' => $user,'total_order'=>$total_order,'pageName'=>$pageName]);
    }

    public function contactus()
    {
       $data =  $this->cms->where('name','contact-us')->first();
       $pageName = "Contact Us";
        $user = auth()->user();
       if(Auth::user()){
        $total_order = $this->order->where('user_id',$user->id)->count();
        }else{   $total_order = 0; }
        return view('pages.cmspage')->with('data',$data->description)->with(['user' => $user,'total_order'=>$total_order,'pageName'=>$pageName]);
    }

    public function termsandcondition()
    {
       $data =  $this->cms->where('name','terms-and-condition')->first();
       $pageName = "Terms And Condition";
        $user = auth()->user();
        if(Auth::user()){
        $total_order = $this->order->where('user_id',$user->id)->count();
        }else{   $total_order = 0; }
        return view('pages.cmspage')->with('data',$data->description)->with(['user' => $user,'total_order'=>$total_order,'pageName'=>$pageName]);
    }
    public function support()
    {
       return view('pages.support');
    }
}
