<div class="col-12">
    <h2>
        Symbols for last 1 day trades
    </h2>
    <style>
        .recentPair {
            display: inline-block;
            border: 1px solid #dadde038;
            padding: 4.5px;
            border-radius: 5px;
            margin: 4px;
        }

        .recentPair.red {
            background-color: #ff00002b;
        }
        .recentPair.green {
            background-color: #3cbc9845;
        }

        .recentPairs {
            margin: 0px 10px 0px 10px;
        }

        .symbol {
            display: inline-block;
        }

        .avpl {
            display: inline-block;
        }
    </style>

    <div class="recentPairs">

        @if($pairs = \App\TradeHelper::recentlyTradedPairs(now()->subDay(1)))
            @foreach($pairs as $pair => $pairData)
                <div class="recentPair @if($pairData['avpl'] > 0) green @else red @endif">
                    <a href="{{route('showSymbol',$pairData['symbol'])}}" class="symbol">
                        {{$pairData['symbol']}}
                    </a>
                    <div class="avpl">

                    </div>{{$pairData['avpl']}}%
                </div>
            @endforeach
        @endif

    </div>
</div>