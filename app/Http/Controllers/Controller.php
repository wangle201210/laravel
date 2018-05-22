<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Dingo\Api\Routing\Helpers;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, Helpers;
    /**
        @apiVersion 0.0.1
        @apiGroup all 通用(必读)
        @api {get} all 通用(必读)
        @apiName all 通用
        @apiSuccessExample {string} 传入参数案列
        {
		# ca从from到to并且id不在value里
	    	/message?filter={"created_at":{"from":"2016-02-20","to":"2016-02-24 23:59:59"}, "id":{"operation":"not in", "value":[2,3,4]}}
		# id从from到to
	 	/message?filter={"id":{"from":2,"to":5}}
	 	# id <= value
	 	/message?filter={"id":{"to":5}} or /message?filter={"id":{"operation":"<=","value":5}}
	 	# up 为空
	 	/message?filter={"updated_at":{"isNull":true}}
	 	# answer 和 value的关系(like,=,<....)
	 	/message?filter={"answer":{"operation":"like","value":"Partial search string"}}
	 	# answer 等于 ***
	 	/message?filter={"answer":"Full search string"}
	 	# 关联搜索
	 	/message?filter={"user.name":"asd"}
	 	# id= 1
	 	/message?filter={"id":1}

	 	# 暂时只支持单字段排序
	 	/message?sort=id
	 	/message?sort=-id
	 	/message?sort=user.name
		# 关联搜索
	 	/message?expand=user 
	 	response: { "id": 1, "message": "some message", "user_id": 1, ... "user": { "id": 1, "name": "Some username", ... } }
	 	# 获取多条(获取message里面id为1,2,3的3条)
	 	/message/1,2,3
	 	}
    */
}
