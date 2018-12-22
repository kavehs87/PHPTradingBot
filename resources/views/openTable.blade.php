<h2 class="mt-5" style="margin-left: 20px;">Open Orders ({{$allCount}})</h2>

@if($open->isNotEmpty())
    <table class="table table-hover table-responsive col-12">
        <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>P/L</th>
            <th>pom</th>
            <th>symbol</th>
            <th>Buy</th>
            <th>Current</th>
            <th>Quantity</th>
            {{--<th>Side</th>--}}
            <th>IsTrailing</th>
            <th>TP</th>
            <th>SL</th>
            <th>TTP</th>
            <th>TSL</th>
            <th>note</th>
            <th>max</th>
            <th>min</th>
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
                        <td>{{$order->id}}</td>
                        <td>{{$order->created_at->diffForHumans()}}</td>
                        <td class="@if($order->inProfit()) bg-success @else bg-danger @endif">
                            {{round($order->getPL(),3)}}%
                        </td>
                        <td>
                            {{round($order->maxFloated - $order->getPL(),2)}}%
                        </td>
                        <td>{{$order->symbol}}</td>
                        <td>{{$order->price}}</td>
                        <td>{{$order->getCurrentPrice()}}</td>
                        <td>{{$order->origQty}}</td>
                        {{--<td>{{$order->side}}</td>--}}
                        <td>
                            @if($order->trailing)
                                <a href="{{route('toggleTrailing',$order->id)}}" class="btn btn-secondary">Yes</a>
                            @else
                                <a href="{{route('toggleTrailing',$order->id)}}" class="btn btn-outline-secondary">No</a>
                            @endif
                        </td>
                        {{--<td><input type="text" size="2" value="{{$order->takeProfit}}" name="takeProfit">%</td>--}}
                        <td>{{$order->takeProfit}}%</td>
                        <td>{{$order->stopLoss}}%</td>
                        <td>{{$order->trailingTakeProfit}}%
                        </td>
                        <td>{{$order->trailingStopLoss}}%
                        </td>
                        <td>
                            {{$order->comment}}
                        </td>
                        <td>{{$order->maxFloated}}</td>
                        <td>{{$order->minFloated}}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{route('positions',$order->id)}}" class="btn btn-success">Edit</a>
                                <a href="{{route('closePosition',$order->id)}}" class="btn btn-danger" onclick="return confirm('Are you sure?');">Close</a>
                                <button onclick="openTV('{{$order->symbol}}')" class="btn btn-secondary">TV</button>
                            </div>
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