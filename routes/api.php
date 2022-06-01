<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

#Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
#    return $request->user();
#});

Route::prefix('v1')->middleware('change-locale')->name('api.v1.')->group(function() {

    Route::middleware('throttle:' . config('api.rate_limits.sign'))->group(function () {
        #短信验证码
        Route::post('verificationCodes', 'VerificationCodesController@store')->name('verificationCodes.store');
        #用户注册
        Route::post('users', 'UsersController@store')->name('users.store');
        #微信授权登录 social_type 这个参数现在只能暂时是 social_type
        Route::post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->where('social_type', 'wechat')
            ->name('socials.authorizations.store');
        #账号密码登录
        Route::post('authorizations', 'AuthorizationsController@store')->name('authorizations.store');
        #刷新token
        Route::put('authorizations/current', 'AuthorizationsController@update')->name('authorizations.update');
        #删除token
        Route::delete('authorizations/current', 'AuthorizationsController@destroy')->name('authorizations.destroy');


    });



    Route::middleware('throttle:' . config('api.rate_limits.access'))->group(function () {
        #通过手机号获取图形验证码
        Route::post('captchas', 'CaptchasController@store')->name('captchas.store');
        #某个用户的详情
        Route::get('users/{user}', 'UsersController@show')->name('users.show');
        #获取这个用户的文章列表
        Route::get('users/{user}/topics', 'TopicsController@userIndex')->name('users.topics.index');
        #展示用户评论
        Route::get('users/{user}/replies', 'RepliesController@userIndex')->name('users.replies.index');
        #获取分类
        Route::get('categories', 'CategoriesController@index')->name('categories.index');
        #展示文章
        Route::resource('topics', 'TopicsController')->only(['index', 'show']);
        #展示文章评论
        Route::get('topics/{topic}/replies', 'RepliesController@index')->name('topics.replies.index');
        #资源推荐接口
        Route::get('links', 'LinksController@index')->name('links.index');
        #活跃用户
        Route::get('actived/users', 'UsersController@activedIndex')->name('actived.users.index');

        #登录后可以访问的接口
        Route::middleware('auth:sanctum')->group(function() {
            #当前登录用户信息
            Route::get('user', 'UsersController@me')->name('user.show');
            #编辑用户信息
            Route::patch('user/{user}', 'UsersController@update')->name('user.update');
            #图片上传
            Route::post('images', 'ImagesController@store')->name('images.store');
            #发布删除文章
            Route::resource('topics', 'TopicsController')->only(['store', 'update', 'destroy']);
            #发布回复
            Route::post('topics/{topic}/replies', 'RepliesController@store')->name('topics.replies.store');
            #删除回复
            Route::delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy')->name('topics.replies.destroy');
            #通知列表
            Route::get('notifications', 'NotificationsController@index')->name('notifications.index');
            #通知状态
            Route::get('notifications/stats', 'NotificationsController@stats')->name('notifications.stats');
            #修改通知为已读状态
            Route::patch('user/read/notifications', 'NotificationsController@read')->name('user.notifications.read');
            #展示当前用户登录的权限
            Route::get('user/permissions', 'PermissionsController@index')->name('user.permissions.index');
        });


    });
});

