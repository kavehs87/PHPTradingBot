<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/28/18
 * Time: 17:45
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class Ticker extends Model
{
    protected $table = 'ticker';
    protected $fillable = ['eventType', 'eventTime', 'symbol', 'priceChange', 'percentChange', 'averagePrice', 'prevClose', 'close', 'closeQty', 'bestBid', 'bestBidQty', 'bestAsk', 'bestAskQty', 'open', 'high', 'low', 'volume', 'quoteVolume', 'openTime', 'closeTime', 'firstTradeId', 'lastTradeId', 'numTrades'];
}