<div class="container-fluid">
    <h2 class="col-12">
        Coin Market Cap coins for Binance
    </h2>

    <div class="table-responsive">
        <table class="table table-hover col-12">
            <thead>
            <tr>
                <th>Symbol</th>
                <th>Market Cap</th>
                <th>Price</th>
                <th>Supply</th>
                <th>Volume (24h)</th>
                <th>% 1h</th>
                <th>% 24h</th>
                <th>% 7d</th>
            </tr>
            </thead>
            <tbody>
            @foreach($coins as $coin)
                <tr>
                    <td>{{$coin[2]}}</td>
                    <td>{{$coin[3]}}</td>
                    <td>{{$coin[4]}}</td>
                    <td>{{$coin[5]}}</td>
                    <td>{{$coin[6]}}</td>
                    <td @if($coin[7] > 0) class="bg-success" @else class="bg-danger" @endif>{{$coin[7]}}</td>
                    <td @if($coin[8] > 0) class="bg-success" @else class="bg-danger" @endif>{{$coin[8]}}</td>
                    <td @if($coin[9] > 0) class="bg-success" @else class="bg-danger" @endif>{{$coin[9]}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>