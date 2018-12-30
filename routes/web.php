<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::group(['middleware' => 'auth'], function () {
    /*
     * Ajax/Post Routes
     */
    Route::get('/loadFavorites', 'HomeController@favorites');
    Route::get('/toggleFavorite/{symbol}', 'HomeController@toggleFavorite');
    Route::get('/loadRecentOrders', 'HomeController@recentOrders');



    Route::get('/', 'HomeController@positions');
    Route::get('/signals', 'HomeController@signals')->name('signals');
    Route::get('/system', 'HomeController@system')->name('system');
    Route::get('/system/ctl/{command}/{service}', 'HomeController@systemCtl')->name('systemCtl');
    Route::get('/events', 'HomeController@events')->name('events');
    Route::get('/history/', 'HomeController@history')->name('history');
    Route::get('/history/{column?}/{sort?}', 'HomeController@history')->name('sortHistory');
    Route::get('/positions/{id?}', 'HomeController@positions')->name('positions');
    Route::get('/positions/{id?}/show', 'HomeController@positions')->name('showSymbol');
    Route::get('/positions/toggleTrailing/{id}', 'HomeController@toggleTrailing')->name('toggleTrailing');
    Route::get('/positions/close/{id}', 'HomeController@closePosition')->name('closePosition');
    Route::get('/positions/new/{market}/{quantity}/{tp?}/{sl?}/{ttp?}/{tsl?}', 'HomeController@newPosition')->name('newPosition');
    Route::get('/positions/table/open', 'HomeController@openTable')->name('openTable');
    Route::post('/positions/edit/{id}', 'HomeController@editPosition')->name('editPosition');
    Route::post('/positions/save', 'HomeController@savePosition')->name('savePosition');
    Route::get('/modules', 'HomeController@modules')->name('modules');
    Route::get('/modules/enable/{id}', 'HomeController@enableModule')->name('enableModule');
    Route::get('/modules/disable/{id}', 'HomeController@disableModule')->name('disableModule');
    Route::get('/modules/install/{name}', 'HomeController@installModule')->name('installModule');
    Route::get('/modules/uninstall/{id}', 'HomeController@uninstallModule')->name('uninstallModule');
    Route::post('/saveSettings', 'HomeController@saveSettings')->name('saveSettings');
    Route::post('/saveOrderDefaults', 'HomeController@saveOrderDefaults')->name('saveOrderDefaults');
    try {
        if (!empty(\App\Modules::getMenus())) {
            foreach (\App\Modules::getMenus() as $menu) {
                Route::match(array('GET', 'POST'), '/modules/page/' . $menu['route'], function (\Illuminate\Http\Request $request) use ($menu) {
                    $module = \App\Modules::init($menu['module']);
                    return view('modulePage', [
                        'module' => $menu['module'],
                        'output' => $module->{$menu['route'] . 'Page'}($request)
                    ]);

                })->name($menu['route']);
            }
        }
    } catch (\Exception $exception) {

    }
});

Route::get('/createAdmin', function () {
    dd('security enabled :D');
    $u = new \App\User();
    $u->name = 'Kaveh Sarkhanlou';
    $u->email = 'kaveh.s@live.com';
    $u->password = \Illuminate\Support\Facades\Hash::make('123456');
    try {
        $u->save();
    }
    catch (\Exception $e){
        dd($e->getMessage());
    }
});

Route::get('/debug', function () {
    dd(\App\TradeHelper::getPrice('CMTETH'));
    dd(\App\TradeHelper::getTick('CMTETH'));
//dd(\Illuminate\Support\Facades\Cache::get('CMTETH'));

//    print_r(\App\Setting::all()->toArray());
//    \App\Setting::query()->truncate();

//    $amount = 11; // usdt
//    $symbol = 'BTCUSDT';
//    $notions = \App\TradeHelper::getNotions($symbol);
//    $stepSize = \App\TradeHelper::getStepSize($symbol);
//    $baseAsset = $notions['baseAsset'];
//    $binance = \App\TradeHelper::getBinance();
//    $quantity = \App\TradeHelper::calcUSDT($amount, $baseAsset);
//    $quantity = $binance->roundStep($quantity,$stepSize);
//    $res = $binance->marketBuyTest($symbol, $quantity);
//    dd($res);


//    $amount = 11; // usdt
//    $symbol = 'BTCUSDT';
//    $notions = \App\TradeHelper::getNotions($symbol);
//    $stepSize = \App\TradeHelper::getStepSize($symbol);
//    $baseAsset = $notions['baseAsset'];
//    $binance = \App\TradeHelper::getBinance();
//    $quantity = \App\TradeHelper::calcUSDT($amount, $baseAsset);
//    $quantity = $binance->roundStep($quantity, $stepSize);
//    $res = $binance->marketSellTest($symbol, $quantity);
//    dd($res);
});


Route::get('/home', 'HomeController@index')->name('home');
