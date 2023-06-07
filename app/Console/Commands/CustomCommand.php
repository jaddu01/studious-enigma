<?php

namespace App\Console\Commands;

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
        DB::statement("ALTER TABLE `products` 
        ADD COLUMN `price` DECIMAL(10,2) NOT NULL AFTER `status`, 
        ADD COLUMN `qty` INT(11) NOT NULL AFTER `price`, 
        ADD COLUMN `offer_id` INT(11) NULL AFTER `qty`, 
        ADD COLUMN `per_order` INT(11) NULL AFTER `offer_id`, 
        ADD COLUMN `best_price` DECIMAL(10,2) NULL AFTER `per_order`, 
        ADD COLUMN `memebership_p_price` DECIMAL(10,2) NULL AFTER `best_price`");
    }
        // DB::statement("ALTER TABLE `products` ADD COLUMN `price` DECIMAL(10,2) NOT NULL AFTER `measurement_value`, ADD COLUMN `discount` DECIMAL(10,2) NOT NULL AFTER `price`, ADD COLUMN `discount_type` ENUM('percentage','amount') NOT NULL AFTER `discount`, ADD COLUMN `discount_start_date` DATE NULL AFTER `discount_type`, ADD COLUMN `discount_end_date` DATE NULL AFTER `discount_start_date`, ADD COLUMN `discount_description` TEXT NULL AFTER `discount_end_date`, ADD COLUMN `discount_conditions` TEXT NULL AFTER `discount_description`, ADD COLUMN `discount_conditions_type` ENUM('all','any') NOT NULL AFTER `discount_conditions`, ADD COLUMN `discount_conditions_value` DECIMAL(10,2) NOT NULL AFTER `discount_conditions_type`, ADD COLUMN `discount_conditions_value_type` ENUM('percentage','amount') NOT NULL AFTER `discount_conditions_value`, ADD COLUMN `discount_conditions_value_amount` DECIMAL(10,2) NOT NULL AFTER `discount_conditions_value_type`, ADD COLUMN `discount_conditions_value_percentage` DECIMAL(10,2) NOT NULL AFTER `discount_conditions_value_amount`;");
    // }
}
