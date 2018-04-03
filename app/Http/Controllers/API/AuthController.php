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

/**
 * Class AuthController
 * @package App\Http\Controllers
 * @resource Auth
 *
 * 用户登录(APP不使用此接口 APP使用sauth)
 */
class AuthController extends Controller {
	protected $guard = 'api';
	/**
	 * 登录 Login（get token）
	 *
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
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
	 * 使得token失效Invalidate a token.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function deleteInvalidate() {
		$token = JWTAuth::parseToken();

		$token->invalidate();

		return new JsonResponse(['message' => 'token_invalidated']);
	}

	/**
	 * 刷新Token信息 Refresh token
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function patchRefresh() {
		try {
			$newToken = JWTAuth::parseToken()->refresh();
		} catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
			return new JsonResponse([
				'message' => 'The token has been blacklisted',
				'status_code' => 401,
			], 401);
		}
		return response([
			'message' => 'token_refreshed',
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
	 * 获取个人信息和用户组personal info
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getUser() {
		if (user()->roles()->get()->isEmpty()) {
			$role = '';
		} else {
			$role = user()->roles()->get()->pluck('name'); //->implode(',');
		}
		return new JsonResponse(['data' => [
			'me' => JWTAuth::parseToken()->authenticate(),
			'roles' => $role],
		]);
	}
	/**
	 * 重置密码Reset pwd
	 * @param  Request $request [password验证]
	 * @return [type]           [description]
	 */
	public function putResetpwd(Requests\ResetPwdRequest $request) {
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
