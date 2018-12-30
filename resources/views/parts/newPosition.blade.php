<div class="card mt-2">
    <div class="card-header">
        <span class="tradingSymbol"></span>
        <span class="text-right">
            <i class="fa toggleFavorite"></i>
        </span>
    </div>

    <div class="card-body">
        @if($status = \App\TradeHelper::systemctl('ticker','status'))

            <div class="ui-widget">
                <label for="pair">Symbol</label>
                <input class="col-12 form-control" id="pair" placeholder="type in symbol"
                       @if(isset($order)) value="{{$order->symbol}}" disabled @endif>
            </div>
            <label for="quantity" class="mt-2">Amount (USDT)</label>
            <input class="col-12 form-control col-12" type="text" id="quantity" value="10" placeholder="quantity"
                   @if(isset($order)) value="{{$order->origQty}}" disabled @endif>
            <label for="tp" class="mt-2">Target Profit Percent</label>
            <input class="form-control col-12" style="background: #0080001c;" type="number" id="tp" placeholder="TP%"
                   @if(isset($order)) value="{{$order->takeProfit}}" @endif>
            <label for="sl" class="mt-2">Stop Loss Percent</label>
            <input class="form-control col-12" style="background: #ff4a682b;" type="number" id="sl" placeholder="SL%"
                   @if(isset($order)) value="{{$order->stopLoss}}" @endif>
            <label for="ttp" class="mt-2">Trailing Target Profit Percent</label>
            <input class="form-control col-12" style="background: #0080001c;" type="number" id="ttp" placeholder="TTP%"
                   @if(isset($order)) value="{{$order->trailingTakeProfit}}" @endif>
            <label for="tsl" class="mt-2">Trailing Stop Loss Percent</label>
            <input class="form-control col-12" style="background: #ff4a682b;" type="number" id="tsl" placeholder="TSL%"
                   @if(isset($order)) value="{{$order->trailingStopLoss}}" @endif>

            <div class="mt-4"></div>
            @if(isset($order))
                <button onclick="savePosition()" class="col-12 btn btn-success" id="savePositionBtn">
                    Save
                </button>
                <button onclick="cancelEdit()" class="col-12 btn btn-primary mt-1">
                    Cancel/New
                </button>
            @else
                <button onclick="openPosition()" class="col-12 btn btn-primary">
                    Buy
                </button>
                @if($show)
                    <button onclick="cancelEdit()" class="col-12 btn btn-primary mt-1">
                        Cancel
                    </button>
                @endif
            @endif

        @else
            <p>
                Service is not Running
            </p>

        @endif
    </div>
</div>
