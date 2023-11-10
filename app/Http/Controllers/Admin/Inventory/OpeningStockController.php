<?php

namespace App\Http\Controllers\Admin\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\VendorProduct;
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
        $query = Product::select('*',DB::raw('(select name from product_translations where product_translations.product_id= products.id limit 1 ) as product_name'));
        $recordsTotal = $query->count();
        $recordsFiltered = $query->count();

        if (isset($search['value'])) {
      
            $query->whereTranslationLike('name','%'.$search['value'].'%');
            };
    
        // $data = $query
        //     ->orderBy($order_by, $order_dir)->skip($skip)->take($take)->get();
            $data = $query->skip($skip)->take($take)->orderByRaw("$order_by $order_dir")->get();

        $i = 1;
        foreach ($data as &$d) {
            $d->sr_no = $i;
            $d->price = $d->price;
            $d->date=Carbon::parse($d->created_at)->format('d-m-Y');
            $d->update_date=Carbon::parse($d->updated_at)->format('d-m-y');
            $i = $i+1;
            $d->action="<button class='btn btn-warning editBtn' product='".$d->product_name."' barcode ='".$d->barcode."'
             purchase-price='".$d->purchase_price."' sku-code='".$d->sku_code."' product-id='".$d->id."' price='".$d->price."' selling-price='".$d->best_price."' qty='".$d->qty."'><i class='fa fa-pencil'></i></button>";
        }

        return [
            "draw" => request('draw'),
            "recordsTotal" => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            "data" => $data,
        ];
    }

    public function updateStock(Request $request){
        try {
            
            DB::beginTransaction();
            if($request->barcode!=''){
                Product::where('id',$request->product_id)->update([
                    'barcode'=>$request->barcode,
                    'qty'=>$request->qty,
                    'purchase_price'=>$request->purchase_price,
                    'best_price'=>$request->best_price,
                    'price'=>$request->price,
                ]);
            }
          Product::where('id',$request->product_id)->update([
                'qty'=>$request->qty,
                'purchase_price'=>$request->purchase_price,
                'best_price'=>$request->best_price,
                'price'=>$request->price,
            ]);
            

          VendorProduct::where('product_id',$request->product_id)->update([
                'best_price'=>$request->best_price,
                'price'=>$request->price,
                'qty'=>$request->qty,
            ]);
            DB::commit();
          
            return response()->json([
                'msg'=>'Updated Successfully !'
            ]);


        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error'=>$e->getMessage()
            ]);
        }
    }

    
}
