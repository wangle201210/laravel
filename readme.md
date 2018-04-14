### laravelInit 只是为了方便以后使用才建立的项目

### 使用
 	1、git clone https://github.com/wangle201210/laravel.git wangle1
 	2、composer install
 	3、php artisan key:
 	4、php artisan migrate


### 引入的包及其功能
## dingo
- API文档
- 频率限制
- 文档生成 php artisan api:docs --name lraveltest --use-version v1 --output-file ./public/documentation.md 或 php artisan make:doc

### 常用命令
 	创建控制器
 	php artisan make:controller XxxController --resource # 创建控制器
 	php artisan make:model Xxx  # 创建模型
 	php artisan api:generate --routePrefix="v1" --router="Dingo" # refresh docs
 	php artisan make:request XxxRequest
 	php artisan make:migration create_users_table --create=users
 	php artisan make:migration add_votes_to_users_table --table=users

### JWT
 	用户验证

### laratrust
 	权限控制

###Header
 	Content-Type : application/json
 	Authorization : Bearer token

### filter and sort
 	使用方法
 	class SomeModel extends Model{
 		use FilterAndSorting;


 	public function index(Request $request){
 		return SomeModel::setFilterAndRelationsAndSort($request)->get()


 	/message?filter={"created_at":{"from":"2016-02-20","to":"2016-02-24 23:59:59"}, "id":{"operation":"not in", "value":[2,3,4]}}
 	/message?filter={"id":{"from":2,"to":5}}
 	/message?filter={"id":{"to":5}} or /message?filter={"id":{"operation":"<=","value":5}}
 	/message?filter={"updated_at":{"isNull":true}}
 	/message?filter={"answer":{"operation":"like","value":"Partial search string"}}
 	/message?filter={"answer":"Full search string"}
 	/message?filter={"user.name":"asd"} # 关联搜索
 	/message?filter={"id":1}

 	# 暂时只支持单字段排序
 	/message?sort=id
 	/message?sort=-id
 	/message?sort=user.name

 	/message?expand=user # 关联搜索
 	response: { "id": 1, "message": "some message", "user_id": 1, ... "user": { "id": 1, "name": "Some username", ... } }

### redis 基本使用
 	Redis::set('key','value'); //存入redis
 	Redis::setex('key','time','value'); //设置键值和过期时间
 	Redis::setnx('key','value'); //如果没有键为key的那么,设置key的值为value
 	Redis::get('key'); //获取Redis中的值
 	Redis::incr('key'); //若原来key的值为1那么incr之后就会加1变为2
 	Redis::decr('key'); //若原来key的值为2那么incr之后就会减1变为1
 	Redis::del('key'); //删除键值
 	Redis::lLen('key'); //队列的长度
 	Redis::rpop('key'); //右侧出队列
 	Redis::rpush('key','value'); //右侧存入队列
 	Redis::exists($key) //redis是否存在这个键

### other

 	artisan migrate
 	artisan db:seed

 	artisan serve --host=127.0.0.1
 		ngrok --subdomain=xxxxx 8000

 	curl -X POST -F "name=xxx" -F "password=password" "http://localhost:8000/api/auth/login"

### 数据的恢复
 	$res = TABLE::withTrashed()->whereId($request->id)->restore();
 	if ($res) {
 		return $this->response->error('success', 201);
 	} else {
 		return $this->response->error('未知错误!', 200);
 	}
## status_code 状态码说明 出现参数 均表示表示业务处理出现错误。
 	200 会在message中显示错误,
 	202 出现错误 一般是参数不足 或会给出错误提示
 	203 出现错误 一般是授权不通过
 	210 出现错误 目标不存在
 	211 出现错误 业务重复
 	其它看情况再说明
## HTTP 状态码说明 与业务完成度有一定关系 并不绝对。
 	200 成功,
 	201 成功-完成新增或修改
 	202 接受 但是出现错误 一般是参数不足 或会给出错误提示
 	203 接受 但是出现错误 一般是授权不通过
 	204 成功-一般用于删除操作,表示完成
 	其它看情况再说明