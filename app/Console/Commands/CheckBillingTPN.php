<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
class CheckBillingTPN extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkBillingTPN';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Billing Payment Status from STEL Purchase in TPN';

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
        $sales = DB::table('stels_sales')->where('payment_status', 0)->whereNotNull('BILLING_ID')->get();
        /*DB::table('stels_sales')
            ->where('payment_status', 0)
            ->whereNotNull('BILLING_ID')
            ->update(['payment_status' => 1]);*/

        $this->info('checkBillingTPN Command Run successfully!');
        // $this->line('Display this on the screen');
        // $this->error('Something went wrong!');
    }
}
