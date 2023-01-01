<?php

use Faker\Provider\sk_SK\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TimeController;
use App\Http\Controllers\DayController;




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

Route::post('register',[CompanyController::class,'register']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('filter1',[CompanyController::class,'filterClient']);
Route::post('filter2',[CompanyController::class,'filterBooking']);
Route::post('filter3',[CompanyController::class,'filterEmployee']);
Route::get('getallservices',[ServiceController::class,'getAllServices']);






Route::post('search1',[CategoryController::class,'searchForCategory']);
Route::post('search2',[ServiceController::class,'searchForService']);

Route::post('search3',[CompanyController::class,'searchForCompany']);


Route::post('adduser',[UserController::class,'addUser']);

Route::post('editpassword/{id}',[UserController::class,'editPassword']);

Route::delete('delete/{id}',[UserController::class,'delete']);

Route::delete('deleteselected/{id}',[UserController::class,'deleteSelected']);

Route::get('getstarttime',[TimeController::class,'getStartTime']);

Route::get('getendtime',[TimeController::class,'getEndTime']);

Route::post('edittime/{source_id}',[TimeController::class,'editTime']);

Route::post('setstarttime/{source_id}',[TimeController::class,'setStartTime']);

Route::post('setendttime/{source_id}',[TimeController::class,'setEndtTime']);

Route::post('signUp',[CustomerController::class,'signUp']);
