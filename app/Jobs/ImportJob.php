<?php

namespace App\Jobs;

use App\MeasurementClass;
use App\Product;
use DB;
use Excel;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Log;

class ImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->file = request()->file('import_file');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            // dd($this->file);
            $path = $this->file[0]->getRealPath();
            $datas = Excel::load($path)->get()->toArray();
           
           if(count($datas) > 0){
                foreach ($datas as $data) {
                    $input = [];
                    $catid=array();
                    $cates = explode(', ',$data['category']);
                    foreach ($cates as $key=>$cate){
                        $catid[$key]=$cate;
                    }
                    $input['category_id'] = $catid;
                    $input['vendor_id'] = auth('admin')->user()->id ?? 1;
                    
                    $input['name:en'] = $data['name'];
                    
                    $input['print_name:en'] = $data['print_name'];
                    
                    $input['description:en'] = $data['description'];
                    
                    $input['keywords:en'] = $data['keywords'];

                    $input['is_returnable'] = $data['returnable'];
                    
                    $input['status'] = "1";
                    
                    $input['brand_id'] = $data['brand'];
                    
                    // $data['disclaimer:en'] = $data['disclaimer'];
                    // dd($data);
                    $input['self_life:en'] = $data['self_life'];
                    $input['manufacture_details:en'] = $data['manufacture_details'];
                    $input['marketed_by:en'] = $data['marketed_by'];
                    $related_products = explode(',',$data['related_products']);
                    foreach ($related_products as $key=>$related_product){
                        $related_product_id[$key]=$related_product;
                    }
                    $input['related_products'] = $related_product_id;
                    $input['barcode'] = $data['barcode'];
                    $input['hsn_code'] = $data['hsn_code'];
                    $input['sku_code'] = $data['sku_code'];
                    $input['max_order'] = $data['max_order'];
                    $input['measurement_class'] = $data['measurement_class'];
                    $input['measurement_value'] = $data['measurement_value'];
                    $input['price'] = $data['best_price'];
                    $input['gst'] = $data['gst'];
                    $input['qty'] = $data['qty'];
                    $input['offer_id'] = $data['offer_id'];
                    $input['per_order'] = $data['per_order'];
                    $input['best_price'] = $data['price'];
                    $input['memebership_p_price'] = $data['memebership_p_price'];
                    $input['purchase_price'] = $data['purchase_price'];
                    // dd($input);
                    
                    $product = Product::where('sku_code', $input['sku_code'])->first();
                    // dd($product);
                    if ($product) {
                        $product->update($input);

                        //update vendor product
                        $vendor_product = DB::table('vendor_products')->where('product_id', $product->id)->first();
                        if ($vendor_product) {
                            DB::table('vendor_products')->where('product_id', $product->id)->update(['price' => $input['best_price'], 'memebership_p_price' => $input['memebership_p_price'], 'qty' => $input['qty'], 'offer_id' => $input['offer_id'], 'per_order' => $input['per_order'],'best_price' => $input['price']]);
                        } else {
                            DB::table('vendor_products')->insert(['vendor_id' => $input['vendor_id'], 'product_id' => $product->id, 'price' => $input['best_price'], 'memebership_p_price' => $input['memebership_p_price'], 'qty' => $input['qty'], 'offer_id' => $input['offer_id'], 'per_order' => $input['per_order'],'best_price' => $input['price']]);
                        }
                    } else {
                        $product = Product::create($input);

                        //create vendor product
                        DB::table('vendor_products')->insert(['vendor_id' => $input['vendor_id'], 'product_id' => $product->id, 'price' => $input['best_price'], 'memebership_p_price' => $input['memebership_p_price'], 'qty' => $input['qty'], 'offer_id' => $input['offer_id'], 'per_order' => $input['per_order'],'best_price' => $input['price']]);
                    }
                }
            }
            
        }catch(\Maatwebsite\Excel\Validators\ValidationException $e){
            Log::info($e->getMessage());
            $failures = $e->failures();
            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
                dd($failure);
            }
        }
       
    }

    public function failed(Exception $e)
    {
        Log::info('Job failed');
        Log::info($e->getMessage());
        return $e->getMessage();
    }
}
