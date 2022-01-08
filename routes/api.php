<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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


Route::group(['middleware' => 'throttle'], function () {        
        
        //the login route can be access via {base_url}/api/login
        Route::post('login', [AuthController::class, 'login']);

        //the register route can be access via {base_url}/api/register
        Route::post('register', [AuthController::class, 'register']);
});
