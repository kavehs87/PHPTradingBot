<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/24/18
 * Time: 17:42
 */

namespace App\Http\Controllers;


use App\Order;

class ApiController extends Controller
{
    public function positions()
    {
        $openPositions = Order::getOpenPositions(true);
        $positions = [];
        foreach ($openPositions as $open) {
            $positions[] = [
                'id' => $open->id,
                'symbol' => $open->symbol,
                'pl' => round($open->getPL(),2),
            ];
        }
        return $positions;
    }
}