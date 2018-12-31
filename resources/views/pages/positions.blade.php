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

    <style>

        .tp, .sl {
            height: 27px;
            display: inline-block;
            padding: 0px;
            margin: 30px 0px 0px ;
        }

        .bar {
            font-size: 8pt;
            width: 100%;
            display: flex;
            height: 100px;
            margin-right: auto;
            padding-right: 50px;
        }

        .note-text {
            width: 0px;
            display: inline-block;
            position: relative;
            left: 60px;
            /* background: linear-gradient(to right, transparent 0%, transparent calc(50% - 0.81px), black calc(50% - 0.8px), black calc(50% + 0.8px), transparent calc(50% + 0.81px), transparent 100%); */
            height: 25px;
            border-left: 1px solid rgba(152, 152, 152, 0.28);
        }

        .note-text.current {
            border-left: 1px solid rgba(152, 152, 152, 0.74);
        }

        div.tp.round > div.note-text.target:last-child {
            border-left: 0 !important;
        }

        div.note-text.tslpoint {
            border-left: 0 !important;
        }

        .note-text span {
            width: 69px !important;
        }

        .note-text .text-top {
            display: inline-block;
            position: absolute;
            top: -20px;
        }

        .note-text .text-bottom {
            position: relative;
            bottom: -30px;
            display: inline-block;
        }

        .text-buy .text-bottom {
            text-align: center;
        }

        .round-left {
            border-radius: 25px 0 0 25px;
            border-right: 0 !important;
        }

        .round {
            border: 1px solid #989898;
        }

        .round-right {
            border-radius: 0 25px 25px 0;
            border-left: 0 !important;
        }

        .tp {
            /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#8c863d54+0,518a4f+100 */
            background: #8c863d54; /* Old browsers */
            background: -moz-linear-gradient(left, #8c863d54 0%, #518a4f 100%); /* FF3.6-15 */
            background: -webkit-linear-gradient(left, #8c863d54 0%, #518a4f 100%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(to right, #8c863d54 0%, #518a4f 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#8c863d54', endColorstr='#518a4f', GradientType=1); /* IE6-9 */
        }

        .sl {
            /* Permalink - use to edit and share this gradient: http://colorzilla.com/gradient-editor/#c1300391+0,8c863d54+100 */
            background: #c1300391; /* Old browsers */
            background: -moz-linear-gradient(left, #c1300391 0%, #8c863d54 100%); /* FF3.6-15 */
            background: -webkit-linear-gradient(left, #c1300391 0%, #8c863d54 100%); /* Chrome10-25,Safari5.1-6 */
            background: linear-gradient(to right, #c1300391 0%, #8c863d54 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
            filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#c1300391', endColorstr='#8c863d54', GradientType=1); /* IE6-9 */
        }

        .tp .reached span {
            color: #0b7b00;
        }

        .tp span, .sl span, .text-buy span {
            color: grey;
        }

        .text-buy span {
            left: -30px;
            /*color: #f7f7f7 !important;*/
        }

        span.text-percent {
            display: inline-block;
            font-size: 9pt;
            position: absolute;
            top: 4px;
            left: 2px;
            color: #d2d2d2ba !important;
        }

        .note-text.current span {
            color: #f3f3f3 !important;
        }

        .current .text-top {
            top: -35px !important;;
        }

        .current .text-bottom {
            bottom: -45px !important;;
        }
    </style>

    <template id="tradeTemplate">
        <div class="bar">
            <div style="width: 50%" class="sl round round-left">
                <div class="note-text current" style="">
                    <span class="text-top"><i class="fa fa-arrow-circle-down"></i> Current</span>
                    <span class="text-percent current">3.34%</span>
                    <span class="text-bottom ">120.54 </span>
                </div>

                <div class="note-text slpoint" style="left: 20%;">
                    <span class="text-top">SL</span>
                    <span class="text-percent">2.40%</span>
                    <span class="text-bottom ">50.24 </span>
                </div>
                <div class="note-text tslpoint" style="left: calc(0% - 8px);">
                    <span class="text-top">TSL</span>
                    <span class="text-percent">3.40%</span>
                    <span class="text-bottom">40.24 </span>
                </div>
            </div>
            <div style="width: 50%" class="tp round round-right">
                <div class="note-text text-buy" style="position: relative;">
                    <span class="text-top">BUY Price</span>
                    <span class="text-bottom">85.24 </span>
                </div>
                <div class="note-text target" style="left: 25%;">
                    <span class="text-top"><i class="fa fa-check-circle"></i> TP 1</span>
                    <span class="text-percent">2.3%</span>
                    <span class="text-bottom ">95.24 </span>
                </div>
            </div>
        </div>
    </template>

    <script>
        window.debugme = false;
        window.roundCrypto = function(number, precision = 8) {
            number = parseFloat(number);
            if (number > 1) {
                return number.toFixed(2);
            }
            return number.toFixed(precision);
        };
        (function countdown(remaining) {
            if (remaining <= 0) {
                axios.get('/positions/table/open').then(function (response) {
                    $("#tableContainer").html(response.data);
                    $(".graphRow").each(function () {
                        drawTrade($(this).find('.graph'),$(this).attr('data-buy'),$(this).attr('data-pl'),[$(this).attr('data-tp')],$(this).attr('data-sl'),$(this).attr('data-tsl'));
                    });
                    if (!debugme){
                        remaining = 5;
                    }
                    else {
                        remaining = 1000;
                    }
                }).then(function (error) {
                    console.log(error);
                });
            }
            setTimeout(function () {
                countdown(remaining - 1);
            }, 1000);
        })(0);


        function drawTrade(element, buy, pl, _tp, sl, tsl) {
            pl = pl.toString().substring(0, pl.length - 1);

            var tpl = $("#tradeTemplate");
            element.html(tpl.clone().html());

            tsl = parseFloat(sl) + parseFloat(tsl);
            var tpLast = Math.max.apply(null, _tp);
            var tpWidth = parseInt(element.find('div.tp.round').css('width'));
            var slWidth = parseInt(element.find('div.sl.round').width());


            var percentProfit = (tpLast * 100 ) / (tpLast + tsl) ;
            console.log(percentProfit);
            element.find(".tp.round").css('width', percentProfit + '%');
            element.find(".sl.round").css('width', 100 - percentProfit + '%');


            var buyDiv = element.find('div.text-buy');
            buyDiv.css('left', '0');
            element.find('div.text-buy .text-bottom').html(roundCrypto(buy));


            var currentDiv = element.find('div.note-text.current');
            element.find('div.note-text.current .text-percent').html(Math.round(pl * 100) / 100 + "%");
            if (pl > 0) {
                var currentPercent = (Math.abs(pl) * 100 / tpLast);
                currentDiv.css('left',element.find('div.sl.round').width() + (element.find('div.tp.round').width() * currentPercent / 100));
                element.find('div.note-text.current .text-bottom').html(Math.round(buy + (buy * Math.abs(pl) / 100)));
            }
            else {
                var currentPercent = 100 - (Math.abs(pl) * 100 / tsl);
                currentDiv.css('left', currentPercent + '%');
                element.find('div.note-text.current .text-bottom').html(roundCrypto(buy - (buy * Math.abs(pl) / 100)));
            }

            var slDiv = element.find('div.sl .note-text.slpoint');
            slDiv.css('left', 100 - (sl * 100 / tsl) + '%');
            slDiv.find('.text-percent').html(sl + "%");
            slDiv.find('.text-bottom').html(roundCrypto(buy - (buy * Math.abs(sl) / 100)));

            var tslDiv = element.find('div.sl .note-text.tslpoint');
            tslDiv.css('left', 'calc(0% - 8px)');
            tslDiv.find('.text-percent').html(tsl + "%");
            tslDiv.find('.text-bottom').html(roundCrypto(buy - (buy * Math.abs(tsl) / 100)));

            // var targetTpl = $(".note-text.target").clone();
            element.find(".note-text.target .text-percent").html(_tp[0]);
            element.find(".note-text.target:not(.note-final)").css('left',(_tp[0] * 100) / tpLast + '%');
            if(pl > (_tp[0])){
                element.find(".note-text.target:not(.note-final)").addClass('reached');
            }else {
                element.find(".note-text.target:not(.note-final)").removeClass('reached');
            }
            element.find(".note-text.target:not(.note-final)").find('.text-bottom').html(roundCrypto(buy - (buy * Math.abs(_tp[0] / 100))));


        }
    </script>

@endsection