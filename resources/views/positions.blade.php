@extends('layout')

@section('body')

    <h2 style="margin-left: 20px;">Open Orders</h2>
    @if($open->isNotEmpty())
        <table class="table table-hover table-responsive col-12">
            <thead>
            <tr>
                <th>P/L</th>
                <th>symbol</th>
                <th>Buy</th>
                <th>Current</th>
                <th>Quantity</th>
                <th>Side</th>
                <th>IsTrailing</th>
                <th>TP</th>
                <th>SL</th>
                <th>TTP</th>
                <th>TSL</th>
                <th>max</th>
                <th>
                    Action
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($open as $symbol)

                @foreach($symbol as $order)
                    <form action="{{route('editPosition',$order->id)}}" method="post">
                        {{csrf_field()}}
                        <tr>
                            <td class="@if($order->inProfit()) bg-success @else bg-danger @endif">
                                {{round($order->getPL(),3)}}%
                            </td>
                            <td>{{$order->symbol}}</td>
                            <td>{{$order->price}}</td>
                            <td>{{$order->getCurrentPrice()}}</td>
                            <td>{{$order->origQty}}</td>
                            <td>{{$order->side}}</td>
                            <td>@if($order->trailing) Yes @else No @endif</td>
                            <td><input type="text" size="2" value="{{$order->takeProfit}}" name="takeProfit">%</td>
                            <td><input type="text" size="2" value="{{$order->stopLoss}}" name="stopLoss">%</td>
                            <td><input type="text" size="2" value="{{$order->trailingTakeProfit}}" name="trailingTakeProfit">%</td>
                            <td><input type="text" size="2" value="{{$order->trailingStopLoss}}" name="trailingStopLoss">%</td>
                            <td>{{$order->maxFloated}}</td>
                            <td>
                                <button class="btn btn-success" type="submit">Save</button>
                                <a href="{{route('closePosition',$order->id)}}" class="btn btn-danger" onclick="return confirm('Are you sure?');">Close</a>
                                <a target="_blank" href="https://www.tradingview.com/chart/?symbol=BINANCE%3A{{$order->symbol}}" class="btn btn-default">TradingView</a>
                            </td>
                        </tr>
                    </form>
                @endforeach


            @endforeach
            </tbody>
        </table>
    @else
        <p class="col-12">
            no open order
        </p>
    @endif

    <div style="clear: both;"></div>

    @include('newPosition')


@endsection