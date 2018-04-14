<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

$api = app('Dingo\Api\Routing\Router');

$api->version('v1',[
		'middleware' => 'api.throttle',
		'limit' => 60,// 一个浏览器每分钟只能请求60次
		'expires' => 1,
	], function ($api) {
		// 供外部访问的路由
		$api->post('/auth/login', [
			'as' => 'api.auth.login',
			'uses' => 'App\Http\Controllers\API\AuthController@postLogin',
		]);
		$api->post('/user/register', [
			'as' => 'api.user.register',
			'uses' => 'App\Http\Controllers\API\UserController@register',
		]);
		// 登陆才能访问的路由
		$api->group([
			// 'middleware' => 'jwt.auth',
			'namespace' => 'App\Http\Controllers\API',
		], function ($api) {
			$api->get('/test/redis', [
				'uses' => 'TestController@redisTest',
				'as' => 'api.test.redisTest',
			]);
			$api->get('/test/needauth', [
				'uses' => 'TestController@needAuth',
				'as' => 'api.test.needauth',
			]);
			// 测试
			$api->post('/tests', [
				'uses' => 'TestController@store',
				'as' => 'api.test.store',
			]);
			$api->get('/tests', [
				'uses' => 'TestController@index',
				'as' => 'api.test.index',
			]);
			$api->get('/test/{id}', [
				'uses' => 'TestController@show',
				'as' => 'api.test.show',
			]);
			$api->put('/test/{id}', [
				'uses' => 'TestController@update',
				'as' => 'api.test.update',
			]);
			$api->delete('/test/{id}', [
				'uses' => 'TestController@destroy',
				'as' => 'api.test.destroy',
			]);
		});
});
