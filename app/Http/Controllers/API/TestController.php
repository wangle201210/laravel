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
 * @Resource("AppController", uri="/api")
 */
class TestController extends Controller
{
    /**
     * 测试jwt
     *
     * @Get("/needAuth")
     */
    public function needAuth(Request $request)
    {
        // $user = auth()->user();
        $user = user('roles');
        return $this->response->array($user);
    }
    /**
     * 增加文章 Add Test
     * @GET("/tests")
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $per_page = getAnyData($request, 'per_page', 20);
        $test = Test::setFilterAndRelationsAndSort($request)->paginate($per_page);
        return $this->response->paginator($test, new TestTransformer);
    }

    /**
     * 增加文章 Add Test
     * @POST("/test")
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\TestRequest $request) {
        $test = new Test;
        $test['test'] = $request->test;
        $test->save();
        return $this->response->item($test, new TestTransformer)->setStatusCode(201);
    }

    /**
     * 显示特定的文章 Display the specified Test.
     * @GET("/test/{id}")
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $ids = getOneOrAll($id);
        if (is_array($ids)) {
            $test = Test::whereIn('id', $ids)->get();
            return $this->response->collection($test, new TestTransformer); //列表数据 状态码 200
        } else {
            $test = Test::find($ids);
            return $this->response->item($test, new TestTransformer); //列表数据 状态码 200
        }
    }

    /**
     * 更新文章 Update the specified Test
     * @PUT("/test/{id}")
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $test = Test::find($id);
        foreach ($test->getOriginal() as $key => $value) {
            $test->{$key} = jsonData($request, $key, $value);
        }
        // dd($test);
        $test->save();
        return $this->response->item($test, new TestTransformer)->setStatusCode(201);
    }

    /**
     * 删除文章 Remove the specified Test
     * @DELETE("/test")
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $ids = getOneOrAll($id, true);
        $isOk = Test::whereIn('id', $ids)->delete();
        if ($isOk) {
            return returnSuccess('success', 204);
        } else {
            return $this->response->error('未知错误！', 200);
        }
    }
}
