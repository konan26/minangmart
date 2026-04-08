<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CancelExpiredOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically cancel orders that have exceeded the 10-minute payment window.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredOrders = \App\Models\Order::where('payment_status', 'awaiting_payment')
            ->where('created_at', '<=', now()->subMinutes(\App\Models\Order::PAYMENT_TIMEOUT_MINUTES))
            ->get();

        $count = $expiredOrders->count();

        foreach ($expiredOrders as $order) {
            $order->markAsCancelled();
        }

        if ($count > 0) {
            $this->info("Successfully cancelled {$count} expired orders and restored stock.");
        }
    }
}
