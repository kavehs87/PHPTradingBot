<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/11/18
 * Time: 21:28
 */

namespace App;


use App\Mail\SignalReceived;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Signal extends Model
{
    protected $guarded = [];


    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if ($model->signalmode == 'buy') {
                $symbol = TradeHelper::market2symbol($model->market);

                /*
                 * Module Signals received hook
                 */
                $activeModules = Modules::getActiveModules();
                if ($activeModules) {
                    foreach ($activeModules as $module) {
                        $module->getFactory()->onSignalReceived($model);
                    }
                }
                $defaultQuantity = Setting::getValue('orderDefaults')['amount'];
                Order::buy($symbol, $defaultQuantity, '', [
                    'signal_id' => $model->signalID
                ]);
            }
        });
    }
}