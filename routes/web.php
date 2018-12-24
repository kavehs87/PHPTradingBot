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

Route::get('/', 'HomeController@system');
Route::get('/signals', 'HomeController@signals')->name('signals');
Route::get('/system', 'HomeController@system')->name('system');
Route::get('/events', 'HomeController@events')->name('events');
Route::get('/history', 'HomeController@history')->name('history');
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
                $module = \App\Modules::factory($menu['module']);
                return view('modulePage', [
                    'module' => $menu['module'],
                    'output' => $module->{$menu['route'] . 'Page'}($request)
                ]);

            })->name($menu['route']);
        }
    }
} catch (\Exception $exception) {

}


Route::get('/debug', function () {
//    dd(\App\Modules::getModules());

//    $order = \App\Order::find(979);
//    $order->trailing = true;
//    $order->save();
//
//    dd($order->toArray());

    dd(\App\TradeHelper::recentlyTradedPairs(\Carbon\Carbon::now()->subDays(1)));

});
