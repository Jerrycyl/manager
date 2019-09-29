<?php

namespace  cylcode\manager\Api\xcx;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use cylcode\tools\arr\Arr;
use Illuminate\Http\Request;
use cylcode\bear\Bear;
use Validator;
class Login extends Base
{
	 /**
     * 接口作用说明
     */
    public $description = '用户登录';

   /**
    * [params 入参]
    * @Author   Jerry                    (wx621201)
    * @DateTime 2019-07-19T14:25:01+0800
    * @Example  eg:
    * @return   [type]                   [description]
    */
    public function params()
    {
        return [
            
        ];
    }

   /**
    * [handle 执行值]
    * @Author   Jerry                    (wx621201)
    * @DateTime 2019-07-19T14:25:12+0800
    * @Example  eg:
    * @param    [type]                   $params    [description]
    * @return   [type]                              [description]
    */
    public function handle(Request $request)
    {
    	$code = $request->input('code');
    	$info = $this->app->auth->session($code);
    	return $info;
      
    }
	
	  /**
     * 返回json格式的例子
     * @return string 结果json串
     */
    public function returnJson()
    {
        return '{
                  "errorcode": 0,
                  "msg": "",
                  "data": 
                  {
                    "user_id": 48926,
                    "accessToken": "c210bdb66e0eea2c640295ed496c72f166465d42f6d3c2d96199722bf74138a9"
                  }
                }';
    }
	
}
