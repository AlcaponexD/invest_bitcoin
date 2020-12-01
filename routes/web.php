<?php
use Illuminate\Support\Facades\Route;
/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

Route::group(['prefix' => 'v1'], function (){
    Route::post('users', 'UserController@store');
    Route::post('login','Auth\AuthController@login');
    Route::post('deposit','WalletController@deposit');
    Route::get('balance','WalletController@balance');
    Route::get('current','CoinController@current');
});