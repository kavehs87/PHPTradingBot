<canvas id="canvas"></canvas>


<script>
    function hashCode(str) { // java String#hashCode
        var hash = 0;
        for (var i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }
        return hash;
    }

    function intToRGB(i){
        var c = (i & 0x00FFFFFF)
            .toString(16)
            .toUpperCase();

        return "00000".substring(0, 6 - c.length) + c;
    }

    function random_rgba() {
        var o = Math.round, r = Math.random, s = 255;
        return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',' + r().toFixed(1) + ')';
    }

    var config = {
        type: 'line',
        data: {
            labels: {!! json_encode(array_map(function ($val){
            return \Carbon\Carbon::createFromTimestamp($val)->toIso8601String();
            },array_keys(reset($config['symbols'])))) !!},
            datasets: [
                @if(isset($config['symbols']))
                    @foreach($config['symbols'] as $symbol => $data)
                {
                    label: '{{$symbol}}',
                    backgroundColor: random_rgba(),
                    data: {!! json_encode(array_values($data)) !!},
                    fill: true,
                    hidden: true,
                },
                    @endforeach
                @endif
                ]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Past ...'
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    type: 'time',
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Time'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Value'
                    }
                }]
            }
        }
    };

    window.onload = function () {
        var ctx = document.getElementById('canvas').getContext('2d');
        window.myLine = new Chart(ctx, config);
    };

</script>


{{--<h2>--}}
    {{--candidates--}}
{{--</h2>--}}

{{--<table class="table-responsive table table-hover">--}}
    {{--<thead>--}}
    {{--<tr>--}}
        {{--<th>--}}
            {{--symbol--}}
        {{--</th>--}}
        {{--@if(isset($config['symbols']))--}}
            {{--@foreach(reset($config['symbols']) as $k => $v)--}}
                {{--<th>--}}
                    {{--{{\Carbon\Carbon::createFromTimestamp($k)->diffForHumans()}}--}}
                {{--</th>--}}
            {{--@endforeach--}}
        {{--@endif--}}
        {{--<th>--}}
            {{--min/max--}}
        {{--</th>--}}
    {{--</tr>--}}

    {{--</thead>--}}
    {{--@if(isset($config['symbols']))--}}
        {{--@forelse($config['symbols'] as $symbol => $data)--}}
            {{--<tr>--}}
                {{--<td>--}}
                    {{--{{$symbol}}--}}
                {{--</td>--}}
                {{--@php--}}
                    {{--$lastValue = null;--}}
                {{--@endphp--}}
                {{--@foreach($data as $k => $v)--}}
                    {{--@php--}}
                        {{--if ($lastValue > $v){--}}
                            {{--$direction = 'red';--}}
                        {{--}--}}
                        {{--else {--}}
                            {{--$direction = 'green';--}}
                        {{--}--}}
                        {{--if ($lastValue == null || $lastValue == $v){--}}
                            {{--$direction = 'grey';--}}
                        {{--}--}}
                    {{--@endphp--}}
                    {{--<th>--}}
                        {{--<span style="color: {{$direction}};">--}}
                            {{--{{$v}} - ({{round(\App\TradeHelper::getPercent($lastValue,$v),2)}}%)--}}
                        {{--</span>--}}
                    {{--</th>--}}

                    {{--@php--}}
                        {{--$lastValue = $v;--}}
                    {{--@endphp--}}
                {{--@endforeach--}}
                {{--<td>--}}
                    {{--{{round(\App\TradeHelper::getPercent(reset($data),end($data)),2)}}%--}}
                {{--</td>--}}
            {{--</tr>--}}

        {{--@empty--}}


        {{--@endforelse--}}
    {{--@endif--}}
{{--</table>--}}


