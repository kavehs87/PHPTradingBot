<?php

namespace App\Console\Commands;

use App\Modules;
use App\Price;
use App\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Prices extends Command
{
    protected $sleepInterval = 10;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daemon:price';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the ticker Daemon';

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
     * @throws \Exception
     */
    public function handle()
    {
        $binanceConfig = Setting::getValue('binance');
        $binance = new \Binance\API($binanceConfig['api'], $binanceConfig['secret']);
        if (isset($binanceConfig['proxyEnabled']) && $binanceConfig['proxyEnabled'] != false){
            $binance->setProxy([
                'proto' => $binanceConfig['proxy']['proto'],
                'address' => $binanceConfig['proxy']['host'],
                'port' => $binanceConfig['proxy']['port'],
                'username' => $binanceConfig['proxy']['username'],
                'password' => $binanceConfig['proxy']['password'],
            ]);
        }
        while (true) {
            try {
                $prices = $binance->prices();
//                $data = null;
//                foreach ($prices as $symbol => $price) {
//                    $data[] = [
//                        'symbol' => $symbol,
//                        'price' => $price,
//                        'created_at' => Carbon::now()->toDateTimeString()
//                    ];
//                }
                Cache::put('prices', json_encode($prices), Carbon::now()->addSeconds($this->sleepInterval + 5));

                /*
                 * Module Signals received hook
                 */
                $activeModules = Modules::getActiveModules();
                if ($activeModules) {
                    foreach ($activeModules as $module) {
                        $module->getFactory()->onTick($prices);
                    }
                }

//                Price::insert($data);
            } catch (\Exception $exception) {
                Log::alert($exception->getMessage());
                unset($exception);
            }

            sleep($this->sleepInterval);
        }

        unset($binance);

        return 0;
    }
}
