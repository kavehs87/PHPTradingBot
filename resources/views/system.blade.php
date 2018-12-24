@extends('layout')

@section('body')
    <div class="col-12">
        <div class="alert alert-primary" role="alert">
            <code>
                sh services.sh
            </code> Command starts all daemons
        </div>
    </div>
    <div class="col-4">
        <h2>
            Prices Daemon
        </h2>
        @if(!$lastPrices)
            <p class="bg-danger">
                Price Daemon is Stopped<br/>
                php artisan daemon:price<br/>
                or check VPN connection
            </p>
        @else
            <p class="bg-success">
                All Good got {{count($lastPrices)}} market prices.
            </p>
        @endif
    </div>

    <div class="col-12">

        <div class="mt-5"></div>

        <div class="row">
            <form action="{{route('saveSettings')}}" method="post" class="col-md-6">
                {{csrf_field()}}
                <h3>
                    Binance
                </h3>
                <div class="form-group">
                    <label for="binance[api]">
                        Api Key
                    </label>
                    <input type="password" id="binance[api]" name="binance[api]" class="input-group"
                           value="{{$binanceConfig['api']}}">
                </div>
                <div class="form-group">
                    <label for="binance[secret]">
                        Api Secret
                    </label>
                    <input type="password" id="binance[secret]" name="binance[secret]" class="input-group"
                           value="{{$binanceConfig['secret']}}">
                </div>
                <div class="form-group">
                    <label for="binance[proxyEnabled]">
                        Use Proxy
                    </label>
                    <input type="checkbox" id="binance[proxyEnabled]" name="binance[proxyEnabled]" class="input-group"
                           value="1" onclick="toggleProxy();"
                           @if(isset($binanceConfig['proxyEnabled']) && $binanceConfig['proxyEnabled']) checked @endif>
                </div>
                <div id="proxy"
                     @if(!isset($binanceConfig['proxyEnabled']) || $binanceConfig['proxyEnabled'] == false) style="display: none;" @endif>
                    <div class="form-group">
                        <label for="binance[proxy][proto]">
                            Protocol
                        </label>
                        <select type="text" id="binance[proxy][proto]" name="binance[proxy][proto]" class="input-group">
                            <option @if(!isset($binanceConfig['proxy']['proto']) || $binanceConfig['proxy']['proto'] == 'http') selected @endif>
                                http
                            </option>
                            <option @if(!isset($binanceConfig['proxy']['proto']) || $binanceConfig['proxy']['proto'] == 'https') selected @endif>
                                https
                            </option>
                            <option @if(!isset($binanceConfig['proxy']['proto']) || $binanceConfig['proxy']['proto'] == 'socks4') selected @endif>
                                socks4
                            </option>
                            <option @if(!isset($binanceConfig['proxy']['proto']) || $binanceConfig['proxy']['proto'] == 'socks5') selected @endif>
                                socks5
                            </option>
                            <option @if(!isset($binanceConfig['proxy']['proto']) || $binanceConfig['proxy']['proto'] == 'socks5h') selected @endif>
                                socks5h
                            </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="binance[proxy][host]">
                            Host
                        </label>
                        <input type="text" id="binance[proxy][host]" name="binance[proxy][host]" class="input-group"
                               value="{{isset($binanceConfig['proxy']['host']) ? $binanceConfig['proxy']['host'] : null}}">
                    </div>
                    <div class="form-group">
                        <label for="binance[proxy][port]">
                            Port
                        </label>
                        <input type="text" id="binance[proxy][port]" name="binance[proxy][port]" class="input-group"
                               value="{{isset($binanceConfig['proxy']['port']) ? $binanceConfig['proxy']['port'] : null}}">
                    </div>
                    <div class="form-group">
                        <label for="binance[proxy][username]">
                            Username
                        </label>
                        <input type="text" id="binance[proxy][username]" name="binance[proxy][username]"
                               class="input-group"
                               value="{{isset($binanceConfig['proxy']['username']) ? $binanceConfig['proxy']['username'] : null}}">
                    </div>
                    <div class="form-group">
                        <label for="binance[proxy][password]">
                            Password
                        </label>
                        <input type="text" id="binance[proxy][password]" name="binance[proxy][password]"
                               class="input-group"
                               value="{{isset($binanceConfig['proxy']['password']) ? $binanceConfig['proxy']['password'] : null}}">
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary col-12">
                        Save
                    </button>
                </div>
            </form>
            <div class="col-6">
                <div class="col-3">
                    <h2>
                        Balances
                    </h2>
                    @if(!empty($balances))
                        @foreach($balances as $coin => $balance)
                            <div class="balance">
                <span>
                    {{$coin}} :
                </span>
                                <strong>
                                    {{$balance['available']}}
                                </strong>
                            </div>
                        @endforeach
                    @endif
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