<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/23/18
 * Time: 10:41
 */

namespace App\Modules;


use App\Modules;
use App\Order;
use App\Price;
use Carbon\Carbon;
use DOMDocument;
use Illuminate\Support\Facades\Cache;

class CoinMarketCap extends Modules
{
    public static $description = 'screens coins and tokens from coinmarketcap';


    public function menus()
    {
        return [[
            'route' => 'coinMarketCap',
            'text' => 'CoinMarketCap',
            'module' => 'CoinMarketCap'
        ]];
    }

    public function coinMarketCapPage()
    {
        $coins = $this->loadCoinMarketCap();


        view()->addNamespace('CoinMarketCap', app_path('Modules/CoinMarketCap/view'));
        return view('CoinMarketCap::cmt_table', [
            'coins' => $coins
        ]);
    }


    public function loadCoinMarketCap()
    {
        $binance_pairs = array_keys(json_decode(Cache::get('prices'), true) ?? []);

        libxml_use_internal_errors(true);

        if (!Cache::has('cmk')) {
            $url = 'https://coinmarketcap.com/coins/views/all/';
            $content = file_get_contents($url);
            Cache::put('cmk', $content, Carbon::now()->addMinutes(5));
        } else {
            $content = Cache::get('cmk');
        }

        $dom = new DOMDocument();
        $dom->loadHTML($content);

        $allCoinsTable = $dom->getElementById('currencies-all');
        $tbody = $allCoinsTable->getElementsByTagName('tbody');
        $r = 1;
        $row = [];
        foreach ($tbody->item(0)->childNodes as $tr) {

            $nodeType = $tr->nodeType;
            if ($nodeType == 1) {
                $tds = $tr->getElementsByTagName('td');
                $coin = trim($tds->item(2)->nodeValue) . 'BTC';
                for ($i = 0; $i < $tds->length; $i++) {
                    if (in_array($coin, $binance_pairs) || $coin == 'BTCBTC') {
                        if ($i != 10) {
                            $row[$r][] = trim($tds->item($i)->nodeValue);
                        }
                    }
                }
            }
            $r++;
        }
        return $row;
    }
}