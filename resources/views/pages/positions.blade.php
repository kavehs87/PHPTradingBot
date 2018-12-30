@extends('layouts.app')

@section('content')

    <div id="tableContainer"></div>

    <div class="row">
        <div class="col-md-9 pr-0">
            @include('parts.tv')
        </div>
        <div class="col-md-3">
            @include('parts.newPosition')
        </div>
    </div>

    <script>
        (function countdown(remaining) {
            if (remaining <= 0) {
                axios.get('/positions/table/open').then(function (response) {
                    $("#tableContainer").html(response.data);
                    remaining = 5;
                }).then(function (error) {
                    console.log(error);
                });
            }
            setTimeout(function () {
                countdown(remaining - 1);
            }, 1000);
        })(0);

    </script>
@endsection