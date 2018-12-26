<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/11/18
 * Time: 22:55
 */

namespace App\Http\Controllers;


use App\Modules;
use App\Order;
use App\Setting;
use App\Signal;
use App\TradeHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function signals()
    {
        return view('signals', [
            'signals' => Signal::orderBy('created_at', 'desc')->paginate(10)
        ]);
    }

    public function system()
    {

        $balances = [];
        $binanceConfig = Setting::getValue('binance');
        if ($binanceConfig) {
            if (Cache::get('balances')) {
                $balances = json_decode(Cache::get('balances'), true);
            } else {

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
                $balances = null;
                foreach ($binance->balances() as $coin => $balance) {
                    if ($balance['available'] != 0) {
                        $balances[$coin] = $balance;
                    }
                }
                Cache::put('balances', json_encode($balances), Carbon::now()->addMinutes(5));
            }
        }


        $lastPrices = Cache::get('prices');
        return view('system', [
            'lastPrices' => json_decode($lastPrices, true),
            'balances' => $balances,
            'binanceConfig' => $binanceConfig,
        ]);
    }

    public function positions($id = null)
    {
        $open = Order::getOpenPositions();
        $count = count(Order::getOpenPositions(true));
        $prices = Cache::get('prices');
        $order = null;
        $showSymbol = false;

        if (Route::currentRouteName() == 'showSymbol') {
            $showSymbol = true;
        } else {
            if ($id) {
                $order = Order::find($id);
            }
        }


        return view('positions', [
            'open' => $open,
            'prices' => json_decode($prices, true),
            'allCount' => $count,
            'order' => $order,
            'show' => $showSymbol,
            'symbol' => $id
        ]);
    }

    public function openTable()
    {
        $open = Order::getOpenPositions();
        $count = count(Order::getOpenPositions(true));

        $prices = Cache::get('prices');
        $html = view('openTable', [
            'open' => $open,
            'prices' => json_decode($prices, true),
            'allCount' => $count
        ]);

        return $html;
    }

    public function history($column = 'created_at', $sortType = 'desc')
    {
        $since = Carbon::now()->subDays(30);

        // todo change to join queries

        if ($column == 'pl') {
            $orders = Order::where('created_at', '>', $since)
                ->where('side', 'BUY')
                ->whereHas('sellOrder')
                ->orderBy('maxFloated', $sortType)
                ->paginate(20);
            $all = $orders->sort(function ($a, $b) {
                $aPL = $a->getPL(true);
                $bPL = $b->getPL(true);
                return $aPL < $bPL;
            });
        } elseif ($column == 'sell_date') {
            $orders = Order::where('created_at', '>', $since)
                ->where('side', 'BUY')
                ->whereHas('sellOrder')
                ->orderBy('created_at', $sortType)
                ->paginate(20);
            $all = $orders->sort(function ($a, $b) use ($sortType) {
                $aPL = $a->sellOrder->created_at;
                $bPL = $b->sellOrder->created_at;
                if ($sortType == 'asc')
                    return $aPL > $bPL;
                if ($sortType == 'desc')
                    return $aPL < $bPL;
            });
        } else {
            $orders = Order::where('created_at', '>', $since)
                ->where('side', 'BUY')
                ->whereHas('sellOrder')
                ->orderBy($column, $sortType)
                ->paginate(20);
            $all = $orders;
        }

        return view('history', [
            'orders' => $orders,
            'all' => $all,
            'column' => $column,
            'sortType' => $sortType
        ]);
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public
    function closePosition($id)
    {
        $buyId = Order::find($id);

        $sellOrderInfo = Order::sell($buyId->symbol, $buyId->origQty, $buyId->id);
        return redirect()->back()->with('success', 'position closed.');
    }

    /**
     * @param $id
     * @param Request $request
     * @return void
     */
    public
    function editPosition($id, Request $request)
    {
        $order = Order::find($id);

        $data = $request->except('_token');

        foreach ($data as $property => $value) {
            if ($value != '-')
                $order->{$property} = $value;
        }

        $order->save();
        return redirect()->back()->with('success', 'position modified.');
    }

    public function newPosition($market, $quantity, $tp = null, $sl = null, $ttp = null, $tsl = null, Request $request)
    {
        $options = [];

        if ($tp != '-')
            $options['tp'] = $tp;
        if ($sl != '-')
            $options['sl'] = $sl;
        if ($ttp != '-')
            $options['ttp'] = $ttp;
        if ($tsl != '-')
            $options['tsl'] = $tsl;

        $symbol = TradeHelper::market2symbol($market);
        $buyId = Order::buy($symbol, $quantity, '', $options);


        return redirect(route('positions'))->with('success', 'position opened.');
    }

    public
    function modules()
    {
        return view('modules');
    }

    public
    function enableModule($moduleId)
    {
        $module = Modules::find($moduleId);
        $module->setActive();
        return redirect()->back();
    }

    public
    function disableModule($moduleId)
    {
        $module = Modules::find($moduleId);
        $module->setInactive();
        return redirect()->back();
    }

    public
    function installModule($moduleName)
    {
        Modules::install($moduleName);
        return redirect()->back();
    }

    public
    function uninstallModule($moduleId)
    {
        $module = Modules::find($moduleId);
        $module->delete();
        return redirect()->back();
    }

    public
    function saveSettings(Request $request)
    {
        $binance = $request->get('binance');
        $miningHamster = $request->get('miningHamster');


        if ($binance) {
            Setting::setValue('binance', $binance);
        }
        if ($miningHamster) {
            Setting::setValue('miningHamster', $miningHamster);
        }


        return redirect()->back()->with('success');
    }

    public
    function saveOrderDefaults(Request $request)
    {
        $data = $request->except('_token');
        Setting::setValue('orderDefaults', $data['orderDefaults']);

        return redirect()->back();
    }


    public
    function toggleTrailing($id)
    {
        $order = Order::find($id);
        $order->trailing = !$order->trailing;
        $order->save();
        return redirect()->back();
    }

    public
    function savePosition(Request $request)
    {
        $order = Order::find($request->get('id'));
        $order->takeProfit = $request->get('tp');
        $order->stopLoss = $request->get('sl');
        $order->trailingTakeProfit = $request->get('ttp');
        $order->trailingStopLoss = $request->get('tsl');
        $order->save();
        return response('success', 200);
    }


}