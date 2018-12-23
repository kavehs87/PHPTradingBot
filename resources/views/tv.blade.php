<style>
    .tradingview-widget-container {

    }

    #tradingview_b42a6 {
        width: 100%;
        min-height: 600px;
        height: 600px;
    }
</style>

<!-- TradingView Widget BEGIN -->
<div class="tradingview-widget-container">
    <div id="tradingview_b42a6"></div>
    <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
    <script type="text/javascript">
        var edit = {{isset($order) ? 1 : 0}}
                var symbol = "{{isset($order) ? $order->symbol : ''}}";
        $(document).ready(function () {
            if (edit) {
                openTV(symbol);
            }
            else {
                openTV('BTCUSDT');
            }
        });
        function openTV(symbol) {
            new TradingView.widget(
                {
                    "autosize": true,
                    "symbol": "BINANCE:" + symbol,
                    "interval": "60",
                    "timezone": "Etc/UTC",
                    "theme": "Dark",
                    "style": "1",
                    "locale": "en",
                    "toolbar_bg": "#f1f3f6",
                    "enable_publishing": false,
                    "hide_side_toolbar": false,
                    "allow_symbol_change": false,
                    "details": true,
                    "studies": [
                        "MACD@tv-basicstudies",
                        "RSI@tv-basicstudies",
                        "BB@tv-basicstudies"
                    ],
                    "container_id": "tradingview_b42a6"
                }
            );
        }
    </script>
</div>
<!-- TradingView Widget END -->
