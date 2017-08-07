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

Route::get('/', function () {
    return view('welcome');
});
// V3s3 add
Route::put('/v3s3/{object}', 	'\App\Modules\V3s3\Controllers\V3s3Controller@put');
Route::get('/v3s3/{object}', 	'\App\Modules\V3s3\Controllers\V3s3Controller@get');
Route::delete('/v3s3/{object}',	'\App\Modules\V3s3\Controllers\V3s3Controller@delete');
Route::post('/v3s3/{object}',	'\App\Modules\V3s3\Controllers\V3s3Controller@post');
Route::post('/v3s3',			'\App\Modules\V3s3\Controllers\V3s3Controller@post');
// V3s3 end
