<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BarcodeController extends Controller
{
    public function index(Request $request){
        $title="Barcode Generator";
        return view('admin.pages.barcode-generator.index',compact('title'));
    }
}
