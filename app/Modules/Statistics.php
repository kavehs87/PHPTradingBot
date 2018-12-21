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
        $orders = Order::where('created_at','>=',Carbon::now()->subDay())->get();

        if ($orders){
            foreach ($orders as $order) {

            }
        }



        view()->addNamespace('Statistics', app_path('Modules/Statistics/view'));
        return view('Statistics::stats', [
            'config' => $this->getConfig()
        ]);
    }
}