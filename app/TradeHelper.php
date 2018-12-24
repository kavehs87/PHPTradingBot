<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/12/18
 * Time: 02:38
 */

namespace App;



use Carbon\Carbon;

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
            if (!in_array($order->symbol,$pairs)){
                $pairs[$order->symbol] = [
                    'symbol' => $order->symbol,
                    'avpl' => round($order->getPL(true),3)
                ];
            }
            else {
                $pairs[$order->symbol] = [
                    'symbol' => $order->symbol,
                    'avpl' => round($pairs[$order->symbol]['avpl'] + $order->getPL(true),3)
                ];
            }
        }
        $collection = collect($pairs);
        return $collection->sortBy(function($pair)
        {
            return $pair['avpl'];
        });
    }

}