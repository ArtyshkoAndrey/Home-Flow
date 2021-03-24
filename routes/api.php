<?php

use App\Http\Controllers\Api\GoogleController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\UserController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\ModuleController;
use Illuminate\Support\Facades\Route;
use PhpMqtt\Client\Facades\MQTT;
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

Route::group(['middleware' => 'auth:api'], function () {
  Route::post('logout', [LoginController::class, 'logout']);

  Route::get('user', [UserController::class, 'current']);
  Route::apiResource('room', RoomController::class);
  Route::apiResource('module', ModuleController::class);

  Route::patch('settings/profile', [ProfileController::class, 'update']);
  Route::patch('settings/password', [PasswordController::class, 'update']);

  Route::get('google/types', [GoogleController::class, 'types']);
  Route::get('google/traits', [GoogleController::class, 'traits']);
});

Route::group(['middleware' => 'guest:api'], function () {
  Route::post('login', [LoginController::class, 'login']);
  Route::post('register', [RegisterController::class, 'register']);

  Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
  Route::post('password/reset', [ResetPasswordController::class, 'reset']);

  Route::post('email/verify/{user}', [VerificationController::class, 'verify'])->name('verification.verify');
  Route::post('email/resend', [VerificationController::class, 'resend']);

  Route::post('oauth/{driver}', [OAuthController::class, 'redirect']);
  Route::get('oauth/{driver}/callback', [OAuthController::class, 'handleCallback'])->name('oauth.callback');
});

Route::get('test/', function () {
  MQTT::publish('test/', 'Hello World!');
});
