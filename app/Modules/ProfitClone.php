<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/14/18
 * Time: 02:34
 */

namespace App\Modules;


use App\Modules;
use App\Order;
use App\TradeHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProfitClone extends Modules
{

    public static $description = 'Makes new buy order if specific rules met';


    public function menus()
    {
        return [
            [
                'route' => 'profitClonerLog',
                'text' => 'Profit Cloner',
                'module' => $this->class
            ]
        ];
    }

    public function onTick($prices)
    {
        $config = $this->getConfig();
        if ($config == null)
            return false;
        $openOrders = Order::getOpenPositions(true);
        if ($openOrders->isNotEmpty()) {
            foreach ($openOrders as $order) {
                if ($order->getPL() > $this->getConfig('tpTrigger')) {
                    $cloned = $this->getConfig('cloned');
                    if (!$cloned)
                        $cloned = [];
                    if (!in_array($order->id, $cloned)) {
                        $config = $this->getConfig();
                        $config['cloned'][] = $order->id;
                        $this->setConfig($config);
                        $newOrderId = Order::buy($order->symbol, TradeHelper::calcPercent($order->origQty, $this->getConfig('amountPercent')));
                    }
                }
            }
        }
    }

    protected function getOrderHash($order)
    {
        return md5($order->symbol);
    }

    public function profitClonerLogPage(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->except('_token');
            $config = $this->getConfig();
            $config = array_merge($config, $data);
            $this->setConfig($config);
        }

        $return = $this->configForm();

        $return .= '<div class="col-12"></div>';

        $return .= '<hr/>';
        if ($this->positions() != null) {
            $return .= '<div class="col-12"><h3>Cloned orders</h3></div>';

            $return .= '<table class="table table-hover table-responsive col-12">
            <thead>
            <tr>
                <td>Symbol</td>
                <td>Buy</td>
                <td>P/L</td>
                <td>Qty</td>
                <td>duration</td>
                <th>TP</th>
                <th>SL</th>
                <th>TTP</th>
                <th>TSL</th>
                <th>max</th>
                <th></th>
            </tr>
            </thead>
            <tbody>';

            if (!empty($this->positions())) {
                foreach ($this->positions() as $order) {
                    $return .= '
                <tr>
                    <td>' . $order->symbol . '</td>
                    <td>' . $order->price . '</td>
                    <td class="';
                    if ($order->getPL(true) > 0) {
                        $return .= 'bg-success';
                    } else {
                        $return .= 'bg-danger';
                    }
                    $return .= '">
                        ' . round($order->getPL(true), 3) . '%
                    </td>
                    <td>' . $order->origQty . '</td>
                    <td>' . $order->getTimeFrame() . '</td>
                    <td>' . $order->takeProfit . '%</td>
                    <td>' . $order->stopLoss . '%</td>
                    <td>' . $order->trailingTakeProfit . '%</td>
                    <td>' . $order->trailingStopLoss . '%</td>
                    <td>' . $order->maxFloated . '</td>
                    <td>
                        <a target="_blank" href="https://www.tradingview.com/chart/?symbol=BINANCE%3A' . $order->symbol . '" class="btn btn-default">TradingView</a>
                    </td>

                </tr>';
                }
            }
            $return .= '</tbody>
        </table>';
        }

        $return .= '<hr/>';


        return $return;
    }

    protected function positions()
    {
        $clones = $this->getConfig('cloned');
        if (empty($clones))
            return [];
        foreach ($clones as $clone) {
            $orders[] = Order::find($clone);
        }

        $positions = collect($orders);

        return $positions->sortByDesc(function ($a) {
            if (isset($a->created_at)) {
                return $a->created_at;
            }
            return false;
        });
    }

    protected function addReBuyCount($order)
    {
        $config = $this->getConfig();
        if (isset($config['rebuy'][self::getOrderHash($order)])) {
            if ($config['rebuy'][self::getOrderHash($order)]['lastPrice'] != $order->price) {
                $config['rebuy'][self::getOrderHash($order)] = [
                    'count' => $config['rebuy'][self::getOrderHash($order)]['count'] + 1,
                    'lastPrice' => $order->price
                ];
            }
        } else {
            $config['rebuy'][self::getOrderHash($order)]['count'] = 1;
            $config['rebuy'][self::getOrderHash($order)]['lastPrice'] = $order->price;
        }
        $this->setConfig($config);
    }

    protected function getReBuyCount($order)
    {
        $config = $this->getConfig();
        if (isset($config['rebuy'][self::getOrderHash($order)])) {
            return $config['rebuy'][self::getOrderHash($order)]['count'];
        }
        return 0;
    }

    protected function configForm()
    {
        $csrf = csrf_field();
        return '
<div class="container-fluid">

<h2>Configuration</h2>
<form method="post">
' . $csrf . '
    <div class="row">
    <div class="col-4">
    <label for="tpTrigger">
new order when profit > or =     
    </label>
    <input name="tpTrigger" type="text" id="tpTrigger" value="' . $this->getConfig('tpTrigger') . '"> %
    </div>
    
    <div class="col-4">
    <label for="amountPercent">
buy      
    </label>
    <input name="amountPercent" type="text" id="amountPercent" value="' . $this->getConfig('amountPercent') . '"> percent more each trade
    </div>
    
    <div class="4">
    <button type="submit" class="btn btn-primary">Save</button>
    </div>
</div>
</form>
    </div>
        ';
    }
}