@extends('layouts.app')

@section('content')
    <div class="col-12">
        @if($diffTicks <= 3)
            <div class="alert alert-success" role="alert">
                Daemon is running
            </div>
        @elseif($diffTicks >= 3 && $diffTicks < 10)
            <div class="alert alert-warning" role="alert">
                Ticker is {{$diffTicks}} seconds behind
            </div>
        @else
            <div class="alert alert-danger" role="alert">
                Daemon is not running, run:
                <code>
                    sh services daemon
                </code>
            </div>
        @endif
    </div>
    <div class="card">
        <div class="card-header">
            System Settings
            <small>restart required for starred (*) options</small>
        </div>
        <div class="row p-5">
            <div class="col-md-9">

                <form action="{{route('saveSettings')}}" method="post" class="row">
                    {{csrf_field()}}

                    <div class="col-12">
                        <div class="row">
                            <div class="col-md-3">
                                <h5>Settings</h5>
                            </div>
                            <div class="col-md-9">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="1"
                                                  @if(\App\Setting::getValue('trainingMode')) checked
                                                  @endif name="trainingMode"> Training Mode (uses binance
                                        ticker but doesn't send any trade to the exchange) </label>
                                </div>

                                <div class="checkbox">
                                    <label><input type="checkbox" value="1" name="modulesCanStopOrders"
                                                  @if(\App\Setting::getValue('modulesCanStopOrders') == 1) checked @endif>
                                        Modules can stop buy/sell orders </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-3">
                                <h5 class=""> Ticker</h5>
                            </div>
                            <div class="col-md-9">
                                <ul class="list-inline">
                                    <li><select class="form-control input-sm" name="tickerType">
                                            <option @if(\App\Setting::getValue('tickerType') == 'full') selected
                                                    @endif value="full">Full Ticker
                                            </option>
                                            <option @if(\App\Setting::getValue('tickerType') == 'mini') selected
                                                    @endif value="mini">Mini Ticker
                                            </option>
                                        </select></li>
                                    <li class="mt-2"><label><input type="checkbox" value="1" name="saveTicker"
                                                                   @if(\App\Setting::getValue('saveTicker')) checked @endif>
                                            Save Ticker to Database </label>
                                    </li>
                                    <li>
                                    </li>
                                </ul>
                                <ul class="list-inline">
                                    <li>delete old ticker data interval</li>
                                    <li>
                                        <select class="form-control input-sm col-md-2" name="oldTickerInterval">
                                            <option @if(\App\Setting::getValue('oldTickerInterval') == '0') selected
                                                    @endif value="0">never
                                            </option>
                                            <option @if(\App\Setting::getValue('oldTickerInterval') == '1 day') selected @endif>
                                                1 day
                                            </option>
                                            <option @if(\App\Setting::getValue('oldTickerInterval') == '1 week') selected @endif>
                                                1 week
                                            </option>
                                            <option @if(\App\Setting::getValue('oldTickerInterval') == '2 week') selected @endif>
                                                2 week
                                            </option>
                                            <option @if(\App\Setting::getValue('oldTickerInterval') == '1 Month') selected @endif>
                                                1 Month
                                            </option>
                                            <option @if(\App\Setting::getValue('oldTickerInterval') == '2 Month') selected @endif>
                                                2 Month
                                            </option>
                                            <option @if(\App\Setting::getValue('oldTickerInterval') == '3 Month') selected @endif>
                                                3 Month
                                            </option>
                                            <option @if(\App\Setting::getValue('oldTickerInterval') == '6 Month') selected @endif>
                                                6 Month
                                            </option>
                                        </select>
                                    </li>
                                    <li>
                                        careful with longer intervals, can use gigabytes of database space. (~ 2MB per
                                        minute)
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-3">
                                <h5 class=""> Order defaults</h5>
                            </div>
                            <div class="col-md-9">
                                <div class="form-row">
                                    <div class="col-2">
                                        <label for="amount">
                                            Quantity (USDT)
                                        </label>
                                    </div>
                                    <div class="col-10">
                                        <input type="text" name="orderDefaults[amount]" id="amount"
                                               class="form-control input-sm"
                                               value="{{isset(\App\Setting::getValue('orderDefaults')['amount']) ? \App\Setting::getValue('orderDefaults')['amount'] : 15}}">
                                    </div>
                                </div>
                                <div class="form-row mt-3">
                                    <div class="col-6">
                                        <label for="tp">Target Profit % </label>
                                        <input type="text" name="orderDefaults[tp]" id="tp"
                                               class="form-control input-sm"
                                               value="{{isset(\App\Setting::getValue('orderDefaults')['tp']) ? \App\Setting::getValue('orderDefaults')['tp'] : 3}}">
                                    </div>
                                    <div class="col-6">
                                        <label for="ttp">Trailing Take Profit % </label>
                                        <input type="text" name="orderDefaults[ttp]" id="ttp"
                                               class="form-control input-sm"
                                               value="{{isset(\App\Setting::getValue('orderDefaults')['ttp']) ? \App\Setting::getValue('orderDefaults')['ttp'] : 1}}">
                                    </div>
                                </div>
                                <div class="form-row mt-3">
                                    <div class="col-6">
                                        <label for="sl">Stop Loss % </label>
                                        <input type="text" name="orderDefaults[sl]" id="sl"
                                               class="form-control input-sm"
                                               value="{{isset(\App\Setting::getValue('orderDefaults')['sl']) ? \App\Setting::getValue('orderDefaults')['sl'] : 2}}">
                                    </div>
                                    <div class="col-6">
                                        <label for="tsl">Trailing Stop Loss % </label>
                                        <input type="text" name="orderDefaults[tsl]" id="tsl"
                                               class="form-control input-sm"
                                               value="{{isset(\App\Setting::getValue('orderDefaults')['tsl']) ? \App\Setting::getValue('orderDefaults')['tsl'] : 1}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-3">
                                <h5> Binance</h5>
                            </div>
                            <div class="col-md-9">
                                <ul class="list-inline">
                                    <li><input type="text" id="binance[api]" name="binance[api]"
                                               class="form-control input-sm"
                                               placeholder="{{$binanceConfig['api'] ? 'api key is saved leave blank if you don\' want to change it.' : 'enter your binance api key'}}">
                                    </li>
                                    <li>Binance api key</li>
                                </ul>
                                <ul class="list-inline">
                                    <li><input type="text" id="binance[secret]" name="binance[secret]"
                                               class="form-control input-sm"
                                               placeholder="{{$binanceConfig['secret'] ? 'api secret is saved leave blank if you don\' want to change it.' : 'enter your binance api secret'}}">
                                    </li>
                                    <li>Binance api Secret</li>
                                </ul>
                            </div>
                        </div>
                        {{--<div class="row mt-5">--}}
                            {{--<div class="col-md-3">--}}
                                {{--<h5> Proxy</h5>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-9">--}}
                                {{--<ul class="list-inline">--}}
                                    {{--<li><input type="checkbox" id="binance[proxyEnabled]" name="binance[proxyEnabled]"--}}
                                               {{--class=""--}}
                                               {{--value="1" onclick="toggleProxy();"--}}
                                               {{--@if(isset($binanceConfig['proxyEnabled']) && $binanceConfig['proxyEnabled']) checked @endif>--}}
                                        {{--<label for="binance[proxyEnabled]">--}}
                                            {{--Enable--}}
                                        {{--</label>--}}
                                    {{--</li>--}}
                                    {{--<li>not compatible with Web Socket ticker</li>--}}
                                {{--</ul>--}}
                                {{--<ul class="list-inline" id="proxy"--}}
                                    {{--@if(!isset($binanceConfig['proxyEnabled']) || $binanceConfig['proxyEnabled'] == false) style="display: none;" @endif>--}}
                                    {{--<li>--}}
                                        {{--<label for="binance[proxy][proto]">--}}
                                            {{--Protocol--}}
                                        {{--</label>--}}
                                        {{--<select type="text" id="binance[proxy][proto]" name="binance[proxy][proto]"--}}
                                                {{--class="form-control col-md-2 input-sm">--}}
                                            {{--<option @if(!isset($binanceConfig['proxy']['proto']) || $binanceConfig['proxy']['proto'] == 'http') selected @endif>--}}
                                                {{--http--}}
                                            {{--</option>--}}
                                            {{--<option @if(!isset($binanceConfig['proxy']['proto']) || $binanceConfig['proxy']['proto'] == 'https') selected @endif>--}}
                                                {{--https--}}
                                            {{--</option>--}}
                                            {{--<option @if(!isset($binanceConfig['proxy']['proto']) || $binanceConfig['proxy']['proto'] == 'socks4') selected @endif>--}}
                                                {{--socks4--}}
                                            {{--</option>--}}
                                            {{--<option @if(!isset($binanceConfig['proxy']['proto']) || $binanceConfig['proxy']['proto'] == 'socks5') selected @endif>--}}
                                                {{--socks5--}}
                                            {{--</option>--}}
                                            {{--<option @if(!isset($binanceConfig['proxy']['proto']) || $binanceConfig['proxy']['proto'] == 'socks5h') selected @endif>--}}
                                                {{--socks5h--}}
                                            {{--</option>--}}
                                        {{--</select>--}}
                                    {{--</li>--}}
                                    {{--<li class="mt-3">--}}
                                        {{--<label for="binance[proxy][host]">--}}
                                            {{--Host--}}
                                        {{--</label>--}}
                                        {{--<input type="text" id="binance[proxy][host]" name="binance[proxy][host]"--}}
                                               {{--class="form-control input-sm"--}}
                                               {{--value="{{isset($binanceConfig['proxy']['host']) ? $binanceConfig['proxy']['host'] : null}}">--}}
                                    {{--</li>--}}
                                    {{--<li class="mt-3">--}}
                                        {{--<label for="binance[proxy][port]">--}}
                                            {{--port--}}
                                        {{--</label>--}}
                                        {{--<input type="text" id="binance[proxy][port]" name="binance[proxy][port]"--}}
                                               {{--class="form-control input-sm"--}}
                                               {{--value="{{isset($binanceConfig['proxy']['port']) ? $binanceConfig['proxy']['port'] : null}}">--}}
                                    {{--</li>--}}
                                    {{--<li class="mt-3">--}}
                                        {{--<label for="binance[proxy][username]">--}}
                                            {{--Username--}}
                                        {{--</label>--}}
                                        {{--<input type="text" id="binance[proxy][username]" name="binance[proxy][username]"--}}
                                               {{--class="form-control input-sm"--}}
                                               {{--value="{{isset($binanceConfig['proxy']['username']) ? $binanceConfig['proxy']['username'] : null}}">--}}
                                    {{--</li>--}}
                                    {{--<li class="mt-3">--}}
                                        {{--<label for="binance[proxy][password]">--}}
                                            {{--Password--}}
                                        {{--</label>--}}
                                        {{--<input type="text" id="binance[proxy][password]" name="binance[proxy][password]"--}}
                                               {{--class="form-control input-sm"--}}
                                               {{--value="{{isset($binanceConfig['proxy']['password']) ? $binanceConfig['proxy']['password'] : null}}">--}}
                                    {{--</li>--}}
                                {{--</ul>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>

                    <div class="col-md-2 offset-md-5 mb-5">
                        <button class="col-12 btn btn-info">Save Changes</button>
                    </div>

                </form>
            </div>
            <div class="col-md-3">
                <h4>Services</h4>
                <a href="{{route('systemCtl',['stop','all'])}}"
                   class="btn btn-outline-secondary fa fa-2x fa-stop text-danger"></a>
                {{--<button class="btn btn-outline-secondary ml-md-5 fa fa-2x fa-refresh text-info"></button>--}}
                <a href="{{route('systemCtl',['start','all'])}}"
                   class="btn btn-outline-secondary ml-md-5 fa fa-2x fa-play text-success"></a>
                <div class="col-12 mt-3">
                    <p>
                        Ticker Daemon
                        : {!! $tickerStatus ? '<span class="text-success">Running</span>' : '<span class="text-danger">Stopped</span>' !!}
                    </p>
                    <p>
                        Orders Daemon
                        : {!! $ordersStatus ? '<span class="text-success">Running</span>' : '<span class="text-danger">Stopped</span>' !!}
                    </p>
                    <p>
                        Signal Daemon
                        : {!! $signalStatus ? '<span class="text-success">Running</span>' : '<span class="text-danger">Stopped</span>' !!}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleProxy() {
            var x = document.getElementById("proxy");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>

@endsection