<?php

namespace App\Http\Controllers\Admin\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OpeningStockController extends Controller
{
    public function index(){
        $currentSection = 'sidebarInventorySection';
        $currentPage ='sidebarOpeningStock';
        return view('admin.pages.inventory.openning_stock.index',compact('currentSection','currentPage'));
    }
}
