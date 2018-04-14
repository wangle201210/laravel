<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller {
	protected $guard = 'api';
	/**
     * @apiVersion 0.0.1
     * @api {post} auth/login 用户登陆
     * @apiName auth_login
     * @apiGroup User
     *
     * @apiParam {String} name 姓名.
     * @apiParam {String} password 密码.
     *
     * @apiSuccessExample {json} 成功返回:
     HTTP/1.1 201 OK
     {
		  "result": {
		    "token": "eyJ0eXAiOihKRRnreQ-Zw4",
		    "user": {
		      "id": 7,
		      "name": "wangle2",
		      "email": "285273594@qq.com",
		      "created_at": "2018-04-04 02:52:00",
		      "updated_at": "2018-04-04 02:52:00"
		    }
		  }
		}
     *
     */
	public function postLogin(Requests\AuthRequest $request) {
		// grab credentials from the request
        $credentials = $request->only('name','password');
		try {
			$this->validatePostLoginRequest($request);
		} catch (HttpResponseException $e) {
			return $this->onBadRequest();
		}
        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
		$user = user();
		$headers = ['Authorization' => $token, 'Access-Control-Expose-Headers' => 'Authorization'];
		$response = ['token' => $token, 'user' => $user];
        // all good so return the token
        // return response()->json(compact('token'));
		// return $this->onAuthorized($token);
		return response()->json(['result' => $response], 200, $headers);
	}

	/**
	 * Validate authentication request.
	 *
	 * @param  Request $request
	 * @return void
	 * @throws HttpResponseException
	 */
	protected function validatePostLoginRequest(Request $request) {
		$this->validate($request, [
			'password' => 'required',
			'name' => 'required',
		]);
	}

	/**
	 * What response should be returned on bad request.
	 *
	 * @return JsonResponse
	 */
	protected function onBadRequest() {
		return new JsonResponse([
			'message' => 'invalid_credentials',
			'status_code' => 401,
		], 401);
		// ], Response::HTTP_BAD_REQUEST);
	}

	/**
	 * What response should be returned on invalid credentials.
	 *
	 * @return JsonResponse
	 */
	protected function onUnauthorized() {
		return new JsonResponse([
			'message' => '用户名或密码错误,或用户被禁止登录!',
			'status_code' => 401,
		], 401);
		// ], Response::HTTP_BAD_REQUEST);
	}

	/**
	 * What response should be returned on error while generate JWT.
	 *
	 * @return JsonResponse
	 */
	protected function onJwtGenerationError() {
		return new JsonResponse([
			'message' => 'could_not_create_token',
			'status_code' => 401,
		], 401);
		// ], Response::HTTP_INTERNAL_SERVER_ERROR);
	}

	/**
	 * What response should be returned on authorized.
	 *
	 * @return JsonResponse
	 */
	protected function onAuthorized($token) {
		$user = Auth::guard('users')->user();
		return response([
			'message' => 'success',
			'data' => [
				'info' => $user,
				'token' => $token,
			],
		])
			->header('Authorization', $token)
			->header('Access-Control-Expose-Headers', 'Authorization');

		// return new JsonResponse([
		//     'message' => 'token_generated',
		//     'data' => [
		//         'token' => $token,
		//     ]
		// ]);
	}

	/**
	 * Get the needed authorization credentials from the request.
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return array
	 */
	protected function getCredentials(Request $request) {
		return $request->only('phone', 'password', 'is_lock');
	}
	/**
     * @apiVersion 0.0.1
     * @api {post} auth/login 用户退出
     * @apiName auth_login_out
     * @apiGroup User
     *
     @apiHeader {String} Authorization Bearer + token
     @apiHeaderExample {json} 头部列子:
     {
    	"Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
     }
     @apiSuccessExample {json} 成功返回:
     HTTP/1.1 201 OK
     {
		"message": "退出登陆成功!"
	 }
     *
     */
	public function deleteInvalidate() {
		$token = JWTAuth::parseToken();

		$token->invalidate();

		return new JsonResponse(['message' => '退出登陆成功!']);
	}
	/**
     * @apiVersion 0.0.1
     * @api {get} auth/refresh 刷新登陆信息
     * @apiName auth_refresh
     * @apiGroup User
     *
     @apiHeader {String} Authorization Bearer + token
     @apiHeaderExample {json} 头部列子:
     {
      "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
     }
     @apiSuccessExample {json} 成功返回:
     HTTP/1.1 201 OK
     {
	  "message": "登陆信息刷新成功!",
	  "data": "eyJ0eXAiOiJKvr-s"
	 }
     *
     */
	public function patchRefresh() {
		try {
			$newToken = JWTAuth::parseToken()->refresh();
		} catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
			return new JsonResponse([
				'message' => '登陆信息已过期,请重新登录!',
				'status_code' => 401,
			], 401);
		}
		return response([
			'message' => '登陆信息刷新成功!',
			'data' => $newToken,
		])
			->header('Authorization', $newToken)
			->header('Access-Control-Expose-Headers', 'Authorization');
		// return new JsonResponse([
		//     'message' => 'token_refreshed',
		//     'data' => [
		//         'token' => $newToken
		//     ]
		// ]);
	}
	/**
     * @apiVersion 0.0.1
     * @api {get} auth/user 获取个人信息和用户组
     * @apiName auth_user
     * @apiGroup User
     *
     @apiHeader {String} Authorization Bearer + token
     @apiHeaderExample {json} 头部列子:
     {
      "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
     }
     @apiSuccessExample {json} 成功返回:
     HTTP/1.1 201 OK
     {
	  "data": {
	    "me": {
	      "id": 7,
	      "name": "wangle2",
	      "email": "285273594@qq.com",
	      "created_at": "2018-04-04 02:52:00",
	      "updated_at": "2018-04-14 18:33:01"
	    },
	    "roles": [
	      "user"
	    ]
	  }
	 }
     *
     */
	public function getUser() {
		if (user()->roles()->get()->isEmpty()) {
			$role = '';
		} else {
			$role = user()->roles()->get()->pluck('name'); //->implode(',');
		}
		return new JsonResponse(['data' => 
			[
				'me' => JWTAuth::parseToken()->authenticate(),
				'roles' => $role
			],
		]);
	}
	/**
     * @apiVersion 0.0.1
     * @api {get} auth/resetpwd 重置密码
     * @apiName auth_resetpwd
     * @apiGroup User
     *
     @apiHeader {String} Authorization Bearer + token
     @apiHeaderExample {json} 头部列子:
     {
      "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
     }
     @apiSuccessExample {json} 成功返回:
     HTTP/1.1 201 OK
     {"id":7,"name":"wangle2","email":"285273594@qq.com","created_at":"2018-04-04 02:52:00","updated_at":"2018-04-14 18:50:48"}
     *
     */
	public function putResetpwd(Request $request) {
		// dd($request->only('phone'),$request->json(),jsonData($request,'phone'));
		$pwd = jsonData($request, 'password');
		$user = JWTAuth::parseToken()->authenticate();

		$npwd = jsonData($request, 'npassword');
		$uxpassword = Hash::make($npwd);
		// dd($user->password);
		// dd($uxpassword,Hash::check($pwd,'$2y$10$Qbl5SpOt0rUvRfJBI/5L3OolwV7rmYp7cvnNnDzhXeEGeIY87LUte'),Hash::check($pwd,$uxpassword),Hash::check($pwd,$uxpassword));
		// dd($pwd,$user->password,$npwd,$uxpassword,$user->password,Hash::check($pwd,$user->password));
		if (Hash::check($pwd, $user->password)) {
			if ($npwd) {
				$user->password = Hash::make($npwd);
				// dd($user->password);
				$user->save();
				return response()->json(['data' => $user->toJson()], 201); //列表数据 状态码 200
			} else {
				return $this->response->error('请设置新密码！', 412);
			}
		} else {
			return $this->response->error('原密码错误！', 202);
		}
	}
}
