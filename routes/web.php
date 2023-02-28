<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ApiUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
  return view('welcome');
});



// Route::group([
//   'middleware' => 'api',
//   'prefix' => 'api'
// ], function ($router) {
//   Route::any('/test', [ApiUserController::class, 'index'])->name('api_test');
// });