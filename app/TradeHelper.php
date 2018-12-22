<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/12/18
 * Time: 02:38
 */

namespace App;


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

}