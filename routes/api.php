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
			'middleware' => 'jwt.auth',
			'namespace' => 'App\Http\Controllers\API',
		], function ($api) {
			// 七牛云上传
			$api->post('/qiniu', [
				'uses' => 'HelperController@qiniu',
				'as' => 'api.test.qiniuTest',
			]);
			// ping++
			$api->get('/ping', [
				'uses' => 'HelperController@ping',
				'as' => 'api.test.ping',
			]);
			// redis缓存
			$api->get('/test/redis', [
				'uses' => 'TestController@redisTest',
				'as' => 'api.test.redisTest',
			]);
			// 角色
			$api->post('/role/attachpermission/{id}', [
				'uses' => 'RoleController@attachPermission',
				'as' => 'api.role.attachpermission',
			]);
			$api->resource('role', 'RoleController');
			// 权限
			$api->resource('permission', 'PermissionController');
			// 退出登陆
			$api->delete('/auth/invalidate', [
				'uses' => 'AuthController@deleteInvalidate',
				'as' => 'api.auth.invalidate',
			]);
			// 刷新登陆信息
			$api->get('/auth/refresh', [
				'uses' => 'AuthController@patchRefresh',
				'as' => 'api.auth.refresh',
			]);
			// 获取个人信息和用户组
			$api->match(['get', 'post'], '/auth/user', [
				'uses' => 'AuthController@getUser',
				'as' => 'api.auth.user',
			]);
			// 重置密码
			$api->put('/auth/resetpwd', [
				'uses' => 'AuthController@putResetpwd',
				'as' => 'api.auth.resetpwd',
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
			// 分类
			$api->post('/categories', [
				'uses' => 'CategoryController@store',
				'as' => 'api.category.store',
			]);
			$api->get('/categories', [
				'uses' => 'CategoryController@index',
				'as' => 'api.category.index',
			]);
			$api->get('/category/{id}', [
				'uses' => 'CategoryController@show',
				'as' => 'api.category.show',
			]);
			$api->put('/category/{id}', [
				'uses' => 'CategoryController@update',
				'as' => 'api.category.update',
			]);
			$api->delete('/category/{id}', [
				'uses' => 'CategoryController@destroy',
				'as' => 'api.category.destroy',
			]);
			// 文章
			$dir = base_path('routes/api/article.php');
			require_once ($dir);
		});
});
