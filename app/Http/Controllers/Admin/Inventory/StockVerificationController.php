<?php

namespace App\Http\Controllers\Admin\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StockVerificationController extends Controller
{
    public function index(){
        return view('admin.pages.inventory.stock_verification.index');
    }

    public function createStockVerification(){
        $currentSection = 'sidebarInventorySection';
        $currentPage ='sidebarStockVerification';
        return view('admin.pages.inventory.stock_verification.create',compact('currentSection','currentPage'));
    }
}
