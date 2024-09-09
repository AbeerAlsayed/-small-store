<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteOldDeliveredOrders extends Command
{
    protected $signature = 'orders:delete-old-delivered';
    protected $description = 'Delete delivered orders older than 3 days';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $threeDaysAgo = Carbon::now()->subDays(3);
        DB::table('orders')
            ->where('status', 'delivered')
            ->where('updated_at', '<=', $threeDaysAgo)
            ->delete();

        $this->info('Old delivered orders deleted successfully.');
    }
}
