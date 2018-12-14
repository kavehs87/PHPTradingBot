<?php

namespace App\Console\Commands;

use App\Setting;
use App\Signal;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class Signals extends Command
{
    protected $sleepInterval = 1;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daemon:signals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Polls signals from mining hamster and stores it into database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $miningHamster = Setting::getValue('miningHamster');
        if (isset($miningHamster['api'])) {
            $apikey = $miningHamster['api'];
        } else {
            return -1;
        }

        while (true) {
            $uri = "https://www.mininghamster.com/api/v2/$apikey";
            $sign = hash_hmac('sha512', $uri, $apikey);
            $ch = curl_init($uri);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('apisign:' . $sign));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $execResult = curl_exec($ch);
            $obj = json_decode($execResult);
            if ($obj) {
                foreach ($obj as $signal) {
                    Signal::firstOrCreate([
                        'market' => $signal->market,
                        'lastprice' => $signal->lastprice,
                        'signalmode' => $signal->signalmode
                    ], (array)$signal);
                }
                Cache::put('signal', json_encode($obj), Carbon::now()->addSeconds(10));
            }
            sleep($this->sleepInterval);
        }
        return 0;
    }


}
