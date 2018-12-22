<div class="row mb-2">
    <div class="col-md-2">
        <div class="ui-widget">
            <input class="col-12 form-control" id="pair" placeholder="type in symbol"
                   @if(isset($order)) value="{{$order->symbol}}" disabled @endif>
        </div>
    </div>
    <div class="col-md-2">
        <input class="form-control col-12" type="text" id="quantity" value="10" placeholder="quantity"
               @if(isset($order)) value="{{$order->origQty}}" disabled @endif>
    </div>
    <div class="col-md-2">
        <input class="form-control col-12" style="background: #0080001c;" type="number" id="tp" placeholder="TP%"
               @if(isset($order)) value="{{$order->takeProfit}}" @endif>
    </div>
    <div class="col-md-2">
        <input class="form-control col-12" style="background: #ff4a682b;" type="number" id="sl" placeholder="SL%"
               @if(isset($order)) value="{{$order->stopLoss}}" @endif>
    </div>
    <div class="col-md-1">
        <input class="form-control col-12" style="background: #0080001c;" type="number" id="ttp" placeholder="TTP%"
               @if(isset($order)) value="{{$order->trailingTakeProfit}}" @endif>
    </div>
    <div class="col-md-1">
        <input class="form-control col-12" style="background: #ff4a682b;" type="number" id="tsl" placeholder="TSL%"
               @if(isset($order)) value="{{$order->trailingStopLoss}}" @endif>
    </div>
    <div class="col-md-2">
        @if(isset($order))
            <button onclick="savePosition()" class="btn btn-success" id="savePositionBtn">
                Save
            </button>
            <button onclick="cancelEdit()" class="btn btn-primary" id="savePositionBtn">
                Cancel/New
            </button>
        @else
            <button onclick="openPosition()" class="btn btn-primary">
                Buy
            </button>
        @endif
    </div>
</div>


<script>
    function cancelEdit() {
        var redirectUrl = "{{route('positions')}}";
        document.location.href = redirectUrl;
    }
    function savePosition() {
        $("#savePositionBtn").attr('disabled','disabled');
        var url = "{{route('savePosition')}}";
        var redirectUrl = "{{route('positions')}}";
        var orderId = "{{isset($order) ? $order->id : ''}}";
        var pair = document.getElementById("pair").value;
        var quantity = document.getElementById("quantity").value;
        var tp = document.getElementById("tp").value ? document.getElementById("tp").value : 0;
        var ttp = document.getElementById("ttp").value ? document.getElementById("ttp").value : 0;
        var sl = document.getElementById("sl").value ? document.getElementById("sl").value : 0;
        var tsl = document.getElementById("tsl").value ? document.getElementById("tsl").value : 0;

        axios.post(url, {
            id: orderId,
            symbol: pair,
            tp: tp,
            sl: sl,
            ttp: ttp,
            tsl: tsl
        }).then(function (response) {
            document.location.href = redirectUrl;
        }).catch(function (error) {
            console.log(error);
        });
    }

    function openPosition() {
        var pair = document.getElementById("pair").value;
        var quantity = document.getElementById("quantity").value;
        var tp = document.getElementById("tp").value ? document.getElementById("tp").value : 0;
        var ttp = document.getElementById("ttp").value ? document.getElementById("ttp").value : 0;
        var sl = document.getElementById("sl").value ? document.getElementById("sl").value : 0;
        var tsl = document.getElementById("tsl").value ? document.getElementById("tsl").value : 0;
        window.location.href = '/positions/new/' + pair + "/" + quantity + "/" + tp + "/" + sl + "/" + ttp + "/" + tsl;
    }
</script>
<script>
    $(function () {
        var availableTags = {!! json_encode(array_keys(json_decode(\Illuminate\Support\Facades\Cache::get('prices'),true))) !!};
        $("#pair").autocomplete({
            source: availableTags,
            autoFill: true,
            select: function (event, ui) {   //must be cleared with function parameter
                var pair = ui.item.label;
                openTV(pair);
            }
        });
    });
</script>