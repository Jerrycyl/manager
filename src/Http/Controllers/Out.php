<?php
namespace  cylcode\manager\Http\Controllers;
use cylcode\bear\Bear;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use cylcode\tools\arr\Arr;
use Illuminate\Support\Facades\Auth as a;
/**
 * 外部页面处理
 */
class Out extends WxBase
{
	/**
	 * [group description]
	 * @Author   Jerry                    (wx621201)
	 * @DateTime 2019-05-24T17:33:36+0800
	 * @Example  eg:
	 * @return   [type]                   [description]
	 */
  public function login(request $request){
  	if(isPost()){
      $info = $request->only(['username','password']);
      if(a::guard('manager')->attempt($info,true)){
          setcookie('managerAdmin', 1, time() + 43200, '/', '*' );##0.5天
        return redirect(route('manager.index'));
        ##登陆成功
      }else{
        ##登陆失败
        return Bear::layout()->error('登陆失败');
      }
  	}
  	return Bear::Layout()->extend(__DIR__.'/../../Views/out/login');
  }


  /**
   * [lock description]
   * @Author   Jerry                    (wx621201)
   * @DateTime 2019-06-05T18:02:10+0800
   * @Example  eg:
   * @return   [type]                   [description]
   */
  public function lock(request $request){
    if(a::guard('manager')->check()){
          $userInfo = current(a::guard('manager')->user());
          $userInfo['avatar'] = $userInfo['avatar']?$userInfo['avatar']:config('manager.default.avatar');
      }else{
         return redirect(route('manager.index'));
      }
   if(isPost()){
      $data = $request->only('password');
      $data['username'] = $userInfo['username'];
      if(a::guard('manager')->attempt($data,true)){
        session(['manager.lock'=>false]);
        return redirect(route('manager.index'));
        ##登陆成功
      }else{
        ##登陆失败
        return Bear::layout()->error('验证失败');
      }
    }
     
      // dump($userInfo);
    session(['manager.lock'=>true]);
    return Bear::Layout()->extend(__DIR__.'/../../Views/out/lock',$userInfo);
  }

   /**
     * User logout.
     *
     * @return Redirect
     */
    public function loginout(Request $request)
    {
        setcookie('managerAdmin', 0, time() + 3600, '/', 'szpp.org.cn' );##鹏博项目登出
        a::guard('manager')->logout();
        return redirect('/');
    }
   
  

}
