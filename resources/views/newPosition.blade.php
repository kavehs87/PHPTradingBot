<div class="col-12">
    <div class="mt-3"></div>
    <h2>
        Open Position
    </h2>

    <div class="row">
        <div class="col-12">
            Select Pair
        </div>
        {{--<div class="col-12">--}}
        {{--<select id="pair">--}}
        {{--@if(\Illuminate\Support\Facades\Cache::get('prices') != null)--}}
        {{--@foreach(json_decode(\Illuminate\Support\Facades\Cache::get('prices'),true) as $pair => $price)--}}
        {{--<option>{{$pair}}</option>--}}
        {{--@endforeach--}}
        {{--@endif--}}
        {{--</select>--}}
        {{--</div>--}}
        <div class="col-12">
            <div class="ui-widget">
                <label for="pair">Pair : </label>
                <input id="pair">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            Quantity
        </div>
        <div class="col-12">
            <input type="text" id="quantity" value="10">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="mt-2"></div>
            <button onclick="openPosition()" class="btn btn-primary">
                Buy
            </button>
        </div>
    </div>
</div>

<script>
    function openPosition() {
        var pair = document.getElementById("pair").value;
        var quantity = document.getElementById("quantity").value;
        window.location.href = '/positions/new/' + pair + "/" + quantity;
    }
</script>
<script>
    $(function () {
        var availableTags = {!! json_encode(array_keys(json_decode(\Illuminate\Support\Facades\Cache::get('prices'),true))) !!};
        $("#pair").autocomplete({
            source: availableTags
        });
    });
</script>