<?php

use Illuminate\Support\Facades\Route;
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


Auth::routes();

#权限验证

//邮箱验证界面
Route::get('/email/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
Route::post('/email/verification-notification', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');


Route::get('/', 'TopicsController@index')->name('root');
Route::get('/permission-denied', 'PagesController@permissionDenied')->name('permission-denied');

Route::resource('/categories', 'CategoriesController', ['only' => ['show']]);
Route::resource('/users', 'UsersController', ['only' => ['show', 'update', 'edit']]);
Route::post('/upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');
Route::resource('/topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::get('/topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');

Route::resource('/replies', 'RepliesController', ['only' => [ 'store', 'update', 'edit', 'destroy']]);
Route::resource('/notifications', 'NotificationsController', ['only' => ['index']]);
