<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    public function inventoryList(){
        return view('admin.pages.inventory.index');
    }

    public function stockVerification(){

    }

}
