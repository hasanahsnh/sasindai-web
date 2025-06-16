<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Midtrans\PlCallbackController;
use Kreait\Firebase\Contract\Database;

class PollMidtransStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:poll-midtrans-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    protected $database;
    protected $callbackController;
    public function __construct(Database $db)
    {
        parent::__construct();
        $this->database = $db;
        $this->callbackController = new PlCallbackController($db);
    }

    public function handle()
    {
        $orders = $this->database->getReference('orders')->getValue();
        if (!$orders) {
            Log::info('Tidak ada order untuk dipolling');
            return;
        }

        foreach ($orders as $key => $order) {
            $orderId = $order['order_id'] ?? null;
            $status = $order['status'] ?? null;

            if (!$orderId) continue;

            if (in_array($status, ['pending', 'expired', 'canceled'])) {
                Log::info("Polling order: $orderId");
                $this->callbackController->checkStatusFromMidtrans($orderId);
            }
        }
    }
}
