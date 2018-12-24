<div class="col-12">
    <h2>
        Mining Hamster
    </h2>

    <form method="post" class="row">
        {{csrf_field()}}
        <div class="col-6">
            <h2>
                Status :
                @if($signals)
                    <span class="btn-outline-success">Working</span>
                @else
                    <span class="btn-outline-danger">Stopped</span>
                @endif
            </h2>
        </div>
        <div class="col-6">
            <label for="api-key">
                Api Key :
            </label>
            <input type="text" id="api-key" name="apiKey" class="form-control" value="{{$config['apiKey'] ?? ''}}">
        </div>
        <div class="col-6">
            <label class="mt-3">
                Exchanges :
            </label>
            <div class="row">
                <div class="col-3">
                    Bittrex <input type="checkbox" name="exchange[bittrex]" value="1"
                                   @if(in_array('bittrex',$config['exchange'] ?? [])) checked @endif>
                </div>
                <div class="col-3">
                    Poloniex <input type="checkbox" name="exchange[poloniex]" value="1"
                                    @if(in_array('poloniex',$config['exchange'] ?? [])) checked @endif>
                </div>
                <div class="col-3">
                    Binance <input type="checkbox" name="exchange[binance]" value="1"
                                   @if(in_array('binance',$config['exchange'] ?? [])) checked @endif>
                </div>
                <div class="col-3">
                    Kucoin <input type="checkbox" name="exchange[kucoin]" value="1"
                                  @if(in_array('kucoin',$config['exchange'] ?? [])) checked @endif>
                </div>
            </div>
        </div>
        <div class="col-6">
            <label class="mt-3">
                Market Volume (BTC/ETH/USDT/BNB):
            </label>
            <select id="outputvolume" class="form-control" name="volume">
                <option value="0">all</option>
                <option value="5">&gt;5 Marketvolume</option>
                <option value="10">&gt;10 Marketvolume</option>
                <option value="20">&gt;20 Marketvolume</option>
                <option value="30">&gt;30 Marketvolume</option>
                <option value="40">&gt;40 Marketvolume</option>
                <option value="50">&gt;50 Marketvolume</option>
                <option value="100">&gt;100 Marketvolume</option>
                <option value="200">&gt;200 Marketvolume</option>
                <option value="300">&gt;300 Marketvolume</option>
                <option value="400">&gt;400 Marketvolume</option>
                <option value="500">&gt;500 Marketvolume</option>
                <option value="1000">&gt;1000 Marketvolume</option>
                <option value="2000">&gt;2000 Marketvolume</option>
                <option value="3000">&gt;3000 Marketvolume</option>
                <option value="4000">&gt;4000 Marketvolume</option>
                <option value="5000">&gt;5000 Marketvolume</option>
                <option value="10000">&gt;10000 Marketvolume</option>
            </select>
        </div>

        <div class="col-6 offset-3 mt-3">
            <button type="submit" class="col-12 btn btn-primary">
                Save
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('#outputvolume').val("{{$config['volume'] ?? 0}}");
    });
</script>