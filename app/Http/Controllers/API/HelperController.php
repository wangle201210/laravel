<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis;

use zgldh\QiniuStorage\QiniuStorage;
use Pingpp;

class HelperController extends Controller
{
    
    /**
        @apiVersion 0.0.1
        @apiHeader {String} Authorization Bearer + token
        @apiHeaderExample {json} 头部列子:
        {
          "Authorization": "Bearer eyJ0eXAiOCI6NZO9ShwFEGVKskg"
        }
        @apiGroup helper
        @api {post} qiniu 测试qiniu云
        @apiName qiniu
        @apiParam {File} file 需要缓存的内容.
        @apiParam {String} path=pic 上传到的位置.
        @apiSuccessExample {string} 成功返回:
        HTTP/1.1 201 OK
        pic/oNWGBOzQihjUZBzltOrrKWLS87s4gghPsM52gnpq.jpeg
    */
    public function qiniu(Request $request)
    {
        $disk = QiniuStorage::disk('qiniu');
        $qnpath = $request->path ?? '/pic';// 七牛云目录
        $path = $disk->put($qnpath,$request->file('file'));
        return $path; //最终储存地址
    }
    public function ping()
    {
        Pingpp::setApiKey(config('pingpp.pk'));
        Pingpp::setPrivateKeyPath(resource_path('rsa_private_key.pem'));
        $a = Pingpp::Charge()->create([
            'order_no'  => '123456789',
            'amount'    => '1',
            'app'       => array('id' => 'app_DufvnPbbvzHCbbD8'),
            'channel'   => 'wx',
            'currency'  => 'cny',
            'client_ip' => '127.0.0.1',
            'subject'   => 'Your Subject',
            'body'      => 'Your Body'
        ]);
        return $a;
    }
    
    public function downloadFile(Request $request) {
        $filepath = storage_path('app/') . $request->filepath;
        $headers = array(
            'Content-Type: application/force-download',
        );
        return response()->download($filepath, null, $headers);
    }
}
