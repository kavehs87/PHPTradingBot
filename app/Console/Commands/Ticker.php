<?php

namespace App\Console\Commands;

use App\Modules;
use App\Price;
use App\Setting;
use App\TradeHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class Ticker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daemon:ticker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listens to thicker web socket';

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
        $binance = TradeHelper::getBinance();
        $tickerType = Setting::getValue('tickerType', 'wss');
        $saveTicker = Setting::getValue('saveTicker', false);
        $enabledModules = Modules::getActiveModules();
        $eligibleModules = [];
        if ($enabledModules) {
            foreach ($enabledModules as $module) {
                $_module = $module->getFactory();
                if (method_exists($_module, 'signalLoop')) {
                    $eligibleModules[] = $_module;
                }
            }
        }
        if ($tickerType == 'full') {
            $this->info('WSS : Full Ticker');
            $binance->ticker(false, function ($api, $symbol, $tick) use ($saveTicker,$eligibleModules) {
                try {
                    if ($saveTicker) {
                        \App\Ticker::create($tick);
                    }
                    Cache::put($tick['symbol'], $tick, now()->addHour(1));
                    $this->onTickEvent($tick,$eligibleModules);
                } catch (\Exception $exception) {
                    $this->alert($exception->getMessage());
                }

                Cache::forever('lastTick', time());
            });

        } else {
            $this->info('WSS : Mini Ticker');
            $binance->miniTicker(function ($api, $ticker) use ($saveTicker,$eligibleModules) {
                try {
                    if ($saveTicker)
                        \App\Ticker::create($ticker);
                    foreach ($ticker as $tick) {
                        Cache::put($tick['symbol'], $tick, now()->addHour(1));
                        $this->onTickEvent($tick,$eligibleModules);
                    }
                } catch (\Exception $exception) {
                    $this->alert($exception->getMessage());
                }

                Cache::forever('lastTick', time());
            });
        }

        unset($binance);

        return 0;
    }

    public function onTickEvent($tick, $eligibleModules)
    {
        foreach ($eligibleModules as $module) {
            $module->onTick($tick);
        }
    }
}
