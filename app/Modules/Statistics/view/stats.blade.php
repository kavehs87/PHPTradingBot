<div class="container">
    <div class="row">
        <h2 class="col-12">
            Statistics for Today
        </h2>

        <div class="card">
            <div class="card-header">
                <span>
                    Profit
                </span>
            </div>
            <div class="card-body">
                <p>
                    Total successful trades : {{$profitCount}}
                </p>
                <p>
                    Total USDT : {{$totalProfit}}
                </p>
                <p>
                    Total Percentage : {{$totalProfitPercent}}
                </p>
                <p>
                    Highest profit : {{$highestProfit->symbol ?? 'N/A'}} : {{$highestProfit ? $highestProfit->getPL(true) : '-'}}
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span>
                    Loss
                </span>
            </div>
            <div class="card-body">
                <p>
                    Total failed trades : {{$lossCount}}
                </p>
                <p>
                    Total USDT : {{$totalLoss}}
                </p>
                <p>
                    Total Percentage : {{$totalLossPercent}}
                </p>
                <p>
                    Highest loss :{{$highestLoss->symbol ?? 'N/A'}} : {{$highestLoss ? $highestLoss->getPL(true) : '-'}}
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span>
                    Performance
                </span>
            </div>
            <div class="card-body">
                <p>
                    Overall Performance :
                </p>
                <p>
                    Day Income : {{round($totalProfit - abs($totalLoss),3)}} USDT
                </p>
                <p>
                    Total Money Used : {{$totalMoneyUsed}} USDT
                </p>
                <p>
                    binance fee : {{$totalMoneyUsed - \App\TradeHelper::calcPercent($totalMoneyUsed,0.2)}} USDT
                </p>
            </div>
        </div>
    </div>
</div>