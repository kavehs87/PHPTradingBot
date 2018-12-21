@extends('layout')

@section('body')

    @if($signals->isNotEmpty())
        <table class="table table-hover table-responsive col-12">
            <thead>
            <tr>
                @foreach(array_keys($signals->first()->toArray()) as $column)
                    <th>{{$column}}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($signals as $signal)
                <tr>
                    @foreach($signal->toArray() as $value)
                        <td>
                            {{$value}}
                        </td>
                    @endforeach
                    <td>
                        <a href="{{route('newPosition',[$signal->market,10])}}" onclick="return confirm('execute a new order?');">
                            Buy
                        </a>
                    </td>
                    <td>
                        <a target="_blank"
                           href="https://www.tradingview.com/chart/?symbol=BINANCE%3A{{\App\TradeHelper::market2symbol($signal->market)}}"
                           class="btn btn-default">TradingView</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>
            no signal
        </p>
    @endif
    <div class="col-2 offset-5">
        {{$signals->links()}}
    </div>

@endsection