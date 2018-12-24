@extends('layout')

@section('body')

    <h2 style="margin-left: 20px;">Order History</h2>
    @if($all->isNotEmpty())
        <table class="table table-hover table-responsive col-12">
            <thead>
            <tr>
                <td>Symbol</td>
                <td>date</td>
                <td>Buy</td>
                <td>Sell</td>
                <td>P/L</td>
                <td>Qty</td>
                <td>TimeFrame</td>
                <td>time</td>
                <th>TP</th>
                <th>SL</th>
                <th>TTP</th>
                <th>TSL</th>
                <th>max</th>
                <th>min</th>
                <th>Comments</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($all as  $order)
                <tr>
                    <td>{{$order->symbol}}</td>
                    <td>{{$order->created_at->diffForHumans()}}</td>
                    <td>{{$order->price}}</td>
                    <td>
                        @if(isset($order->sellOrder) && !empty($order->sellOrder))
                            {{$order->sellOrder->price}}
                        @endif
                    </td>
                    <td class="@if($order->getPL(true) >= 0) bg-success @else bg-danger @endif">
                        {{round($order->getPL(true),3)}}%
                    </td>
                    <td>{{$order->origQty}}</td>
                    <td>{{$order->getTimeFrame()}}</td>
                    {{--                        <td>{{$order->created_at->diffForHumans()}}</td>--}}
                    <td>
                        @if(isset($order->sellOrder) && !empty($order->sellOrder))
                            {{$order->sellOrder->created_at->diffForHumans()}}
                        @endif
                    </td>
                    <td>{{$order->takeProfit}}%</td>
                    <td>{{$order->stopLoss}}%</td>
                    <td>{{$order->trailingTakeProfit}}%</td>
                    <td>{{$order->trailingStopLoss}}%</td>
                    <td>{{$order->maxFloated}}</td>
                    <td>{{$order->minFloated}}</td>
                    <td>{{$order->sellOrder->comment}}</td>
                    <td>
                        <a href="{{route('positions',$order->id)}}" class="btn btn-secondary">TV</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p class="col-12">
            no history
        </p>
    @endif
    <div class="col-3 offset-4">
        {{$all->links()}}
    </div>

@endsection