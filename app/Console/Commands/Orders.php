<?php

namespace App\Console\Commands;

use App\Order;
use Illuminate\Console\Command;

class Orders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daemon:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processes Orders';
    private $sleepInterval = 1;

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
        while(true){

            $openOrders = Order::getOpenPositions(true);
            foreach ($openOrders as $order){
                $order->updateState();
            }

            sleep($this->sleepInterval);
        }
    }
}
