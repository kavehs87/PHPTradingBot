<form class="col-md-12 row" method="post" action="">
    {{csrf_field()}}
    <div class="input-group col-md-4">
        <label>
            Traceable orders :
        </label>
        <div class="col-md-12">
            <input type="checkbox" value="1" name="traceable[profit]"
                   @if(isset($config['traceable']['profit']) && $config['traceable']['profit']) checked @endif> Profit
            <br/>
            <input type="checkbox" value="1" name="traceable[loss]"
                   @if(isset($config['traceable']['loss']) && $config['traceable']['loss']) checked @endif> Loss <br/>
        </div>
    </div>

    <div class="input-group col-md-4">
        <label>
            Maximum Samples to take :
        </label>
        <div class="col-md-12">
            <input type="text" name="maxSamples" class="form-control" value="{{$config['maxSamples'] ?? '' }}">
        </div>
    </div>

    <div class="input-group col-md-4">
        <label>
            Take sample interval (minutes) :
        </label>
        <div class="col-md-12">
            <input type="text" name="interval" class="form-control" value="{{$config['interval'] ?? ''}}">
        </div>
    </div>

    <div class="input-group col-md-4">
        <label>
            Only orders within x number of days
        </label>
        <div class="col-md-12">
            <input style="width: 5em;" type="text" name="days" class="form-control" value="{{$config['days'] ?? ''}}">
        </div>
    </div>


    <div class="col-12">
    <h4 class="mt-5">buy conditions</h4>
        <table class="table table-responsive table-hover">
            <thead>
            <tr>
                <th>
                    Enabled
                </th>
                <th>
                    Condition
                </th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="checkbox" name="lastXSamplesEnabled"
                           @if(isset($config['lastXSamplesEnabled'])) checked @endif value="1">
                </td>
                <td>
                    buy new order if last <input style="width: 3em;" type="text" name="averageSamples"  value="{{$config['averageSamples'] ?? ''}}"> samples were increasing and average increase were >=
                    <input style="width: 3em;" type="text" name="average" value="{{$config['average'] ?? ''}}"> %
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="col-md-8 offset-2 mt-4">
        <button type="submit" class="col-md-12 btn btn-primary">
            Save
        </button>
    </div>
</form>