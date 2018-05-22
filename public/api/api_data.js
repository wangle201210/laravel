define({ "api": [  {    "version": "0.0.1",    "type": "post",    "url": "auth/login",    "title": "用户登陆",    "name": "auth_login",    "group": "User",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "String",            "optional": false,            "field": "name",            "description": "<p>姓名.</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": false,            "field": "password",            "description": "<p>密码.</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "     HTTP/1.1 201 OK\n     {\n\t\t  \"result\": {\n\t\t    \"token\": \"eyJ0eXAiOihKRRnreQ-Zw4\",\n\t\t    \"user\": {\n\t\t      \"id\": 7,\n\t\t      \"name\": \"wangle2\",\n\t\t      \"email\": \"285273594@qq.com\",\n\t\t      \"created_at\": \"2018-04-04 02:52:00\",\n\t\t      \"updated_at\": \"2018-04-04 02:52:00\"\n\t\t    }\n\t\t  }\n\t\t}",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/AuthController.php",    "groupTitle": "User"  },  {    "version": "0.0.1",    "type": "post",    "url": "auth/login",    "title": "用户退出",    "name": "auth_login_out",    "group": "User",    "header": {      "fields": {        "Header": [          {            "group": "Header",            "type": "String",            "optional": false,            "field": "Authorization",            "description": "<p>Bearer + token</p>"          }        ]      },      "examples": [        {          "title": "头部列子:",          "content": " {\n\t\"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n }",          "type": "json"        }      ]    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "     HTTP/1.1 201 OK\n     {\n\t\t\"message\": \"退出登陆成功!\"\n\t }",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/AuthController.php",    "groupTitle": "User"  },  {    "version": "0.0.1",    "type": "get",    "url": "auth/refresh",    "title": "刷新登陆信息",    "name": "auth_refresh",    "group": "User",    "header": {      "fields": {        "Header": [          {            "group": "Header",            "type": "String",            "optional": false,            "field": "Authorization",            "description": "<p>Bearer + token</p>"          }        ]      },      "examples": [        {          "title": "头部列子:",          "content": "{\n \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "     HTTP/1.1 201 OK\n     {\n\t  \"message\": \"登陆信息刷新成功!\",\n\t  \"data\": \"eyJ0eXAiOiJKvr-s\"\n\t }",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/AuthController.php",    "groupTitle": "User"  },  {    "version": "0.0.1",    "type": "get",    "url": "auth/resetpwd",    "title": "重置密码",    "name": "auth_resetpwd",    "group": "User",    "header": {      "fields": {        "Header": [          {            "group": "Header",            "type": "String",            "optional": false,            "field": "Authorization",            "description": "<p>Bearer + token</p>"          }        ]      },      "examples": [        {          "title": "头部列子:",          "content": "{\n \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 201 OK\n{\"id\":7,\"name\":\"wangle2\",\"email\":\"285273594@qq.com\",\"created_at\":\"2018-04-04 02:52:00\",\"updated_at\":\"2018-04-14 18:50:48\"}",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/AuthController.php",    "groupTitle": "User"  },  {    "version": "0.0.1",    "type": "get",    "url": "auth/user",    "title": "获取个人信息和用户组",    "name": "auth_user",    "group": "User",    "header": {      "fields": {        "Header": [          {            "group": "Header",            "type": "String",            "optional": false,            "field": "Authorization",            "description": "<p>Bearer + token</p>"          }        ]      },      "examples": [        {          "title": "头部列子:",          "content": "{\n \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "     HTTP/1.1 201 OK\n     {\n\t  \"data\": {\n\t    \"me\": {\n\t      \"id\": 7,\n\t      \"name\": \"wangle2\",\n\t      \"email\": \"285273594@qq.com\",\n\t      \"created_at\": \"2018-04-04 02:52:00\",\n\t      \"updated_at\": \"2018-04-14 18:33:01\"\n\t    },\n\t    \"roles\": [\n\t      \"user\"\n\t    ]\n\t  }\n\t }",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/AuthController.php",    "groupTitle": "User"  },  {    "version": "0.0.1",    "type": "post",    "url": "user/register",    "title": "注册用户",    "name": "user_register",    "group": "User",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "String",            "optional": false,            "field": "name",            "description": "<p>姓名.</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": false,            "field": "email",            "description": "<p>邮箱.</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": false,            "field": "password",            "description": "<p>密码.</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": false,            "field": "comfirm",            "description": "<p>确定密码.</p>"          }        ]      }    },    "success": {      "fields": {        "201": [          {            "group": "201",            "type": "Object",            "optional": false,            "field": "data",            "description": "<p>{&quot;name&quot;:&quot;wangle4&quot;,&quot;email&quot;:&quot;285273596@qq.com&quot;,&quot;updated_at&quot;:&quot;2018-04-11 15:24:06&quot;,&quot;created_at&quot;:&quot;2018-04-11 15:24:06&quot;,&quot;id&quot;:9}.</p>"          }        ]      }    },    "filename": "app/Http/Controllers/API/UserController.php",    "groupTitle": "User"  },  {    "version": "0.0.1",    "header": {      "examples": [        {          "title": "头部列子:",          "content": "{\n    \"Content-Type\": \"application/json\",\n    \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "article",    "type": "delete",    "url": "article/:id",    "title": "删除文章",    "name": "article_delete",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "id",            "description": "<p>文章id</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 204 OK",          "type": "null"        }      ]    },    "filename": "app/Http/Controllers/API/ArticlesController.php",    "groupTitle": "article"  },  {    "type": "get",    "url": "article/:id",    "title": "获取单条文章信息",    "version": "0.0.1",    "header": {      "examples": [        {          "title": "头部列子:",          "content": "{\n    \"Content-Type\": \"application/json\",\n    \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "article",    "name": "article_get",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "id",            "description": "<p>文章id</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 200 OK\n{\n  \"data\": {\n    \"id\": 2,\n    \"order\": 0,\n    \"user_id\": 1,\n    \"title\": \"\\u5206\\u7c7b1\",\n    \"type\": \"article\",\n    \"depth\": null,\n    \"parent_id\": 0,\n    \"display\": 1,\n    \"picture\": \"\",\n    \"description\": \"\",\n    \"point\": 0,\n    \"created_at\": \"2018-04-21 16:00:53\",\n    \"updated_at\": \"2018-04-21 16:00:53\",\n    \"deleted_at\": null\n  }\n}",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/ArticlesController.php",    "groupTitle": "article"  },  {    "version": "0.0.1",    "header": {      "examples": [        {          "title": "头部列子:",          "content": "{\n    \"Content-Type\": \"application/json\",\n    \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "article",    "type": "post",    "url": "articles",    "title": "添加文章",    "name": "article_save",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "String",            "optional": false,            "field": "title",            "description": "<p>标题</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": false,            "field": "content",            "description": "<p>内容</p>"          },          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "order",            "defaultValue": "0",            "description": "<p>排序</p>"          },          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "parent_id",            "defaultValue": "0",            "description": "<p>父文章id</p>"          },          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "display",            "defaultValue": "1",            "description": "<p>是否展示</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": true,            "field": "picture",            "description": "<p>文章缩略图</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": true,            "field": "description",            "description": "<p>文章简介</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 201 OK\n{\n  \"data\": {\n    \"title\": \"\\u6587\\u7ae0\\u6807\\u9898\",\n    \"content\": \"\\u6587\\u7ae0\\u5185\\u5bb9\",\n    \"updated_at\": \"2018-04-23 19:17:06\",\n    \"created_at\": \"2018-04-23 19:17:06\",\n    \"id\": 1\n  }\n}",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/ArticlesController.php",    "groupTitle": "article"  },  {    "version": "0.0.1",    "header": {      "examples": [        {          "title": "头部列子:",          "content": "{\n    \"Content-Type\": \"application/json\",\n    \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "article",    "type": "put",    "url": "article",    "title": "修改文章",    "name": "article_update",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "String",            "optional": true,            "field": "title",            "description": "<p>标题</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": true,            "field": "type",            "description": "<p>类型</p>"          },          {            "group": "Parameter",            "type": "Number",            "optional": true,            "field": "order",            "description": "<p>排序</p>"          },          {            "group": "Parameter",            "type": "Number",            "optional": true,            "field": "parent_id",            "description": "<p>父文章id</p>"          },          {            "group": "Parameter",            "type": "Number",            "optional": true,            "field": "display",            "description": "<p>是否展示</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": true,            "field": "picture",            "description": "<p>文章缩略图</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": true,            "field": "description",            "description": "<p>文章简介</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 201 OK\n{\n  \"data\": {\n    \"title\": \"文章1\",\n    \"type\": \"article\",\n    \"user_id\": 1,\n    \"order\": 0,\n    \"parent_id\": 0,\n    \"display\": \"1\",\n    \"picture\": \"\",\n    \"description\": \"\",\n    \"updated_at\": \"2018-04-21 16:00:53\",\n    \"created_at\": \"2018-04-21 16:00:53\",\n    \"id\": 2\n  }\n}",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/ArticlesController.php",    "groupTitle": "article"  },  {    "version": "0.0.1",    "header": {      "examples": [        {          "title": "头部列子:",          "content": "{\n    \"Content-Type\": \"application/json\",\n    \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "article",    "type": "get",    "url": "articles",    "title": "获取文章",    "name": "get_articles",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "per_page",            "defaultValue": "20",            "description": "<p>每页数量.</p>"          },          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "page",            "description": "<p>当前页.</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 200 OK\n{\n  \"data\": [\n    {\n      \"id\": 1,\n      \"order\": null,\n      \"user_id\": null,\n      \"user_id_edited\": null,\n      \"category_id\": null,\n      \"title\": \"\\u6587\\u7ae0\\u6807\\u9898\",\n      \"description\": null,\n      \"content\": \"\\u6587\\u7ae0\\u5185\\u5bb9\",\n      \"source_url\": null,\n      \"source\": null,\n      \"picture\": null,\n      \"tops\": 0,\n      \"is_comment\": 1,\n      \"point\": 0,\n      \"created_at\": \"2018-04-23 19:17:06\",\n      \"updated_at\": \"2018-04-23 19:17:06\",\n      \"deleted_at\": null\n    }\n  ],\n  \"meta\": {\n    \"pagination\": {\n      \"total\": 1,\n      \"count\": 1,\n      \"per_page\": 20,\n      \"current_page\": 1,\n      \"total_pages\": 1,\n      \"links\": []\n    }\n  }\n}",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/ArticlesController.php",    "groupTitle": "article"  },  {    "version": "0.0.1",    "header": {      "examples": [        {          "title": "头部列子:",          "content": "{\n    \"Content-Type\": \"application/json\",\n    \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "category",    "type": "delete",    "url": "category/:id",    "title": "删除分类",    "name": "category_delete",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "id",            "description": "<p>分类id</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 204 OK",          "type": "null"        }      ]    },    "filename": "app/Http/Controllers/API/CategoryController.php",    "groupTitle": "category"  },  {    "type": "get",    "url": "category/:id",    "title": "获取单条分类信息",    "version": "0.0.1",    "header": {      "examples": [        {          "title": "头部列子:",          "content": "{\n    \"Content-Type\": \"application/json\",\n    \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "category",    "name": "category_get",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "id",            "description": "<p>分类id</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 200 OK\n{\n  \"data\": {\n    \"id\": 2,\n    \"order\": 0,\n    \"user_id\": 1,\n    \"title\": \"\\u5206\\u7c7b1\",\n    \"type\": \"article\",\n    \"depth\": null,\n    \"parent_id\": 0,\n    \"display\": 1,\n    \"picture\": \"\",\n    \"description\": \"\",\n    \"point\": 0,\n    \"created_at\": \"2018-04-21 16:00:53\",\n    \"updated_at\": \"2018-04-21 16:00:53\",\n    \"deleted_at\": null\n  }\n}",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/CategoryController.php",    "groupTitle": "category"  },  {    "version": "0.0.1",    "header": {      "examples": [        {          "title": "头部列子:",          "content": "{\n    \"Content-Type\": \"application/json\",\n    \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "category",    "type": "post",    "url": "categories",    "title": "添加分类",    "name": "category_save",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "String",            "optional": false,            "field": "title",            "description": "<p>标题</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": false,            "field": "type",            "defaultValue": "article",            "description": "<p>类型</p>"          },          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "order",            "defaultValue": "0",            "description": "<p>排序</p>"          },          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "parent_id",            "defaultValue": "0",            "description": "<p>父分类id</p>"          },          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "display",            "defaultValue": "1",            "description": "<p>是否展示</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": true,            "field": "picture",            "description": "<p>分类缩略图</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": true,            "field": "description",            "description": "<p>分类简介</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 201 OK\n{\n  \"data\": {\n    \"title\": \"分类1\",\n    \"type\": \"article\",\n    \"user_id\": 1,\n    \"order\": 0,\n    \"parent_id\": 0,\n    \"display\": \"1\",\n    \"picture\": \"\",\n    \"description\": \"\",\n    \"updated_at\": \"2018-04-21 16:00:53\",\n    \"created_at\": \"2018-04-21 16:00:53\",\n    \"id\": 2\n  }\n}",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/CategoryController.php",    "groupTitle": "category"  },  {    "version": "0.0.1",    "header": {      "examples": [        {          "title": "头部列子:",          "content": "{\n    \"Content-Type\": \"application/json\",\n    \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "category",    "type": "put",    "url": "category",    "title": "修改分类",    "name": "category_update",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "String",            "optional": true,            "field": "title",            "description": "<p>标题</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": true,            "field": "type",            "description": "<p>类型</p>"          },          {            "group": "Parameter",            "type": "Number",            "optional": true,            "field": "order",            "description": "<p>排序</p>"          },          {            "group": "Parameter",            "type": "Number",            "optional": true,            "field": "parent_id",            "description": "<p>父分类id</p>"          },          {            "group": "Parameter",            "type": "Number",            "optional": true,            "field": "display",            "description": "<p>是否展示</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": true,            "field": "picture",            "description": "<p>分类缩略图</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": true,            "field": "description",            "description": "<p>分类简介</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 201 OK\n{\n  \"data\": {\n    \"title\": \"分类1\",\n    \"type\": \"article\",\n    \"user_id\": 1,\n    \"order\": 0,\n    \"parent_id\": 0,\n    \"display\": \"1\",\n    \"picture\": \"\",\n    \"description\": \"\",\n    \"updated_at\": \"2018-04-21 16:00:53\",\n    \"created_at\": \"2018-04-21 16:00:53\",\n    \"id\": 2\n  }\n}",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/CategoryController.php",    "groupTitle": "category"  },  {    "version": "0.0.1",    "header": {      "examples": [        {          "title": "头部列子:",          "content": "{\n    \"Content-Type\": \"application/json\",\n    \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "category",    "type": "get",    "url": "categories",    "title": "获取分类",    "name": "get_categories",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "per_page",            "defaultValue": "20",            "description": "<p>每页数量.</p>"          },          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "page",            "description": "<p>当前页.</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 200 OK\n{\n  \"data\": [\n    {\n      \"id\": 1,\n      \"order\": null,\n      \"user_id\": 1,\n      \"title\": \"ssss\",\n      \"type\": \"type\",\n      \"depth\": null,\n      \"parent_id\": 0,\n      \"display\": 1,\n      \"picture\": null,\n      \"description\": null,\n      \"point\": 0,\n      \"created_at\": \"2018-04-20 21:45:28\",\n      \"updated_at\": \"2018-04-20 21:45:28\",\n      \"deleted_at\": null\n    }\n  ],\n  \"meta\": {\n    \"pagination\": {\n      \"total\": 1,\n      \"count\": 1,\n      \"per_page\": 20,\n      \"current_page\": 1,\n      \"total_pages\": 1,\n      \"links\": []\n    }\n  }\n}",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/CategoryController.php",    "groupTitle": "category"  },  {    "version": "0.0.1",    "header": {      "fields": {        "Header": [          {            "group": "Header",            "type": "String",            "optional": false,            "field": "Authorization",            "description": "<p>Bearer + token</p>"          }        ]      },      "examples": [        {          "title": "头部列子:",          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOCI6NZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "helper",    "type": "post",    "url": "qiniu",    "title": "测试qiniu云",    "name": "qiniu",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "File",            "optional": false,            "field": "file",            "description": "<p>需要缓存的内容.</p>"          },          {            "group": "Parameter",            "type": "String",            "optional": false,            "field": "path",            "defaultValue": "pic",            "description": "<p>上传到的位置.</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 201 OK\npic/oNWGBOzQihjUZBzltOrrKWLS87s4gghPsM52gnpq.jpeg",          "type": "string"        }      ]    },    "filename": "app/Http/Controllers/API/HelperController.php",    "groupTitle": "helper"  },  {    "version": "0.0.1",    "header": {      "fields": {        "Header": [          {            "group": "Header",            "type": "String",            "optional": false,            "field": "Authorization",            "description": "<p>Bearer + token</p>"          }        ]      },      "examples": [        {          "title": "头部列子:",          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "test",    "type": "get",    "url": "tests",    "title": "测试获取article",    "name": "test_article",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "per_page",            "defaultValue": "20",            "description": "<p>每页数量.</p>"          },          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "page",            "description": "<p>当前页.</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 201 OK\n{\n    \"data\": [\n        {\n            \"id\": 1,\n            \"created_at\": \"2018-04-03 18:05:41\",\n            \"updated_at\": \"2018-04-03 18:05:35\",\n            \"test\": \"test\",\n            \"title\": \"test1\"\n        },\n        {\n            \"id\": 2,\n            \"created_at\": \"2018-04-03 18:05:41\",\n            \"updated_at\": \"2018-04-03 18:05:35\",\n            \"test\": \"test\",\n            \"title\": \"test2\"\n        }\n    ],\n    \"meta\": {\n        \"pagination\": {\n            \"total\": 19,\n            \"count\": 2,\n            \"per_page\": 2,\n            \"current_page\": 1,\n            \"total_pages\": 10,\n            \"links\": {\n            \"next\": \"http:\\/\\/l.t\\/api\\/tests?page=2\"\n            }\n        }\n    }\n}",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/TestController.php",    "groupTitle": "test"  },  {    "type": "get",    "url": "test/:id",    "title": "getArticle",    "version": "0.0.1",    "group": "test",    "name": "test_article_get",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "Number",            "optional": false,            "field": "id",            "description": "<p>Users unique ID.</p>"          }        ]      }    },    "filename": "app/Http/Controllers/API/TestController.php",    "groupTitle": "test"  },  {    "version": "0.0.1",    "header": {      "fields": {        "Header": [          {            "group": "Header",            "type": "String",            "optional": false,            "field": "Authorization",            "description": "<p>Bearer + token</p>"          }        ]      },      "examples": [        {          "title": "头部列子:",          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "test",    "type": "post",    "url": "tests",    "title": "测试上传article",    "name": "test_article_save",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "String",            "optional": false,            "field": "test",            "description": "<p>test.</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 201 OK\n{\n    \"data\": {\n        \"test\": \"ssss\",\n        \"updated_at\": \"2018-04-11 21:55:25\",\n        \"created_at\": \"2018-04-11 21:55:25\",\n        \"id\": 24,\n        \"title\": \"ssss24\"\n    }\n}",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/TestController.php",    "groupTitle": "test"  },  {    "version": "0.0.1",    "header": {      "fields": {        "Header": [          {            "group": "Header",            "type": "String",            "optional": false,            "field": "Authorization",            "description": "<p>Bearer + token</p>"          }        ]      },      "examples": [        {          "title": "头部列子:",          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "test",    "type": "put",    "url": "tests",    "title": "测试修改article",    "name": "test_article_update",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "String",            "optional": true,            "field": "test",            "description": "<p>test.</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 201 OK\n{\n    \"data\": {\n        \"test\": \"ssss\",\n        \"updated_at\": \"2018-04-11 21:55:25\",\n        \"created_at\": \"2018-04-11 21:55:25\",\n        \"id\": 24,\n        \"title\": \"ssss24\"\n    }\n}",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/TestController.php",    "groupTitle": "test"  },  {    "version": "0.0.1",    "header": {      "fields": {        "Header": [          {            "group": "Header",            "type": "String",            "optional": false,            "field": "Authorization",            "description": "<p>Bearer + token</p>"          }        ]      },      "examples": [        {          "title": "头部列子:",          "content": "{\n    \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "test",    "type": "get",    "url": "test/needauth",    "title": "测试needauth",    "name": "test_needauth",    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 201 OK\n\"user\": {\n    \"id\": 7,\n    \"name\": \"wangle2\",\n    \"email\": \"285273594@qq.com\",\n    \"created_at\": \"2018-04-04 02:52:00\",\n    \"updated_at\": \"2018-04-04 02:52:00\"\n}",          "type": "json"        }      ]    },    "filename": "app/Http/Controllers/API/TestController.php",    "groupTitle": "test"  },  {    "version": "0.0.1",    "header": {      "fields": {        "Header": [          {            "group": "Header",            "type": "String",            "optional": false,            "field": "Authorization",            "description": "<p>Bearer + token</p>"          }        ]      },      "examples": [        {          "title": "头部列子:",          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXAiOCI6NZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "test",    "type": "post",    "url": "test/qiniu",    "title": "测试qiniu云",    "name": "test_qiniu",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "File",            "optional": false,            "field": "file",            "description": "<p>需要缓存的内容.</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 201 OK\npic/oNWGBOzQihjUZBzltOrrKWLS87s4gghPsM52gnpq.jpeg",          "type": "string"        }      ]    },    "filename": "app/Http/Controllers/API/TestController.php",    "groupTitle": "test"  },  {    "version": "0.0.1",    "header": {      "fields": {        "Header": [          {            "group": "Header",            "type": "String",            "optional": false,            "field": "Authorization",            "description": "<p>Bearer + token</p>"          }        ]      },      "examples": [        {          "title": "头部列子:",          "content": "{\n  \"Authorization\": \"Bearer eyJ0eXuHZO9ShwFEGVKskg\"\n}",          "type": "json"        }      ]    },    "group": "test",    "type": "get",    "url": "test/redis",    "title": "测试redis",    "name": "test_redis",    "parameter": {      "fields": {        "Parameter": [          {            "group": "Parameter",            "type": "String",            "optional": false,            "field": "string",            "description": "<p>需要缓存的内容.</p>"          }        ]      }    },    "success": {      "examples": [        {          "title": "成功返回:",          "content": "HTTP/1.1 201 OK\nstring",          "type": "string"        }      ]    },    "filename": "app/Http/Controllers/API/TestController.php",    "groupTitle": "test"  }] });
