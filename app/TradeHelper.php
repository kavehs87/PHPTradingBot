<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/12/18
 * Time: 02:38
 */

namespace App;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TradeHelper
{
    public static function calcPercent($price, $percent)
    {
        return $price - (($price * $percent) / 100);
    }

    public static function getPercent($buy, $current)
    {
        if ($buy == 0 || $current == 0)
            return null;
        return (($current - $buy) * 100) / $current;
    }

    public static function maxPercent($current, $max, $buyPrice)
    {
        $_temp = $max / 100;
        $maxPrice = ($buyPrice * $_temp) + $buyPrice;
        return round(self::getPercent($maxPrice, $current), 2);

//        return (($current - ($buy + ($buy * $max))) * 100) / ($buy + ($buy * $max));
    }

    public static function market2symbol($market)
    {
        if (strpos($market, '-') === false) {
            return $market;
        }
        $parts = explode('-', $market);
        return $parts[1] . $parts[0];
    }


    public static function getRIO(Order $order)
    {
        $pl = $order->getPL(true);
        $quantity = $order->origQty;

        return $quantity * $pl / 100;
    }

    public static function recentlyTradedPairs(Carbon $time)
    {
        $pairs = [];
        $orders = Order::whereHas('sellOrder')
            ->where('created_at', '>=', $time)
            ->get();
        if ($orders->isEmpty())
            return false;
        foreach ($orders as $order) {
            if (!in_array($order->symbol, $pairs)) {
                $pairs[$order->symbol] = [
                    'symbol' => $order->symbol,
                    'avpl' => round($order->getPL(true), 3)
                ];
            } else {
                $pairs[$order->symbol] = [
                    'symbol' => $order->symbol,
                    'avpl' => round($pairs[$order->symbol]['avpl'] + $order->getPL(true), 3)
                ];
            }
        }
        $collection = collect($pairs);
        return $collection->sortBy(function ($pair) {
            return $pair['avpl'];
        });
    }

    public static function getBinance()
    {
        $binanceConfig = Setting::getValue('binance');
        $binance = new \Binance\API($binanceConfig['api'], $binanceConfig['secret']);
        if (isset($binanceConfig['proxyEnabled']) && $binanceConfig['proxyEnabled'] != false) {
            $binance->setProxy([
                'proto' => $binanceConfig['proxy']['proto'],
                'address' => $binanceConfig['proxy']['host'],
                'port' => $binanceConfig['proxy']['port'],
                'username' => $binanceConfig['proxy']['username'],
                'password' => $binanceConfig['proxy']['password'],
            ]);
        }
        return $binance;
    }

    public static function calcUSDT($amount, $symbol)
    {
        if ($price = self::getPrice($symbol . 'USDT')) {
            $usdtPrice = $price;
        } else {
            $btcPrice = self::getPrice('BTCUSDT');
            $symbol2btc = self::getPrice($symbol . 'USDT');

            $usdtPrice = $symbol2btc * $btcPrice;
        }
        return $amount / $usdtPrice;
    }

    public static function getNotions($filter = null)
    {
        $notions = [];
        if (Cache::has('notions')) {
            $notions = Cache::get('notions');
        } else {
            $binance = self::getBinance();
            $exInfo = $binance->exchangeInfo();
            foreach ($exInfo['symbols'] as $symbol) {
                $notions[$symbol['symbol']] = $symbol;
            }
            Cache::put('notions', $notions, now()->addMinutes(30));
        }
        if ($filter) {
            return $notions[$filter];
        }
        return $notions;
    }

    public static function getStepSize($symbol)
    {
        $notion = self::getNotions($symbol);
        if (isset($notion['filters'])) {
            foreach ($notion['filters'] as $filter) {
                if (isset($filter['stepSize']))
                    return $filter['stepSize'];
            }
        }
        return 0.01;
    }

    public static function getTick($symbol)
    {
        $tick = Cache::get($symbol);
        if ($tick == null) {
            // get last data for symbol
            try {
                $price = self::getBinance()->price($symbol);
            } catch (\Exception $e) {
                return false;
                // when signal symbol is not supported by exchange class TODO
            }
            $tick = ['symbol' => $symbol, 'close' => $price];
            Cache::put($symbol, $tick, now()->addSeconds(5));
        }

        return $tick;
    }

    public static function getPrice($symbol)
    {
        $tick = self::getTick($symbol);
        return $tick['close'];
    }

    public static function getSymbols()
    {
        if (Cache::has('symbols')) {
            return Cache::get('symbols');
        }
        $symbols = [];
        try {
            ob_start();
            $binance = $exchangeInfo = self::getBinance();
            $exchangeInfo = $binance->exchangeInfo();
            ob_clean();
            if (empty($exchangeInfo)) {
                return [];
            }
        } catch (\Exception $e) {
            return [];
        }

        foreach ($exchangeInfo['symbols'] as $symbol) {
            $symbols[] = $symbol['symbol'];
        }
        Cache::put('symbols', $symbols, now()->addMinutes(30));
        return $symbols;
    }


    /**
     * @param $command
     * @param $service
     * @param bool $background
     * @return bool
     * @throws \Exception
     */
    public static function systemctl($service, $command, $background = true)
    {

        if ($service == 'all') {
            self::systemctl('ticker', $command);
            self::systemctl('orders', $command);
            self::systemctl('signal', $command);
            sleep(2);
            return true;
        }

        $phpBinary = exec("which php");
        if (!$phpBinary) {
            throw new \Exception('cannot find php binary');
        }

        $cmd = 'cd ' . base_path() . '; ';
        switch ($command) {
            case 'status':
                $cmd .= "echo $(ps aux | grep 'daemon:$service' | grep -v grep | awk '{print $2}')";
                exec($cmd, $output, $return);
                if (isset($output[0]) && $output[0] != null) {
                    return true;
                }
                return false;
            case 'restart':
                $stop = self::systemctl($service, 'stop');
                $start = self::systemctl($service, 'start');
                if ($start && $stop) {
                    return true;
                }
                return false;
            case 'start':
                $cmd .= $phpBinary . ' artisan daemon:' . $service;
                break;
            case 'stop':
                $cmd .= "kill $(ps aux | grep 'daemon:$service' | grep -v grep | awk '{print $2}')";
                break;
        }


        if ($background) {
            $cmd .= ' > /dev/null 2>&1 &';
        }
        $result = exec($cmd, $output, $return);
        return self::systemctl($service, 'status');
    }

    public static function isFavorite($symbol)
    {
        if ($user = Auth::user()) {
            $favorites = $user->favorites;
            $favorites = json_decode($favorites);
            if (in_array($symbol, $favorites)) {
                return true;
            }
        }
        return false;
    }
}