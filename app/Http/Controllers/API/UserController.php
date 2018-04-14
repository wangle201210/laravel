<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use App\Http\Transformer\UserTransformer;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * @apiVersion 0.0.1
     * @api {post} user/register 注册用户
     * @apiName user_register
     * @apiGroup User
     *
     * @apiParam {String} name 姓名.
     * @apiParam {String} email 邮箱.
     * @apiParam {String} password 密码.
     * @apiParam {String} comfirm 确定密码.
     *
     * @apiSuccess (201) {Object} data {"name":"wangle4","email":"285273596@qq.com","updated_at":"2018-04-11 15:24:06","created_at":"2018-04-11 15:24:06","id":9}.
     *
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
