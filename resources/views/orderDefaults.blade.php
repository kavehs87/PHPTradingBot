<style>
    .input-group label {
        width: 180px;
        min-width: 180px;
    }
</style>
<div class="col-12">
    <div class="mt-3"></div>
    <h2>
        Default settings for new orders
    </h2>

    <form class="row" method="post" action="{{route('saveOrderDefaults')}}">
        {{csrf_field()}}
        <div class="input-group">
            <label for="tp">
                Target Profit :
            </label>
            <input type="text" name="orderDefaults[tp]" id="tp" class="form-group" value="{{isset(\App\Setting::getValue('orderDefaults')['tp']) ? \App\Setting::getValue('orderDefaults')['tp'] : 3}}">
        </div>
        <div class="input-group">
            <label for="sl">
                Stop Loss :
            </label>
            <input type="text" name="orderDefaults[sl]" id="sl" class="form-group" value="{{isset(\App\Setting::getValue('orderDefaults')['sl']) ? \App\Setting::getValue('orderDefaults')['sl'] : 2}}">
        </div>
        <div class="input-group">
            <label for="ttp">
                Trailing Target Profit :
            </label>
            <input type="text" name="orderDefaults[ttp]" id="ttp" class="form-group" value="{{isset(\App\Setting::getValue('orderDefaults')['ttp']) ? \App\Setting::getValue('orderDefaults')['ttp'] : 1}}">
        </div>
        <div class="input-group">
            <label for="tsl">
                Trailing Stop Loss :
            </label>
            <input type="text" name="orderDefaults[tsl]" id="tsl" value="{{isset(\App\Setting::getValue('orderDefaults')['tsl']) ? \App\Setting::getValue('orderDefaults')['tsl'] : 1}}">
        </div>
        <input type="submit" value="Save" class="btn btn-primary col-2">
    </form>
</div>

<script>
    function openPosition() {
        var pair = document.getElementById("pair").value;
        var quantity = document.getElementById("quantity").value;
        window.location.href = '/positions/new/' + pair + "/" + quantity;
    }
</script>