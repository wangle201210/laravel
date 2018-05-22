<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Transformer\CategoryTransformer;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
        @apiVersion 0.0.1
        @apiHeaderExample {json} 头部列子:
        {
            "Content-Type": "application/json",
            "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
        }
        @apiGroup category
        @api {get} categories 获取分类
        @apiName get_categories
        @apiParam {Number} per_page=20 每页数量.
        @apiParam {Number} page 当前页.
        @apiSuccessExample {json} 成功返回:
        HTTP/1.1 200 OK
        {
          "data": [
            {
              "id": 1,
              "order": null,
              "user_id": 1,
              "title": "ssss",
              "type": "type",
              "depth": null,
              "parent_id": 0,
              "display": 1,
              "picture": null,
              "description": null,
              "point": 0,
              "created_at": "2018-04-20 21:45:28",
              "updated_at": "2018-04-20 21:45:28",
              "deleted_at": null
            }
          ],
          "meta": {
            "pagination": {
              "total": 1,
              "count": 1,
              "per_page": 20,
              "current_page": 1,
              "total_pages": 1,
              "links": []
            }
          }
        }
    */
    public function index(Request $request) {
        $per_page = getAnyData($request, 'per_page', 20);
        $cate = Category::setFilterAndRelationsAndSort($request)->paginate($per_page);
        return $this->response->paginator($cate, new CategoryTransformer);
    }

    /**
        @apiVersion 0.0.1
        @apiHeaderExample {json} 头部列子:
        {
            "Content-Type": "application/json",
            "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
        }
        @apiGroup category
        @api {post} categories 添加分类
        @apiName category_save
        @apiParam {String} title 标题
        @apiParam {String} type=article 类型
        @apiParam {Number} order=0 排序
        @apiParam {Number} parent_id=0 父分类id
        @apiParam {Number} display=1 是否展示
        @apiParam {String} [picture] 分类缩略图
        @apiParam {String} [description] 分类简介
        @apiSuccessExample {json} 成功返回:
        HTTP/1.1 201 OK
        {
          "data": {
            "title": "分类1",
            "type": "article",
            "user_id": 1,
            "order": 0,
            "parent_id": 0,
            "display": "1",
            "picture": "",
            "description": "",
            "updated_at": "2018-04-21 16:00:53",
            "created_at": "2018-04-21 16:00:53",
            "id": 2
          }
        }
    */
    public function store(Request $request) {
        $cate = new Category;
        $cate->title = $request['title'];
        $cate->user_id = user('id');
        $cate->order = $request['order'] ?? 0;
        $cate->type = $request['type'] ?? 'article';
        $cate->parent_id = $request['parent_id'] ?? 0;
        $cate->display = $request['display'] ?? 1;
        $cate->picture = $request['picture'] ?? '';
        $cate->description = $request['description'] ?? '';
        $cate->save();
        return $this->response->item($cate, new CategoryTransformer)->setStatusCode(201);
    }
    /**
       @api {get} category/:id 获取单条分类信息
       @apiVersion 0.0.1
       @apiHeaderExample {json} 头部列子:
        {
            "Content-Type": "application/json",
            "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
        }
       @apiGroup category
       @apiName category_get
       @apiParam {Number} id 分类id
        @apiSuccessExample {json} 成功返回:
        HTTP/1.1 200 OK
        {
          "data": {
            "id": 2,
            "order": 0,
            "user_id": 1,
            "title": "\u5206\u7c7b1",
            "type": "article",
            "depth": null,
            "parent_id": 0,
            "display": 1,
            "picture": "",
            "description": "",
            "point": 0,
            "created_at": "2018-04-21 16:00:53",
            "updated_at": "2018-04-21 16:00:53",
            "deleted_at": null
          }
        }
    */        
    public function show($id) {
        $ids = getOneOrAll($id);
        if (is_array($ids)) {
            $cate = Category::whereIn('id', $ids)->get();
            return $this->response->collection($cate, new CategoryTransformer); //列表数据 状态码 200
        } else {
            $cate = Category::find($ids);
            return $this->response->item($cate, new CategoryTransformer); //列表数据 状态码 200
        }
    }

    /**
        @apiVersion 0.0.1
        @apiHeaderExample {json} 头部列子:
        {
            "Content-Type": "application/json",
            "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
        }
        @apiGroup category
        @api {put} category 修改分类
        @apiName category_update
        @apiParam {String} [title] 标题
        @apiParam {String} [type] 类型
        @apiParam {Number} [order] 排序
        @apiParam {Number} [parent_id] 父分类id
        @apiParam {Number} [display] 是否展示
        @apiParam {String} [picture] 分类缩略图
        @apiParam {String} [description] 分类简介
        @apiSuccessExample {json} 成功返回:
        HTTP/1.1 201 OK
        {
          "data": {
            "title": "分类1",
            "type": "article",
            "user_id": 1,
            "order": 0,
            "parent_id": 0,
            "display": "1",
            "picture": "",
            "description": "",
            "updated_at": "2018-04-21 16:00:53",
            "created_at": "2018-04-21 16:00:53",
            "id": 2
          }
        }
    */
    public function update(Request $request, $id) {
        $cate = Category::find($id);
        foreach ($cate->getOriginal() as $key => $value) {
            $cate->{$key} = jsonData($request, $key, $value);
        }
        // dd($cate);
        $cate->save();
        return $this->response->item($cate, new CategoryTransformer)->setStatusCode(201);
    }

    /**
        @apiVersion 0.0.1
        @apiHeaderExample {json} 头部列子:
        {
            "Content-Type": "application/json",
            "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
        }
        @apiGroup category
        @api {delete} category/:id 删除分类
        @apiName category_delete
        @apiParam {Number} id 分类id
        @apiSuccessExample {null} 成功返回:
        HTTP/1.1 204 OK
    */
    public function destroy($id) {
        $ids = getOneOrAll($id, true);
        $isOk = Category::whereIn('id', $ids)->delete();
        if ($isOk) {
            return returnSuccess('success', 204);
        } else {
            return $this->response->error('未知错误！', 200);
        }
    }
}
