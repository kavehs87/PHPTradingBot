@extends('layouts.app')

@section('content')

    <div class="card">
        <div class="card-header">
            History
        </div>
        <div class="card-body">
            @if($all->isNotEmpty())
                <table class="table table-hover table-striped table-responsive col-12">
                    <thead>
                    <tr>
                        <td>Symbol</td>
                        <th>
                            <a href="{{route('sortHistory',['created_at',$sortType == 'desc' ? 'asc' : 'desc'])}}">
                                Buy Date
                                @if($column == 'created_at')
                                    <i>
                                        {{$sortType}}
                                    </i>
                                @endif
                            </a>
                        </th>
                        <th>Buy</th>
                        <th>Sell</th>
                        <th>
                            <a href="{{route('sortHistory',['pl',$sortType == 'desc' ? 'asc' : 'desc'])}}">
                                P/L
                                @if($column == 'pl')
                                    <i>
                                        {{$sortType}}
                                    </i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{route('sortHistory',['origQty',$sortType == 'desc' ? 'asc' : 'desc'])}}">
                                Qty
                                @if($column == 'origQty')
                                    <i>
                                        {{$sortType}}
                                    </i>
                                @endif
                            </a>
                        </th>
                        <th>TimeFrame</th>
                        <th>
                            <a href="{{route('sortHistory',['sell_date',$sortType == 'asc' ? 'desc' : 'asc'])}}">
                                Sell Date
                                @if($column == 'sell_date')
                                    <i>
                                        {{$sortType}}
                                    </i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{route('sortHistory',['takeProfit',$sortType == 'desc' ? 'asc' : 'desc'])}}">
                                TP
                                @if($column == 'takeProfit')
                                    <i>
                                        {{$sortType}}
                                    </i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{route('sortHistory',['stopLoss',$sortType == 'desc' ? 'asc' : 'desc'])}}">
                                SL
                                @if($column == 'stopLoss')
                                    <i>
                                        {{$sortType}}
                                    </i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{route('sortHistory',['trailingTakeProfit',$sortType == 'desc' ? 'asc' : 'desc'])}}">
                                TTP
                                @if($column == 'trailingTakeProfit')
                                    <i>
                                        {{$sortType}}
                                    </i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{route('sortHistory',['trailingStopLoss',$sortType == 'desc' ? 'asc' : 'desc'])}}">
                                TSL
                                @if($column == 'trailingStopLoss')
                                    <i>
                                        {{$sortType}}
                                    </i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{route('sortHistory',['maxFloated',$sortType == 'desc' ? 'asc' : 'desc'])}}">
                                max
                                @if($column == 'maxFloated')
                                    <i>
                                        {{$sortType}}
                                    </i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{route('sortHistory',['minFloated',$sortType == 'desc' ? 'asc' : 'desc'])}}">
                                min
                                @if($column == 'minFloated')
                                    <i>
                                        {{$sortType}}
                                    </i>
                                @endif
                            </a>
                        </th>
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
                            <td class="@if($order->pl >= 0) bg-success @else bg-danger @endif">
                                {{round($order->pl,3)}}%
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
                            <td>{{round($order->maxFloated,4)}}</td>
                            <td>{{round($order->minFloated,4)}}</td>
                            <td>{{$order->sellOrder->comment}}</td>
                            <td>
                                <a href="{{route('showSymbol',$order->symbol)}}" class="btn btn-secondary">TV</a>
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
        </div>
    </div>
    <div class="col-12 text-center">
        {{$orders->links()}}
    </div>

@endsection