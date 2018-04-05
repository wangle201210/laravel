<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use App\Http\Transformer\UserTransformer;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
/**
 *
 *
 * @Versions({"v1"})
 * @Resource("UserController", uri="/api/user")
 */
class UserController extends Controller
{
    /**
     * 用户注册
     *
     * @Get("/register")
     */
    public function register(Requests\RegisterRequest $request)
    {
        if ($request['password'] !== $request['comfirm']) {
            return $this->response->error('两次输入密码不一致，请核实后再次尝试！');
        }
        $user = new User;
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = app('hash')->make($request['password']);
        $user->remember_token = str_random(10);
        $user->save();
        $roleId = jsonData($request, 'role_id');
        if (!$roleId) {
            $roleId = env('DEFROLE', 3);
            // 默认用户组 user
        }
        $user->roles()->sync($roleId);
        return $this->response->item($user, new UserTransformer)->setStatusCode(201);
    }
}
