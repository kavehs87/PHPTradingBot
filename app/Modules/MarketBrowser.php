<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/14/18
 * Time: 23:19
 */

namespace App\Modules;


use App\Modules;

class MarketBrowser extends Modules
{
    public static $description = 'Lists Market pair in a more friendly fashion.';


    public function menus()
    {
        return [
            [
                'route' => 'browse',
                'text' => 'Browse Markets',
                'module' => $this->class
            ]
        ];
    }

    public function browsePage()
    {
        view()->addNamespace('marketBrowser', app_path('Modules/MarketBrowser/view'));
        return view('marketBrowser::layout');
    }

}