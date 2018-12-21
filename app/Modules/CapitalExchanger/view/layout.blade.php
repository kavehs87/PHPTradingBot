<div class="container-fluid">
    <div class="mt-2"></div>

    <h2>
        Capital Mover
    </h2>

    <hr>

    <form action="" class="col-md-4">
        <div class="input-group">
            <label for="source" class="col-12">
                Source Asset :
            </label>
            <select name="source" id="source" class="form-control">
                <option value="">USDT</option>
                <option value="">BTC</option>
                <option value="">ETH</option>
                <option value="">BNB</option>
            </select>
        </div>
        <div class="mt-5"></div>
        <div class="input-group">
            <label for="rule" class="col-12">
                Enable Auto Buyer
                <small>if order has not enough source coin</small>
            </label>

            <select name="rule" id="rule" class="form-control">
                <option value="1">YES - Buy the required source asset for orders to fill</option>
                <option value="1">No - let order fail</option>
            </select>
        </div>
    </form>
</div>