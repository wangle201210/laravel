FORMAT: 1A

# Laravel

# UserController [/api/user]

## 用户注册 [POST /api/user/register]
Register a new user with a `username` and `password`.

+ Parameters
    + name: (string, required) - 姓名
    + email: (string, required) - 邮箱
    + password: (string, required) - 密码
    + comfirm: (string, required) - 确认密码

+ Response 201 (application/json)
    + Body

            {
                "data": {
                    "name": "wangle4",
                    "email": "285273596@qq.com",
                    "updated_at": "2018-04-11 15:24:06",
                    "created_at": "2018-04-11 15:24:06",
                    "id": 9
                }
            }

# AppController [/api]
请将header中的Authorization token设置正确

## 测试redis [GET /api/redis]


## 测试jwt [GET /api/needAuth]


## 增加文章 Add Test [GET /api/tests]


## 增加文章 Add Test [POST /api/test]


## 显示特定的文章 Display the specified Test. [GET /api/test/{id}]


## 更新文章 Update the specified Test [PUT /api/test/{id}]


## 删除文章 Remove the specified Test [DELETE /api/test]