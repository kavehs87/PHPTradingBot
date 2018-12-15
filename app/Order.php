<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/12/18
 * Time: 01:18
 */

namespace App;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property int id
 * @property string side
 * @property string timeInForce
 * @property string type
 * @property string status
 * @property int cummulativeQuoteQty
 * @property int executedQty
 * @property int origQty
 * @property float price
 * @property string symbol
 * @property int orderId
 * @property int clientOrderId
 * @property float|int transactTime
 * @property int buyId
 * @property mixed sellOrder
 * @property int takeProfit
 * @property int stopLoss
 * @property int trailingTakeProfit
 * @property float trailingStopLoss
 * @property mixed maxFloated
 * @property bool trailing
 */
class Order extends Model
{
    protected $guarded = [];


    /**
     * @param $symbol
     * @param $quantity
     * @return bool
     * @throws \Exception
     */
    public static function buy($symbol, $quantity)
    {
        $orderDefaults = Setting::getValue('orderDefaults');

        /*
         * Module Hook
         */
        $activeModules = Modules::getActiveModules();
        $anyFails = false;
        if ($activeModules) {
            foreach ($activeModules as $module) {
                if ($module->getFactory()->beforeBuy() == false) {
                    $anyFails = true;
                }
            }
        }
        if ($anyFails) {
            return false;
        }


        $prices = json_decode(Cache::get('prices'), true);
        if (!isset($prices[$symbol])) {
            throw new \Exception("price for the specified symbol not found in cache");
        }

        $side = 'BUY';
        $type = 'MARKET';
        $timeInForce = 'GTC';
        $timestamp = round(microtime(true) * 1000);

        $order = new Order();
        $order->symbol = $symbol;
        $order->orderId = rand(1, 9999999);
        $order->clientOrderId = rand(1, 999);
        $order->transactTime = $timestamp;
        $order->price = $prices[$symbol];
        $order->origQty = $quantity;
        $order->executedQty = $quantity;
        $order->cummulativeQuoteQty = $quantity;
        $order->status = 'FILLED';
        $order->timeInForce = $timeInForce;
        $order->type = $type;
        $order->side = $side;
        $order->takeProfit = isset($orderDefaults['tp']) ? $orderDefaults['tp'] : 2;
        $order->stopLoss = isset($orderDefaults['sl']) ? $orderDefaults['sl'] : 2;
        $order->trailingTakeProfit = isset($orderDefaults['ttp']) ? $orderDefaults['ttp'] : 1;
        $order->trailingStopLoss = isset($orderDefaults['tsl']) ? $orderDefaults['tsl'] : 0.5;

        $order->save();

        /*
         * Modules after hook
         */
        if ($activeModules) {
            foreach ($activeModules as $module) {
                $module->getFactory()->afterBuy();
            }
        }

        return $order->id;
    }

    /**
     * @param $symbol
     * @param $quantity
     * @param $buyId
     * @return bool
     * @throws \Exception
     */
    public static function sell($symbol, $quantity, $buyId)
    {
        /*
         * Module before Hook
         */
        $activeModules = Modules::getActiveModules();
        $anyFails = false;
        if ($activeModules) {
            foreach ($activeModules as $module) {
                if ($module->getFactory()->beforeSell() == false) {
                    $anyFails = true;
                }
            }
        }
        if ($anyFails) {
            return false;
        }


        $prices = json_decode(Cache::get('prices'), true);
        if (!isset($prices[$symbol])) {
            throw new \Exception("price for the specified symbol not found in cache");
        }

        $side = 'SELL';
        $type = 'MARKET';
        $timeInForce = 'GTC';
        $timestamp = round(microtime(true) * 1000);

        $order = new Order();
        $order->symbol = $symbol;
        $order->orderId = rand(1, 9999999);
        $order->clientOrderId = rand(1, 999);
        $order->transactTime = $timestamp;
        $order->price = $prices[$symbol];
        $order->origQty = $quantity;
        $order->executedQty = $quantity;
        $order->cummulativeQuoteQty = $quantity;
        $order->status = 'FILLED';
        $order->timeInForce = $timeInForce;
        $order->type = $type;
        $order->side = $side;
        $order->buyId = $buyId;

        $order->save();

        /*
         * Modules after hook
         */
        if ($activeModules) {
            foreach ($activeModules as $module) {
                $module->getFactory()->afterSell();
            }
        }
        return $order->id;
    }

    public static function getOpenPositions($noGroup = false)
    {
        $since = Carbon::now()->subDays(30);

        $orders = Order::where('created_at', '>', $since)
            ->where('side','BUY')
            ->whereDoesntHave('sellOrder')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($noGroup) {
            return $orders;
        }
        return $orders->groupBy('symbol');
    }

    public function sellOrder()
    {
        return $this->belongsTo(Order::class, 'id', 'buyId');
    }

    public function isOpen()
    {
        if ($this->sellOrder == null && $this->side != 'SELL') {
            return true;
        }
        return false;
    }

    public function getPL($history = false)
    {
        $prices = json_decode(Cache::get('prices'), true);
        if (!isset($prices[$this->symbol])) {
            return false;
        }

        if (!$this->isOpen()) {
            if ($history) {
                $buyPrice = $this->price;
                $nowPrice = $this->sellOrder->price;
            } else {
                return false;
            }
        } else {
            $buyPrice = $this->price;
            $nowPrice = $prices[$this->symbol];
        }


        return TradeHelper::getPercent($buyPrice, $nowPrice);
    }

    public function getCurrentPrice()
    {
        $prices = json_decode(Cache::get('prices'), true);
        if (!isset($prices[$this->symbol])) {
            return false;
        }
        return $prices[$this->symbol];
    }

    public function inProfit()
    {
        if ($this->getPL() > 0) {
            return true;
        }
        return false;
    }

    public static function getClosedPositions($noGroup = false)
    {
        $since = Carbon::now()->subDays(30);
        $orders = Order::where('created_at', '>', $since)
            ->where('side','BUY')
            ->whereHas('sellOrder')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($noGroup) {
            return $orders;
        }
        return $orders->groupBy('symbol');
    }

    public function getTimeFrame()
    {
        if (!$this->isOpen()) {
            $buyDate = Carbon::createFromTimestampMs($this->transactTime);
            $sellDate = Carbon::createFromTimestampMs($this->sellOrder->transactTime);
            return $buyDate->diffForHumans($sellDate, true);
        }
        return false;
    }

    public function updateState()
    {
        /*
         * updates the maxFloated
         */
        $pl = $this->getPL();
        if ($pl >= $this->maxFloated) {
            $this->maxFloated = $pl;
            $this->save();
        }


        /*
         * updates the isTrailing
         */
        if (!$this->trailing) {
            if ($this->inProfit()) {
                // profit
                if ($this->getPL() > $this->takeProfit) {
                    $this->trailing = true;
                    $this->save();
//                    Event::create([
//                        'type' => 'info',
//                        'message', 'trailing take profit activated for ' . $this->symbol,
//                    ]);
                }
            } else {
                // loss
                if (abs($this->getPL()) > $this->stopLoss) {
                    $this->trailing = true;
                    $this->save();
//                    Event::create([
//                        'type' => 'info',
//                        'message', 'trailing stoploss activated for ' . $this->symbol,
//                    ]);
                }
            }
        } /*
         * watch for trailing P/L
         */
        else {
            $diff = $this->maxFloated - $this->getPL();
            if ($this->inProfit()) {
                // profit
                if ($diff > $this->trailingTakeProfit) {
                    self::sell($this->symbol, $this->origQty, $this->id);
                }
            } else {
                // loss
                if ($this->maxFloated >= 0) {
                    //just got reversed to loss from profit
//                    $this->maxFloated = $this->getPL();
//                    $this->save();
                }
                if ($diff > $this->trailingStopLoss) {
                    self::sell($this->symbol, $this->origQty, $this->id);
                }
            }

        }

    }

}