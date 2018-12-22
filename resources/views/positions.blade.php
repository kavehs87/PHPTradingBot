@extends('layout')

@section('body')

    <div class="col-12">
        @include('newPosition')
        @include('tv')
    </div>

    <div id="tableContainer"></div>

    <script>
        (function countdown(remaining) {
            if (remaining <= 0) {
                axios.get('/positions/table/open').then(function (response) {
                    $("#tableContainer").html(response.data);
                    remaining = 5;
                });
            }
            // document.getElementById('countdown').innerHTML = remaining;
            setTimeout(function () {
                countdown(remaining - 1);
            }, 1000);
        })(0);

    </script>



    {{--<div class="row">--}}
    {{--<div class="col-md-4">@include('orderDefaults')</div>--}}
    {{--</div>--}}
@endsection