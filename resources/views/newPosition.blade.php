<div class="col-12">
    <h2>
        Open Position
    </h2>

    <div class="row">
        <div class="col-2">
            Select Pair
        </div>
        <div class="col-10">
            <select id="pair">
                @if(\Illuminate\Support\Facades\Cache::get('prices') != null)
                    @foreach(json_decode(\Illuminate\Support\Facades\Cache::get('prices'),true) as $pair => $price)
                        <option>{{$pair}}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-2">
            Quantity
        </div>
        <div class="col-10">
            <input type="text" id="quantity" value="10">
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <button onclick="openPosition()">
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