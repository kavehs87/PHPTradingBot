<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/25/18
 * Time: 01:26
 */

namespace App\Modules;


use App\Modules;
use App\Signal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class MiningHamsterSignals extends Modules
{
    public static $description = 'MiningHamster Signals Module';

    public function menus()
    {
        return [
            [
                'route' => 'MiningHamsterSignals',
                'text' => 'Mining Hamster',
                'module' => 'MiningHamsterSignals'
            ],
        ];
    }

    public function MiningHamsterSignalsPage(Request $request)
    {
        if ($request->isMethod('post')) {
            if ($request->get('exchange') == null) {
                return redirect()->back()->withErrors('at least one exchange should be selected');
            }
            $exchange = array_keys($request->get('exchange'));
            $volume = $request->get('volume');
            $apiKey = $request->get('apiKey');

            $this->setConfig([
                'exchange' => $exchange,
                'volume' => $volume,
                'apiKey' => $apiKey
            ]);

            return redirect()->back();

        } else {
            view()->addNamespace('MiningHamsterSignals', app_path('Modules/MiningHamsterSignals/view'));
            return view('MiningHamsterSignals::setting', [
                'config' => $this->getConfig(),
                'signals' => $this->getSignals()
            ]);
        }
    }

    public function signalLoop()
    {
        $this->_getRiskLevels();
        $this->_getSignals();

        $config = $this->getConfig();
        if (!$config)
            return false;


        if (!empty($signals = $this->getSignals())) {
            foreach ($signals as $signal) {
                if ($config['volume'] > $signal['basevolume'])
                    continue;

                if (!in_array($signal['exchange'], $config['exchange'] ?? []))
                    continue;

                $signal['module'] = 'MiningHamster';
                Signal::firstOrCreate([
                    'market' => $signal['market'],
                    'lastprice' => $signal['lastprice'],
                    'signalmode' => $signal['signalmode']
                ], (array)$signal);
            }
        }
    }

    public function getSignals()
    {
        $signals = json_decode(Cache::get('signal'), true) ?? null;
        $riskLevels = Cache::get('riskLevels') ?? null;
        $signalsWithRiskLevels = [];
        if ($signals) {
            foreach ($signals as $i => $signal) {
                $signalsWithRiskLevels[$i] = $signal;
                $signalsWithRiskLevels[$i]['rl'] = $riskLevels[$signal['exchange'] . '-' . $signal['market']]->risklevel;
            }
        }
        return $signalsWithRiskLevels;
    }


    protected function _getSignals()
    {
        $config = $this->getConfig();
        $apikey = $config['apiKey'] ?? null;
        $uri = "https://www.mininghamster.com/api/v2/" . $apikey;
        $sign = hash_hmac('sha512', $uri, $apikey);
        $ch = curl_init($uri);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:' . $sign));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $execResult = curl_exec($ch);
        $obj = json_decode($execResult);
        if ($obj) {
            Cache::put('signal', json_encode($obj), Carbon::now()->addSeconds(10));
        }
        return $obj;
    }

    protected function _getRiskLevels()
    {
        $urlRL = "https://www.mininghamster.com/api/v2/risklevel/ticker";
        $riskLevelRawContent = file_get_contents($urlRL);
        $riskLevels = json_decode($riskLevelRawContent);
        $riskLevelsAssoc = [];
        foreach ($riskLevels->risklevel as $riskLevel) {
            $riskLevelsAssoc[$riskLevel->exchange . '-' . $riskLevel->market] = $riskLevel;
        }
        Cache::put('riskLevels', $riskLevelsAssoc, Carbon::now()->addMinutes(5));
        return $riskLevels;
    }
}