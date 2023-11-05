<?php

namespace App\Http\Controllers\Admin\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OpeningStockController extends Controller
{
    public function index()
    {
        $currentSection = 'sidebarInventorySection';
        $currentPage = 'sidebarOpeningStock';
        return view('admin.pages.inventory.opening_stock.index', compact('currentSection', 'currentPage'));
    }

    public function list(Request $request)
    {
        $_order = request('order');
        $_columns = request('columns');
        $order_by = (($_columns[$_order[0]['column']]['name']) == 'sr_no' ? 'id' : $_columns[$_order[0]['column']]['name']);
        // dd($order_by);
        $order_dir = $_order[0]['dir'];
        $search = request('search');
        $skip = request('start');
        $take = request('length');
        $query = Product::select('*',DB::raw('(select name from product_translations where product_translations.product_id= products.id ) as product_name'));

        $recordsTotal = $query->count();


        if (isset($search['value'])) {
      
            $query->whereTranslationLike('name','%'.$search['value'].'%');
            };
        


        $recordsFiltered = $query->count();

        // $data = $query
        //     ->orderBy($order_by, $order_dir)->skip($skip)->take($take)->get();
            $data = $query->skip($skip)->take($take)->orderByRaw("$order_by $order_dir")->get();

        $i = 1;
        foreach ($data as &$d) {
            $d->sr_no = $i;
            $d->price = $d->price;
            // $d->qty = $d->qty;
            
            $d->date=Carbon::parse($d->created_at)->format('d-m-Y');
            $i = $i+1;
            $d->action="<button class='btn btn-warning editBtn'><i class='fa fa-pencil'></i></button>";
        }

        return [
            "draw" => request('draw'),
            "recordsTotal" => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            "data" => $data,
        ];
    }
}
