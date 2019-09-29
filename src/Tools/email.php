<?php

namespace  cylcode\manager\Tools;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth as a;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use cylcode\bear\Bear;
use cylcode\tools\arr\Arr;
use Mail;
use Closure;
/**
 * 邮箱处理
 */
class email
{
    public function send() {
     $name = '我发的第一份邮件'; 
     Mail::send('emails.test',['name'=>$name],function($message){ 
            $to = '123456789@qq.com'; $message ->to($to)->subject('邮件测试'); 
     }); 
     // 返回的一个错误数组，利用此可以判断是否发送成功
      dd(Mail::failures());
    } 

}