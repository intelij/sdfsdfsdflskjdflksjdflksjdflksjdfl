<?php

use Illuminate\Http\Request;
use \GuzzleHttp\Psr7\Response;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//
//Route::middleware('auth:api')->get('/payment', function (Request $request) {
//    return $request->user();
//});



//Route::prefix('v1')->group(function() {
    Route::prefix('payment')->group(function() {
        Route::post('/', 'PaymentController@index');
        Route::post('/auth-token', 'PaymentController@index');
        Route::post('/card-identifier', 'PaymentController@ci');



        Route::get('/', function () {
            dump('we hit this');
        });

    });
//});


Route::get('test', function () {

    $trans = new \Modules\Payment\Entities\Transaction();

    $trans->user_id = '04d7f995-ef33-4870-a214-4e21c51ff76e';
    $trans->transaction_id = 'TEST_API_' . time();
    $trans->response_message = randomArray();

    $trans->save();

    return response()->json(\Modules\Payment\Entities\Transaction::all());

});

Route::get('test-header', function (Request $request) {

    $response = new \GuzzleHttp\Psr7\Response(200, ['Authorization' => 'Basic SOME-SYPSPSER-LINGSKSJDKFJKSD-HGWKJEHKJS']);

    $header = $request->header('Authorization');

    dump($request, $response);

});

function randomArray() {

    $quote = array(
        "I wish I had",
        "Why Can't I have",
        "Can I have",
        "Did you have",
        "Will you get",
        "When will I get"
    );

    $items = array(
        "Money",
        "Time",
        "Sex",
        "Coffee",
        "A Better Job",
        "A Life",
        "Better Programming Skills",
        "Internet that was mine",
        "More Beer",
        "More Donuts",
        "Candy",
        "My Daughter",
        "Cable",
        "A Dining Room Table",
        "Better Couches",
        "A PS4",
        "A New Laptop",
        "A New Phone",
        "Water",
        "Rum",
        "Movies",
        "A Desktop Computer",
        "A Fish Tank",
        "My Socks",
        "My Jacket",
        "More Coffee",
        "More Koolaid",
        "More Power",
        "A Truck",
        "Toolbox",
        "More fish for Fish Tank",
        "A Screwdriver",
        "A Projector",
        "More Pants"
    );

    return $rand_keys = $quote[array_rand($quote,1)] . ' ' .  $rand_keys = $items[array_rand($items,1)] . ' ' .  $rand_keys = $items[array_rand($items,1)] . ' ' .  $rand_keys = $items[array_rand($items,1)];

}

//// App v1 API
//Route::group([
//    'middleware' => ['app', 'api.version:1'],
//    'namespace'  => 'App\Http\Controllers\App',
//    'prefix'     => 'api/v1',
//], function ($router) {
//    require base_path('routes/app_api.v1.php');
//});
//
//// App v2 API
//Route::group([
//    'middleware' => ['app', 'api.version:2'],
//    'namespace'  => 'App\Http\Controllers\App',
//    'prefix'     => 'api/v2',
//], function ($router) {
//    require base_path('routes/app_api.v2.php');
//});

