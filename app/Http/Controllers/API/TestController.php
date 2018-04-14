<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Http\Transformer\TestTransformer;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;
use zgldh\QiniuStorage\QiniuStorage;

class TestController extends Controller
{
    /**
        @apiVersion 0.0.1
        @apiHeader {String} Authorization Bearer + token
        @apiHeaderExample {json} 头部列子:
        {
          "Authorization": "Bearer eyJ0eXAiOCI6NZO9ShwFEGVKskg"
        }

        @apiGroup test
        @api {post} test/qiniu 测试qiniu云
        @apiName test_qiniu
        @apiParam {File} file 需要缓存的内容.
        @apiSuccessExample {string} 成功返回:
        HTTP/1.1 201 OK
        pic/oNWGBOzQihjUZBzltOrrKWLS87s4gghPsM52gnpq.jpeg
    */
    public function qiniuTest(Request $request)
    {
        $disk = QiniuStorage::disk('qiniu');
        $qnpath = '/pic';// 七牛云目录
        $path = $disk->put($qnpath,$request->file('file'));
        return $path; //最终储存地址
    }
    /**
        @apiVersion 0.0.1
        @apiHeader {String} Authorization Bearer + token
        @apiHeaderExample {json} 头部列子:
        {
          "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
        }

        @apiGroup test
        @api {get} test/redis 测试redis
        @apiName test_redis
        @apiParam {String} string 需要缓存的内容.
        @apiSuccessExample {string} 成功返回:
        HTTP/1.1 201 OK
        string
    */

    public function redisTest(Request $request)
    {
        Redis::set('name', $request->string);
        $res = Redis::get('name');
        return $res;
    }
    /**
        @apiVersion 0.0.1
        @apiHeader {String} Authorization Bearer + token
        @apiHeaderExample {json} 头部列子:
        {
            "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
        }

        @apiGroup test
        @api {get} test/needauth 测试needauth
        @apiName test_needauth
        @apiSuccessExample {json} 成功返回:
        HTTP/1.1 201 OK
        "user": {
            "id": 7,
            "name": "wangle2",
            "email": "285273594@qq.com",
            "created_at": "2018-04-04 02:52:00",
            "updated_at": "2018-04-04 02:52:00"
        }
    */
    public function needAuth(Request $request)
    {
        // $user = auth()->user();
        $user = user();
        return $this->response->array($user);
    }
    /**
        @apiVersion 0.0.1
        @apiHeader {String} Authorization Bearer + token
        @apiHeaderExample {json} 头部列子:
        {
          "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
        }

        @apiGroup test
        @api {get} tests 测试获取article
        @apiName test_article
        @apiParam {Number} per_page=20 每页数量.
        @apiParam {Number} page 当前页.
        @apiSuccessExample {json} 成功返回:
        HTTP/1.1 201 OK
        {
            "data": [
                {
                    "id": 1,
                    "created_at": "2018-04-03 18:05:41",
                    "updated_at": "2018-04-03 18:05:35",
                    "test": "test",
                    "title": "test1"
                },
                {
                    "id": 2,
                    "created_at": "2018-04-03 18:05:41",
                    "updated_at": "2018-04-03 18:05:35",
                    "test": "test",
                    "title": "test2"
                }
            ],
            "meta": {
                "pagination": {
                    "total": 19,
                    "count": 2,
                    "per_page": 2,
                    "current_page": 1,
                    "total_pages": 10,
                    "links": {
                    "next": "http:\/\/l.t\/api\/tests?page=2"
                    }
                }
            }
        }
    */
    public function index(Request $request) {
        $per_page = getAnyData($request, 'per_page', 20);
        $test = Test::setFilterAndRelationsAndSort($request)->paginate($per_page);
        return $this->response->paginator($test, new TestTransformer);
    }

    /**
        @apiVersion 0.0.1
        @apiHeader {String} Authorization Bearer + token
        @apiHeaderExample {json} 头部列子:
        {
          "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
        }
        @apiGroup test
        @api {post} tests 测试上传article
        @apiName test_article_save
        @apiParam {String} test test.
        @apiSuccessExample {json} 成功返回:
        HTTP/1.1 201 OK
        {
            "data": {
                "test": "ssss",
                "updated_at": "2018-04-11 21:55:25",
                "created_at": "2018-04-11 21:55:25",
                "id": 24,
                "title": "ssss24"
            }
        }
    */
    public function store(Request $request) {
        $test = new Test;
        $test['test'] = $request->test;
        $test->save();
        return $this->response->item($test, new TestTransformer)->setStatusCode(201);
    }
    /**
     *  @api {get} test/:id getArticle
     *  @apiVersion 0.0.1
     *  @apiGroup test
     *  @apiName test_article_get
     *  @apiParam {Number} id Users unique ID.
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
        @apiVersion 0.0.1
        @apiHeader {String} Authorization Bearer + token
        @apiHeaderExample {json} 头部列子:
        {
          "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
        }
        @apiGroup test
        @api {put} tests 测试修改article
        @apiName test_article_update
        @apiParam {String} [test] test.
        @apiSuccessExample {json} 成功返回:
        HTTP/1.1 201 OK
        {
            "data": {
                "test": "ssss",
                "updated_at": "2018-04-11 21:55:25",
                "created_at": "2018-04-11 21:55:25",
                "id": 24,
                "title": "ssss24"
            }
        }
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
