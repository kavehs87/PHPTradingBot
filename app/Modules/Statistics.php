<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/21/18
 * Time: 20:28
 */

namespace App\Modules;


use App\Modules;
use App\Order;
use App\TradeHelper;
use Carbon\Carbon;

class Statistics extends Modules
{
    public static $description = 'shows information about trades';

    public function menus()
    {
        return [
            [
                'route' => 'stats',
                'text' => 'Stats',
                'module' => 'Statistics'
            ]
        ];
    }

    public function statsPage()
    {
        $orders = Order::where('created_at', '>=', Carbon::now()->subDay())
            ->whereHas('sellOrder')
            ->get();

        $totalProfit = 0;
        $totalProfitPercent = 0;
        $profitCount = 0;
        $highestProfit = null;
        $totalLoss = 0;
        $totalLossPercent = 0;
        $lossCount = 0;
        $highestLoss = null;
        if ($orders) {
            foreach ($orders as $order) {
                if ($order->getPL(true) > 0) {
                    // profit
                    $totalProfit += TradeHelper::getRIO($order);
                    $totalProfitPercent += $order->getPL(true);
                    $profitCount++;
                    if (!$highestProfit || $highestProfit->getPL(true) < $order->getPL(true)){
                        $highestProfit = $order;
                    }
                } else {
                    // loss
                    $totalLoss += TradeHelper::getRIO($order);
                    $totalLossPercent -= $order->getPL(true);
                    $lossCount++;
                    if (!$highestLoss || $highestLoss->getPL(true) > $order->getPL(true)){
                        $highestLoss = $order;
                    }
                }
            }
        }


        view()->addNamespace('Statistics', app_path('Modules/Statistics/view'));
        return view('Statistics::stats', [
            'config' => $this->getConfig(),
            'totalLoss' => $totalLoss,
            'totalLossPercent' => $totalLossPercent,
            'lossCount' => $lossCount,
            'highestLoss' => $highestLoss,


            'totalProfit' => $totalProfit,
            'totalProfitPercent' => $totalProfitPercent,
            'profitCount' => $profitCount,
            'highestProfit' => $highestProfit
        ]);
    }
}