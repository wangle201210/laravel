<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Http\Transformer\ArticleTransformer;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// 不知道为什么用article会报错说找不到这个contrller,先用art来解决问题
class ArticleController extends Controller
{
    /**
        @apiVersion 0.0.1
        @apiHeaderExample {json} 头部列子:
        {
            "Content-Type": "application/json",
            "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
        }
        @apiGroup article
        @api {get} articles 获取文章
        @apiName get_articles
        @apiParam {Number} per_page=20 每页数量.
        @apiParam {Number} page 当前页.
        @apiSuccessExample {json} 成功返回:
        HTTP/1.1 200 OK
        {
          "data": [
            {
              "id": 1,
              "order": null,
              "user_id": null,
              "user_id_edited": null,
              "category_id": null,
              "title": "\u6587\u7ae0\u6807\u9898",
              "description": null,
              "content": "\u6587\u7ae0\u5185\u5bb9",
              "source_url": null,
              "source": null,
              "picture": null,
              "tops": 0,
              "is_comment": 1,
              "point": 0,
              "created_at": "2018-04-23 19:17:06",
              "updated_at": "2018-04-23 19:17:06",
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
        $article = Article::setFilterAndRelationsAndSort($request)->paginate($per_page);
        return $this->response->paginator($article, new ArticleTransformer);
    }

    /**
        @apiVersion 0.0.1
        @apiHeaderExample {json} 头部列子:
        {
            "Content-Type": "application/json",
            "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
        }
        @apiGroup article
        @api {post} articles 添加文章
        @apiName article_save
        @apiParam {String} title 标题
        @apiParam {String} content 内容
        @apiParam {Number} order=0 排序
        @apiParam {Number} parent_id=0 父文章id
        @apiParam {Number} display=1 是否展示
        @apiParam {String} [picture] 文章缩略图
        @apiParam {String} [description] 文章简介
        @apiSuccessExample {json} 成功返回:
        HTTP/1.1 201 OK
        {
          "data": {
            "title": "\u6587\u7ae0\u6807\u9898",
            "content": "\u6587\u7ae0\u5185\u5bb9",
            "updated_at": "2018-04-23 19:17:06",
            "created_at": "2018-04-23 19:17:06",
            "id": 1
          }
        }
    */
    public function store(Request $request) {
        $article = new Article;
        $article->title = $request['title'];
        $article->content = $request['content'];
        $article->order = $request['order'] ?? 0;
        $article->user_id = user('id');
        $article->user_id_edited = user('id');
        $article->category_id = $request['category_id'];
        $article->title = $request['title'];
        $article->description = $request['description'] ?? '';
        $article->content = $request['content'];
        $article->source_url = $request['source_url'] ?? '';
        $article->source = $request['source'] ?? '';
        $article->picture = $request['picture'] ?? '';
        $article->tops = $request['tops'] ?? 0;
        $article->is_comment = $request['is_comment'] ?? 1;
        $article->point = $request['point'] ?? 0;
        // $article->user_id = user('id');
        // $article->order = $request['order'] ?? 0;
        // $article->type = $request['type'] ?? 'article';
        // $article->parent_id = $request['parent_id'] ?? 0;
        // $article->display = $request['display'] ?? 1;
        // $article->picture = $request['picture'] ?? '';
        // $article->description = $request['description'] ?? '';
        $article->save();
        return $this->response->item($article, new ArticleTransformer)->setStatusCode(201);
    }
    /**
       @api {get} article/:id 获取单条文章信息
       @apiVersion 0.0.1
       @apiHeaderExample {json} 头部列子:
        {
            "Content-Type": "application/json",
            "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
        }
       @apiGroup article
       @apiName article_get
       @apiParam {Number} id 文章id
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
            $article = Article::whereIn('id', $ids)->get();
            return $this->response->collection($article, new ArticleTransformer); //列表数据 状态码 200
        } else {
            $article = Article::find($ids);
            $article->point += 1;
            $article->save();
            return $this->response->item($article, new ArticleTransformer); //列表数据 状态码 200
        }
    }

    /**
        @apiVersion 0.0.1
        @apiHeaderExample {json} 头部列子:
        {
            "Content-Type": "application/json",
            "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
        }
        @apiGroup article
        @api {put} article 修改文章
        @apiName article_update
        @apiParam {String} [title] 标题
        @apiParam {String} [type] 类型
        @apiParam {Number} [order] 排序
        @apiParam {Number} [parent_id] 父文章id
        @apiParam {Number} [display] 是否展示
        @apiParam {String} [picture] 文章缩略图
        @apiParam {String} [description] 文章简介
        @apiSuccessExample {json} 成功返回:
        HTTP/1.1 201 OK
        {
          "data": {
            "title": "文章1",
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
        $article = Article::find($id);
        foreach ($article->getOriginal() as $key => $value) {
            $article->{$key} = jsonData($request, $key, $value);
        }
        $article->user_id_edited = user('id');
        $article->save();
        return $this->response->item($article, new ArticleTransformer)->setStatusCode(201);
    }

    /**
        @apiVersion 0.0.1
        @apiHeaderExample {json} 头部列子:
        {
            "Content-Type": "application/json",
            "Authorization": "Bearer eyJ0eXuHZO9ShwFEGVKskg"
        }
        @apiGroup article
        @api {delete} article/:id 删除文章
        @apiName article_delete
        @apiParam {Number} id 文章id
        @apiSuccessExample {null} 成功返回:
        HTTP/1.1 204 OK
    */
    public function destroy($id) {
        $ids = getOneOrAll($id, true);
        $isOk = Article::whereIn('id', $ids)->delete();
        if ($isOk) {
            return returnSuccess('success', 204);
        } else {
            return $this->response->error('未知错误！', 200);
        }
    }
}
