<?php

use Illuminate\Support\Facades\Route;
use Carbon\Carbon;

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

Route::get('/', function () {
    $step = 100;
    $data = [];
    $day = 3 * 24 * 60 * 60;
    for($index = 0; $index < $step; $index++) {
        $date = Carbon::today();
        $date = $date->addSeconds($day * $index / $step)->toDateTimeString();
        $value = $index == 0 ? rand(0, 60) : $data[$index-1]->value + rand(-1, 1);
        $value = $value < 0 ? 0 : $value;
        $value = $value > 60 ? 60 : $value;
        array_push($data, (object) [
            'date' => $date,
            'value' => $value,
        ]);
    }

    $data = json_encode($data);

    return view('welcome', compact('data'));
});

Route::get('/gen-data', 'App\Http\Controllers\ReportController@genData');
Route::get('/export', 'App\Http\Controllers\ReportController@export');
