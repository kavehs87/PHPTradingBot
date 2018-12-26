<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/12/18
 * Time: 02:38
 */

namespace App;


use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class TradeHelper
{
    public static function calcPercent($price, $percent)
    {
        return $price - (($price * $percent) / 100);
    }

    public static function getPercent($buy, $current)
    {
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
        $prices = Cache::get('prices', []);
        $prices = json_decode($prices, true);
        if (isset($prices[$symbol . 'USDT'])) {
            $usdtPrice = $prices[$symbol . 'USDT'];
        } else {
            $btcPrice = $prices['BTCUSDT'];
            $symbol2btc = $prices[$symbol . 'BTC'];

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
        if (isset($notion['filters'])){
            foreach ($notion['filters'] as $filter) {
                if (isset($filter['stepSize']))
                    return $filter['stepSize'];
            }
        }
        return 0.01;
    }

}