<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/18/18
 * Time: 01:06
 */

namespace App\Modules;


use App\Modules;
use App\Order;
use App\Price;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Respawn extends Modules
{
    public static $description = 'Monitors failed orders for possible pullback';

    public function menus()
    {
        return [[
            'route' => 'respawn',
            'text' => 'Monitor Past',
            'module' => 'Respawn'
        ]
            ];
    }


    public function RespawnPage(Request $request)
    {
        // post
        if ($request->isMethod('post')) {
            $data = $request->except('_token');
            $data['symbols'] = $this->getConfig('symbols');
            $this->setConfig($data);
            return redirect()->back();
        }

//        $this->updatePrice(json_decode(Cache::get('prices'),true));

        // get
        view()->addNamespace('Respawn', app_path('Modules/Respawn/view'));
        return view('Respawn::layout', [
            'config' => $this->getConfig()
        ]);
    }

    public function updatePrice($prices)
    {
        $config = $this->getConfig();
        $symbols = $config['symbols'] ?? [];


        if (!isset($config['days']))
            return false;
        $orders = Order::where('created_at', '>', Carbon::now()->subDays($config['days']))->get();
        $orderPairs = $orders->unique('symbol');


        foreach ($orderPairs as $order) {
            $symbol = $order->symbol;
            $keys = isset($symbols[$symbol]) ? array_keys($symbols[$symbol]) : [];
            $now = Carbon::now();
            if (empty($keys)) {
                // first time
                $symbols[$symbol][time()] = $prices[$symbol];
            } else {
                $lastTime = max($keys);
                $lastTime = Carbon::createFromTimestamp($lastTime);
                $diffInMinutes = $lastTime->diffInMinutes(Carbon::now());
                if ($diffInMinutes >= $config['interval']) {
                    $symbols[$symbol][time()] = $prices[$symbol];
                }
            }

            if (count($symbols[$symbol]) >= $config['maxSamples']) {
                unset($symbols[$symbol][min($keys)]);
            }

            $pairs[] = $symbol;
        }

        foreach ($symbols as $symbol => $data) {
            if (!in_array($symbol, $pairs)) {
                unset($symbols[$symbol]);
            }
        }


//        $symbols = [];
        $config['symbols'] = $symbols;
        $this->setConfig($config);
    }


    public function onTick($prices)
    {
        $this->updatePrice($prices);
    }
}