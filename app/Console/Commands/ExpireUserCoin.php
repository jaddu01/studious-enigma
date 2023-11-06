<?php

namespace App\Console\Commands;

use App\User;
use App\UserWallet;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpireUserCoin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expire:coin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
            DB::beginTransaction();
            function isExpire($date)
            {
                $days =  Carbon::createFromFormat('Y-m-d H:i:s', $date)->diffInDays(now());
        
                if ($days > 30) {
                    return true;
                } else {
                    return false;
                }
            }
        
        
            $user_wallet = UserWallet::where('status', 1)->where('transaction_type', 'CREDIT')
                ->where('wallet_type', 'coin')->get();
            foreach ($user_wallet as $wallet) {
                if (isExpire($wallet->created_at)) {
                    UserWallet::where('id', $wallet->id)->update(['status' => 0]);
                    $user_coin=User::where('id',$wallet->user_id)->value('coin_amount');
                    if($user_coin>=$wallet->amount){
                        User::where('id',$wallet->user_id)->decrement('coin_amount',$wallet->amount);
                    }
                }
            }
            DB::commit();
            return "working";
        
        }
        catch(Exception $e){
            Log::error($e);
            DB::rollBack();
        
        }
    }
}
