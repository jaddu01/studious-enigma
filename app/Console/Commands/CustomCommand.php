<?php

namespace App\Console\Commands;

use App\Product;
use App\VendorProduct;
use DB;
use Illuminate\Console\Command;

class CustomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:query';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Custom query to run';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try{
            Product::query()->chunk(100, function($products){
                foreach($products as $product){
                    //insert into vendor products
                    $this->info('Product ID: '.$product->id);
                    VendorProduct::updateOrCreate(['user_id' => $product->vendor_id, 'product_id' => $product->id], [
                        'user_id' => $product->vendor_id,
                        'product_id' => $product->id,
                        'qty' => $product->qty,
                        'price' => $product->price,
                        'best_price' => $product->price,
                        'per_order' => $product->per_order,
                        'memebership_p_price' => $product->memebership_p_price,
                        'offer_id' => $product->offer_id,
                        'status' => "1",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $this->info('Product ID: '.$product->id.' inserted');
                }
            });
        }catch(\Exception $e){
            dd($e);
        }
    }
        // DB::statement("ALTER TABLE `products` ADD COLUMN `price` DECIMAL(10,2) NOT NULL AFTER `measurement_value`, ADD COLUMN `discount` DECIMAL(10,2) NOT NULL AFTER `price`, ADD COLUMN `discount_type` ENUM('percentage','amount') NOT NULL AFTER `discount`, ADD COLUMN `discount_start_date` DATE NULL AFTER `discount_type`, ADD COLUMN `discount_end_date` DATE NULL AFTER `discount_start_date`, ADD COLUMN `discount_description` TEXT NULL AFTER `discount_end_date`, ADD COLUMN `discount_conditions` TEXT NULL AFTER `discount_description`, ADD COLUMN `discount_conditions_type` ENUM('all','any') NOT NULL AFTER `discount_conditions`, ADD COLUMN `discount_conditions_value` DECIMAL(10,2) NOT NULL AFTER `discount_conditions_type`, ADD COLUMN `discount_conditions_value_type` ENUM('percentage','amount') NOT NULL AFTER `discount_conditions_value`, ADD COLUMN `discount_conditions_value_amount` DECIMAL(10,2) NOT NULL AFTER `discount_conditions_value_type`, ADD COLUMN `discount_conditions_value_percentage` DECIMAL(10,2) NOT NULL AFTER `discount_conditions_value_amount`;");
    // }
}
