<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Http\Transformer\TestTransformer;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
/**
 *
 * 请将header中的Authorization token设置正确
 *
 * @Versions({"v1"})
 * @Resource("AppController", uri="/api/app")
 */
class TestController extends Controller
{
    /**
     * 获取个人信息personal info 等同于sauth/user
     *
     * @Get("/test")
     */
    public function index(Request $request) {
        $per_page = getAnyData($request, 'per_page', 20);
        $test = Test::setFilterAndRelationsAndSort($request)->paginate($per_page);
        return $this->response->paginator($test, new TestTransformer);
    }
    public function needAuth(Request $request)
    {
        // $user = auth()->user();
        $user = user('roles');
        return $this->response->array($user);
    }
}
